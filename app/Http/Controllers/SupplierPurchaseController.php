<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierPurchase;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierPurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $supplier = Supplier::findOrFail($request->supplier_id);

            foreach ($request->items as $item) {
                $totalPrice = $item['quantity'] * $item['purchase_price'];

                // 1. Create Purchase Record
                $purchase = SupplierPurchase::create([
                    'supplier_id' => $supplier->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'total_price' => $totalPrice,
                    'notes' => $item['notes'],
                    'purchase_date' => $request->purchase_date,
                    'user_id' => Auth::id(),
                ]);

                // 2. Update Product Cost Price
                $product = Product::findOrFail($item['product_id']);
                $product->update([
                    'cost_price' => $item['purchase_price']
                ]);

                // 3. Update Stock
                $stock = Stock::where('product_id', $product->id)->first();
                $qtyBefore = $stock ? $stock->quantity : 0;

                if ($stock) {
                    $stock->increment('quantity', $item['quantity']);
                } else {
                    $stock = Stock::create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'min_stock' => 10,
                    ]);
                }
                $qtyAfter = $stock->quantity;

                // 4. Record Stock Movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity_before' => $qtyBefore,
                    'quantity_after' => $qtyAfter,
                    'quantity_change' => $item['quantity'],
                    'reason' => 'Pasokan dari Supplier: ' . $supplier->name . ($item['notes'] ? ' (' . $item['notes'] . ')' : ''),
                    'reference' => 'SUP-PUR-' . $purchase->id,
                ]);
            }

            // 5. Update Supplier Last Purchase Date
            $supplier->update([
                'last_purchase_at' => now()
            ]);

            DB::commit();

            return redirect()->back()->with('success', __('admin.supply_recorded_success'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}

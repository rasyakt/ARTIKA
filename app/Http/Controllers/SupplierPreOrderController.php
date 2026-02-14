<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supplier;
use App\Models\Product;
use App\Models\SupplierPreOrder;
use App\Models\SupplierPreOrderItem;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\SupplierPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplierPreOrderController extends Controller
{
    public function index()
    {
        $preOrders = SupplierPreOrder::with(['supplier', 'user'])->latest()->paginate(10);
        return view('admin.suppliers.pre_orders.index', compact('preOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $units = \App\Models\Unit::all();
        return view('admin.suppliers.pre_orders.create', compact('suppliers', 'products', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_arrival_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.unit_name' => 'required|string',
            'items.*.pcs_per_unit' => 'required|integer|min:1',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['pcs_per_unit'] * $item['unit_price'];
            }

            $preOrder = SupplierPreOrder::create([
                'uuid' => (string) Str::uuid(),
                'supplier_id' => $request->supplier_id,
                'reference_number' => $request->reference_number,
                'status' => 'pending',
                'expected_arrival_date' => $request->expected_arrival_date,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['pcs_per_unit'] * $item['unit_price'];
                SupplierPreOrderItem::create([
                    'supplier_pre_order_id' => $preOrder->id,
                    'product_id' => $item['product_id'],
                    'unit_name' => $item['unit_name'],
                    'pcs_per_unit' => $item['pcs_per_unit'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.suppliers.pre_orders.index')->with('success', 'Pre-order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show(SupplierPreOrder $preOrder)
    {
        $preOrder->load(['supplier', 'user', 'items.product']);
        return view('admin.suppliers.pre_orders.show', compact('preOrder'));
    }

    public function updateStatus(Request $request, SupplierPreOrder $preOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,ordered,shipped,received,cancelled',
        ]);

        if ($preOrder->status === 'received') {
            return redirect()->back()->with('error', 'Cannot change status of a received pre-order.');
        }

        if ($request->status === 'received') {
            return $this->markAsReceived($preOrder);
        }

        $preOrder->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function markAsReceived(SupplierPreOrder $preOrder)
    {
        if ($preOrder->status === 'received') {
            return redirect()->back()->with('error', 'Pre-order is already marked as received.');
        }

        try {
            DB::beginTransaction();

            foreach ($preOrder->items as $item) {
                // 1. Update Product Cost Price
                $item->product->update([
                    'cost_price' => $item->unit_price
                ]);

                // 2. Update Stock
                $stock = Stock::where('product_id', $item->product_id)->first();
                $qtyBefore = $stock ? $stock->quantity : 0;

                $incrementAmount = $item->quantity * $item->pcs_per_unit;

                if ($stock) {
                    $stock->increment('quantity', $incrementAmount);
                } else {
                    $stock = Stock::create([
                        'product_id' => $item->product_id,
                        'quantity' => $incrementAmount,
                        'min_stock' => 10,
                    ]);
                }
                $qtyAfter = $stock->quantity;

                // 3. Record Stock Movement
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity_before' => $qtyBefore,
                    'quantity_after' => $qtyAfter,
                    'quantity_change' => $incrementAmount,
                    'reason' => 'Penerimaan Pre-order: ' . $preOrder->uuid . ' (' . $item->quantity . ' ' . $item->unit_name . ')',
                    'reference' => 'PRE-ORDER-' . $preOrder->id,
                ]);

                // 4. Create Supplier Purchase Record (Optional but recommended for compatibility)
                SupplierPurchase::create([
                    'uuid' => (string) Str::uuid(),
                    'supplier_id' => $preOrder->supplier_id,
                    'product_id' => $item->product_id,
                    'quantity' => $incrementAmount,
                    'purchase_price' => $item->unit_price, // unit_price is now stored as price per Pcs (HPP)
                    'total_price' => $item->subtotal,
                    'notes' => 'From Pre-order: ' . $preOrder->uuid . ' (' . $item->quantity . ' ' . $item->unit_name . ')',
                    'purchase_date' => now(),
                    'user_id' => Auth::id(),
                ]);
            }

            $preOrder->update(['status' => 'received']);

            // Update Supplier Last Purchase Date
            $preOrder->supplier->update([
                'last_purchase_at' => now()
            ]);

            DB::commit();

            return redirect()->route('admin.suppliers.pre_orders.index')->with('success', 'Pre-order marked as received and stock updated!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function printFaktur($id)
    {
        $preOrder = SupplierPreOrder::with(['supplier', 'items.product', 'user'])->findOrFail($id);

        if ($preOrder->status !== 'received') {
            return redirect()->back()->with('error', 'Faktur hanya dapat dicetak untuk pesanan yang sudah diterima.');
        }

        return view('admin.suppliers.pre_orders.print-faktur', compact('preOrder'));
    }
}

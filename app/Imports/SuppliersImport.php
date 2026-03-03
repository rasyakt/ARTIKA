<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierPurchase;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SuppliersImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private $supplierId;

    public function __construct($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    public function prepareForValidation($data, $index)
    {
        foreach ($data as $key => $value) {
            if ($value !== null && !is_array($value)) {
                $data[$key] = (string) $value;
            }
        }
        return $data;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            $supplier = Supplier::findOrFail($this->supplierId);

            foreach ($rows as $row) {
                // Filter out empty rows or instruction rows
                if (empty($row['barcode']) || empty($row['jumlah'])) {
                    continue;
                }

                $product = Product::where('barcode', $row['barcode'])->first();
                if (!$product) {
                    continue; // Skip if product barcode not registered in master data
                }

                $quantity = (int) $row['jumlah'];
                $pcsPerUnit = (int) ($row['pcs_per_satuan'] ?? 1);
                $purchasePrice = (float) $row['harga_beli_per_pcs'];
                $totalPrice = $quantity * $pcsPerUnit * $purchasePrice;

                // 1. Create Purchase Record
                $purchase = SupplierPurchase::create([
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'supplier_id' => $supplier->id,
                    'product_id' => $product->id,
                    'unit_name' => $row['satuan'] ?? 'Pcs',
                    'pcs_per_unit' => $pcsPerUnit,
                    'quantity' => $quantity,
                    'purchase_price' => $purchasePrice,
                    'total_price' => $totalPrice,
                    'notes' => $row['catatan'] ?? null,
                    'purchase_date' => now(),
                    'user_id' => Auth::id(),
                ]);

                // 2. Update Product Cost Price
                $product->update([
                    'cost_price' => $purchasePrice
                ]);

                // 3. Update Stock
                $stock = Stock::where('product_id', $product->id)->first();
                $qtyBefore = $stock ? $stock->quantity : 0;
                $incrementAmount = $quantity * $pcsPerUnit;

                if ($stock) {
                    $stock->increment('quantity', $incrementAmount);
                } else {
                    $stock = Stock::create([
                        'product_id' => $product->id,
                        'quantity' => $incrementAmount,
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
                    'quantity_change' => $incrementAmount,
                    'reason' => 'Pasokan dari Supplier (import massal): ' . $supplier->name . ' (' . $quantity . ' ' . ($row['satuan'] ?? 'Pcs') . ')' . ($row['catatan'] ? ' - ' . $row['catatan'] : ''),
                    'reference' => 'SUP-PUR-' . $purchase->id,
                ]);
            }

            // 5. Update Supplier Last Purchase Date
            $supplier->update([
                'last_purchase_at' => now()
            ]);
        });
    }

    public function rules(): array
    {
        return [
            'barcode' => ['nullable', 'string', 'max:255'],
            'satuan' => ['nullable', 'string', 'max:50'],
            'pcs_per_satuan' => ['nullable', 'numeric', 'min:1'],
            'jumlah' => ['nullable', 'numeric', 'min:1'],
            'harga_beli_per_pcs' => ['nullable', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ];
    }
}

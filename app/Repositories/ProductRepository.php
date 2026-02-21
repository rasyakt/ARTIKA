<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\Stock;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts()
    {
        return Product::with('stocks')->get();
    }

    public function findProductByBarcode($barcode)
    {
        return Product::where('barcode', $barcode)->first();
    }

    public function updateStock($productId, $quantity)
    {
        // FIFO: Deduct from earliest expiring batches first, then by earliest created
        $remainingToDeduct = $quantity;

        $stocks = Stock::where('product_id', $productId)
            ->where('quantity', '>', 0)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->orderBy('expired_at', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($stocks as $stock) {
            /** @var Stock $stock */
            if ($remainingToDeduct <= 0)
                break;

            if ($stock->quantity >= $remainingToDeduct) {
                $stock->decrement('quantity', $remainingToDeduct);
                $remainingToDeduct = 0;
            } else {
                $remainingToDeduct -= $stock->quantity;
                $stock->update(['quantity' => 0]);
            }
        }

        // If still remaining (oversold), throw exception instead of allowing negative stock
        if ($remainingToDeduct > 0) {
            throw new \Exception("Insufficient stock for product ID {$productId}. Short by {$remainingToDeduct} units.");
        }
    }

    public function incrementStock($productId, $quantity)
    {
        // Return stock to the latest batch or the one with no expiry
        // In a more complex system, we might track which batch was sold
        $stock = Stock::where('product_id', $productId)
            ->orderBy('expired_at', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if ($stock) {
            $stock->increment('quantity', $quantity);
        } else {
            // Create a default stock record if none exists
            Stock::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'min_stock' => 10
            ]);
        }
    }
}

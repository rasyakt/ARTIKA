<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\Stock;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts()
    {
        return Product::all();
    }

    public function findProductByBarcode($barcode)
    {
        return Product::where('barcode', $barcode)->first();
    }

    public function updateStock($productId, $quantity)
    {
        // Decrement stock for the branch (assuming branch 1 for now or passed branch)
        // For simplicity, we'll just find the first stock record for this product
        // In reality, we need branch_id context.
        $stock = Stock::where('product_id', $productId)->first();
        if ($stock) {
            $stock->decrement('quantity', $quantity);
        }
    }
}

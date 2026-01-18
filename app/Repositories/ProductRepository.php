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
        // Decrement stock for the product
        $stock = Stock::where('product_id', $productId)->first();
        if ($stock) {
            $stock->decrement('quantity', $quantity);
        }
    }
}

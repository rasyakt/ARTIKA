<?php

namespace App\Interfaces;

interface ProductRepositoryInterface
{
    public function getAllProducts();
    public function findProductByBarcode($barcode);
    public function updateStock($productId, $quantity);
}

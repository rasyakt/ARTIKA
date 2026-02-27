<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private $categories;

    public function __construct()
    {
        // Cache existing categories to avoid N+1 queries
        $this->categories = Category::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtolower(trim($name)) => $id];
        })->toArray();
    }

    public function prepareForValidation($data, $index)
    {
        foreach ($data as $key => $value) {
            if ($value !== null && !is_array($value)) {
                // Cast all scalar values to string to avoid "validation.string" and "max" errors on numeric formats
                $data[$key] = (string) $value;
            }
        }
        return $data;
    }

    public function model(array $row)
    {
        $categoryName = trim($row['kategori'] ?? '');
        $categoryId = null;

        if (!empty($categoryName)) {
            $lowerKey = strtolower($categoryName);
            if (isset($this->categories[$lowerKey])) {
                $categoryId = $this->categories[$lowerKey];
            } else {
                // Auto-create category if it does not exist
                $newCategory = Category::create([
                    'name' => $categoryName,
                    'slug' => \Illuminate\Support\Str::slug($categoryName),
                    'color' => '#6B7280' // default neutral color
                ]);
                $categoryId = $newCategory->id;
                $this->categories[$lowerKey] = $categoryId; // update cache
            }
        }

        return new Product([
            'barcode' => $row['barcode'],
            'name' => $row['nama_produk'],
            'category_id' => $categoryId,
            'price' => $row['harga_jual'],
            'cost_price' => $row['harga_modal'] ?? 0,
            'description' => $row['deskripsi'],
        ]);
    }

    public function rules(): array
    {
        return [
            'barcode' => ['required', 'string', 'max:255', 'unique:products,barcode'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'harga_modal' => ['nullable', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts()
    {
        return Product::all();
    }

    public function getAllProductsSortedViaViews()
    {
        return Product::query()->orderBy('views', 'DESC')->get();
    }

    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function getViaId($id)
    {
        return Product::find($id);
    }
}

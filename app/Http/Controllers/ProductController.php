<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
      $this->productRepository = $productRepository;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->productRepository->getAllProductsSortedViaViews()
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $data = [
                'name' => $request->post('name'),
                'price' => $request->post('price'),
                'released_at' => $request->post('released_at'),
                'availiable_quantity' => $request->post('availiable_quantity')
            ];
            $product = $this->productRepository->createProduct($data);

            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productRepository->getViaId($id);

            if(!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found'
                ]);
            }
            //for every product view, increase views by 1
            $product->increment('views');

            return response()->json([
                'success' => true,
                'data' => $product->toArray()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

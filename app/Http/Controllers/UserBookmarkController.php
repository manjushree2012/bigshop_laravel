<?php

namespace App\Http\Controllers;

use App\Models\UserBookmark;
use App\Http\Requests\StoreUserBookmarkRequest;
use App\Http\Requests\UpdateUserBookmarkRequest;

class UserBookmarkController extends Controller
{
    public function index()
    {
        try {
            $bookmarks = auth()->user()->bookmarks;
            return response()->json([
                'success' => true,
                'data' => $bookmarks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function add($productId)
    {
        try {
            $product = \App\Models\Product::find($productId);
            if(!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid product given.'
                ], 400);
            }
            auth()->user()->bookmarks()->syncWithoutDetaching($product->id);
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function remove($productId)
    {
        try {
            $detached = auth()->user()->bookmarks()->detach($productId);
            if(!$detached) {
                return response()->json([
                    'success' => false,
                    'message' => 'No records affected'
                ], 400);
            }
            return response()->json([
                'success' => true,
                'message' => "$detached records affected."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

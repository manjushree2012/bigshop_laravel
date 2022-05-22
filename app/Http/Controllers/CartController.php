<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $user = auth()->user();
            $existingCart = $user->cart;

            $product = Product::find($request->product_id);
            if(!$product) {
                throw new \Exception('Ordered product unavailiable at stock.');
            }
            if($product->availiable_quantity < $request->quantity) {
                throw new \Exception('Ordered quantity unavailiable at stock.');
            }

            if(!$existingCart) { //for first time, create one
                $existingCart = Cart::create([
                    'user_id' => $user->id
                ]);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'cart_id' => $existingCart->id
            ]);

            if($order) {
                //after order places, decrease the availiable quantity for that product
                $product->decrement('availiable_quantity', $request->quantity);

                return response()->json([
                    'success' => true,
                    'message' => 'Order succesfully added to cart'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Order not added to cart'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteFromCart(Request $request)
    {
        try {
            $orderToDelete = $request->order_id;
            $order = Order::where('user_id', auth()->user()->id)
                    ->where('id', $orderToDelete)
                    ->get()
                    ->first();

            if($order) {
                //when order removed, put back to stock
                $product_id = $order->product_id;
                $product = Product::find($product_id);
                $product->increment('availiable_quantity', $order->quantity);

                $isDeleted = $order->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Order succesfully removed from cart'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

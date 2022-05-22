<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;

class CartTest extends TestCase
{
    use HasFactory, WithFaker;

    public function testAddToCartSuccess()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => $this->faker->name(),
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "availiable_quantity" => $this->faker->numberBetween($min=1, $max=100)
       ]);

       $productData = [
           'product_id' => $product->id,
           'quantity' => $product->availiable_quantity - 1
       ];

       $response = $this->json('POST', 'api/cart/add/order', $productData, ['Accept' => 'application/json'])
           ->assertStatus(200)
           ->assertJsonFragment([
               'success' => true
           ]);
    }

    public function testAddToCartDeletedProduct()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => $this->faker->name(),
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "availiable_quantity" => $this->faker->numberBetween($min=1, $max=100)
       ]);
       $productData = [
           'product_id' => $product->id,
           'quantity' => $product->availiable_quantity - 1
       ];
       $product->delete();

       $response = $this->json('POST', 'api/cart/add/order', $productData, ['Accept' => 'application/json'])
           ->assertStatus(500)
           ->assertJsonFragment([
               'success' => false
           ]);
    }

    public function testAddToCartUnavailiableProduct()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => $this->faker->name(),
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "availiable_quantity" => $this->faker->numberBetween($min=1, $max=100)
       ]);

       $productData = [
           'product_id' => $product->id,
           'quantity' => $product->availiable_quantity + 1
       ];

       $response = $this->json('POST', 'api/cart/add/order', $productData, ['Accept' => 'application/json'])
           ->assertStatus(500)
           ->assertJsonFragment([
               'success' => false
           ]);
    }

    public function testDeleteFromCartSuccess()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => $this->faker->name(),
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "availiable_quantity" => $this->faker->numberBetween($min=1, $max=100)
       ]);

       $cart = Cart::create([
           'user_id' => $user->id
       ]);

       $order = Order::create([
           'user_id' => $user->id,
           'product_id' => $product->id,
           'quantity' => $product->availiable_quantity - 1,
           'cart_id' => $cart->id
       ]);

       $orderData = [
           'order_id' => $order->id
       ];

       $response = $this->json('POST', 'api/cart/delete/order', $orderData, ['Accept' => 'application/json'])
           ->assertStatus(200)
           ->assertJsonFragment([
               'success' => true
           ]);

       $this->assertModelMissing($order);
    }

    public function testDeleteFromCartForDeletedOrder()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => $this->faker->name(),
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "availiable_quantity" => $this->faker->numberBetween($min=1, $max=100)
       ]);

       $cart = Cart::create([
           'user_id' => $user->id
       ]);

       $order = Order::create([
           'user_id' => $user->id,
           'product_id' => $product->id,
           'quantity' => $product->availiable_quantity - 1,
           'cart_id' => $cart->id
       ]);

       $orderData = [
           'order_id' => $order->id
       ];

       $order->delete();

       $response = $this->json('POST', 'api/cart/delete/order', $orderData, ['Accept' => 'application/json'])
           ->assertStatus(400)
           ->assertJsonFragment([
               'success' => false
           ]);
    }


}

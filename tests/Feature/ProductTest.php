<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ProductTest extends TestCase
{
    use HasFactory, WithFaker;

    public function testProductsListSuccess()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => 'Lorem Ipsum Product',
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "views" => $this->faker->numberBetween(0,1000)
       ]);

       $response = $this->json('GET', 'api/products', ['Accept' => 'application/json']);
       $findProduct = (collect(json_decode($response->getContent())->data)->filter(function($item) use ($product) {
           return $item->id == $product->id;
       }));
       $this->assertTrue(!empty($findProduct));
    }

    public function testRetrieveExistingProduct()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => 'Lorem Ipsum Product',
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "views" => $this->faker->numberBetween(0,1000)
       ]);

       $this->assertModelExists($product);
    }

    public function testRetrieveDeletedProduct()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => 'Lorem Ipsum Product',
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "views" => $this->faker->numberBetween(0,1000)
       ]);
       $product->delete();

       $this->assertModelMissing($product);
    }

    public function testBookmarkProduct()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => 'Lorem Ipsum Product',
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "views" => $this->faker->numberBetween(0,1000)
       ]);

       $response = $this->json('POST', "/api/product/$product->id/bookmark", ['Accept' => 'application/json'])
                    ->assertStatus(200)
                    ->assertJsonFragment([
                        'success' => true
                    ]);
    }

    public function testUnbookmarkProduct()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => 'Lorem Ipsum Product',
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "views" => $this->faker->numberBetween(0,1000)
       ]);

       $userBookmark = \App\Models\UserBookmark::create([
           'user_id' => $user->id,
           'product_id' => $product->id
       ]);

       $response = $this->json('POST', "/api/product/$product->id/unbookmark", ['Accept' => 'application/json'])
                    ->assertStatus(200)
                    ->assertJsonFragment([
                        'success' => true
                    ]);

        $this->assertModelMissing($userBookmark);
    }

    public function testUnbookmarkDeletedProduct()
    {
        $user = User::factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = Product::factory(Product::class)->create([
           "name" => 'Lorem Ipsum Product',
           "price" => $this->faker->numberBetween($min=1000, $max=10000),
           "released_at" => $this->faker->dateTimeBetween('-1 week', '+1 week'),
           "views" => $this->faker->numberBetween(0,1000)
       ]);

       $userBookmark = \App\Models\UserBookmark::create([
           'user_id' => $user->id,
           'product_id' => $product->id
       ]);

       $userBookmark->delete();

       $response = $this->json('POST', "/api/product/$product->id/unbookmark", ['Accept' => 'application/json'])
                    ->assertStatus(400)
                    ->assertJsonFragment([
                        'success' => false
                    ]);

        $this->assertModelMissing($userBookmark);
    }

}

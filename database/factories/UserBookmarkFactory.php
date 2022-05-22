<?php

// namespace Database\Factories;
//
// use Illuminate\Database\Eloquent\Factories\Factory;
//
// /**
//  * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserBookmark>
//  */
// class UserBookmarkFactory extends Factory
// {
//     /**
//      * Define the model's default state.
//      *
//      * @return array<string, mixed>
//      */
//     public function definition()
//     {
//         return [
//           'user_id'       => factory('App\User')->create()->id,
//           'product_id'    => factory('App\Product')->create()->id
//         ];
//     }
// }

use App\Models\UserBookmark;
use Faker\Generator as Faker;

$factory->define(UserBookmark::class, function (Faker $faker) {
    return [
        'user_id' => factory('App\User')->create()->id,
        'product_id' => factory('App\Product')->create()->id,
    ];
});

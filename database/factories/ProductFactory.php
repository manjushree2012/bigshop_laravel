<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
          'name'       => $this->faker->name(),
          'price'      => $this->faker->numberBetween($min=1000, $max=10000),
          'released_at' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
          'views' => $this->faker->numberBetween(0,1000),
          'availiable_quantity' => $this->faker->numberBetween(1,100)
        ];
    }
}

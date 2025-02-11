<?php

namespace Database\Factories;

use Illuminate\Support\Str;
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
    public function definition(): array
    {
        $name = $this->faker->word;
        return 
        [
           'name' => $name,
           'slug' => Str::slug($name),
           'cover'=>$this->faker->imageUrl,
           'price'=>$this->faker->randomFloat(1,20,30),
           'description'=>$this->faker->sentence,
           'stock'=>$this->faker->randomDigit(),
        ];
    }
}

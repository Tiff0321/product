<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class BrandFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Brand::class;
    public function definition(): array
    {

        return [
            'name' => $this->faker->unique()->company,
        ];
    }

}

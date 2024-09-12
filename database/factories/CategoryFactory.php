<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends Factory
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            // parent_id 将在 seeder 中设置
        ];
    }
}

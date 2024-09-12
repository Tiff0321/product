<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建一些顶级分类
        Category::factory()->count(5)->create(['parent_id' => null])->each(function ($category) {
            // 为每个顶级分类创建子分类
            $category->children()->saveMany(Category::factory()->count(3)->make());
        });
    }
}

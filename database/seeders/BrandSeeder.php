<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建20个随机品牌
        Brand::factory()->count(20)->create();

        // 创建一些特定的品牌
        $specificBrands = [
            '苹果',
            '三星',
            '华为',
            '小米',
            '索尼',
        ];

        foreach ($specificBrands as $brandName) {
            Brand::factory()->create(['name' => $brandName]);
        }
    }
}

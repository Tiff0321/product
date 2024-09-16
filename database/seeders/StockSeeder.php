<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取所有商品
        $products = Product::all();

        // 可能的仓库位置
        $warehouseLocations = ['A', 'B', 'C', 'D', 'E'];

        foreach ($products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'quantity' => rand(0, 100),  // 随机库存数量,0-100
                'warehouse_location' => $warehouseLocations[array_rand($warehouseLocations)] . '-' . rand(1, 20),  // 随机仓库位置
            ]);
        }

        $this->command->info('库存数据填充完成!');
    }
}

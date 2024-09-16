<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('请先确保有用户和产品数据');
            return;
        }

        foreach ($users as $user) {
            // 获取用户已有的收藏和购买记录
            $existingFavorites = $user->favoriteProducts()->pluck('product_id')->toArray();
            $existingPurchases = $user->purchasedProducts()->pluck('product_id')->toArray();

            // 随机收藏 1-5 个新产品
            $favoritesCount = rand(1, 5);
            $newFavorites = $products->whereNotIn('id', $existingFavorites)->random(min($favoritesCount, $products->count() - count($existingFavorites)))->pluck('id')->toArray();

            foreach ($newFavorites as $productId) {
                DB::table('user_product')->insertOrIgnore([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'type' => 'favorite',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 随机购买 1-3 个新产品
            $purchasedCount = rand(1, 3);
            $newPurchases = $products->whereNotIn('id', $existingPurchases)->random(min($purchasedCount, $products->count() - count($existingPurchases)))->pluck('id')->toArray();

            foreach ($newPurchases as $productId) {
                DB::table('user_product')->insertOrIgnore([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'type' => 'purchased',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('用户产品关系数据填充完成!');

    }
}

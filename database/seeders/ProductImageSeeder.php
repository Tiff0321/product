<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取所有产品
        $products = Product::all();

        // 示例图片URL数组
        $sampleImageUrls = [
            'https://via.placeholder.com/400x300?text=Product+Image+1',
            'https://via.placeholder.com/400x300?text=Product+Image+2',
            'https://via.placeholder.com/400x300?text=Product+Image+3',
            'https://via.placeholder.com/400x300?text=Product+Image+4',
            'https://via.placeholder.com/400x300?text=Product+Image+5',
        ];

        foreach ($products as $product) {
            // 为每个产品随机选择1-3张图片
            $imageCount = rand(1, 3);
            $selectedImages = array_rand($sampleImageUrls, $imageCount);

            if (!is_array($selectedImages)) {
                $selectedImages = [$selectedImages];
            }

            foreach ($selectedImages as $imageIndex) {
                $imageUrl = $sampleImageUrls[$imageIndex];

                // 下载图片并保存到本地
                $contents = file_get_contents($imageUrl);
                $filename = 'product_' . $product->id . '_' . uniqid() . '.jpg';
                Storage::disk('public')->put('product_images/' . $filename, $contents);

                // 创建产品图片记录
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => 'product_images/' . $filename,
//                    'is_primary' => $imageCount === 1 || $imageIndex === $selectedImages[0], // 如果只有一张图片或是第一张图片,则设为主图
                ]);
            }
        }

        $this->command->info('产品图片数据填充完成!');
    }
}

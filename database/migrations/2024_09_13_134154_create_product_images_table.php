<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id()->comment('商品图片ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->string('image_url')->comment('图片URL');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->comment('外键，关联商品表');
        });

        // 添加表注释
        DB::statement("ALTER TABLE `product_images` comment '商品图片表，存储商品的图片信息'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
};

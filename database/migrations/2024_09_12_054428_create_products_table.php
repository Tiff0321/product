<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id()->comment('商品ID');
            $table->string('name')->comment('商品名称');
            $table->text('description')->nullable()->comment('商品描述');
            $table->decimal('price', 10, 2)->comment('商品价格');
            $table->unsignedBigInteger('category_id')->nullable()->comment('分类ID');
            $table->unsignedBigInteger('brand_id')->nullable()->comment('品牌ID');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};

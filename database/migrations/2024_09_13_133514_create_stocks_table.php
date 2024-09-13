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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id()->comment('库存ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->integer('quantity')->default(0)->comment('库存数量');
            $table->string('warehouse_location')->nullable()->comment('仓库位置');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->comment('外键，关联商品表');
        });
        // 添加表注释
        DB::statement("ALTER TABLE `stocks` comment '库存表，存储商品的库存信息'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};

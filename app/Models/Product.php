<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    /**
     * 可以批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'brand_id',
    ];


    /**
     * 获取与商品关联的分类。 一对一关系
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 获取与商品关联的品牌。一对一关系
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }


    /**
     * 获取商品的所有图片。一对多
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * 获取商品的库存信息。 一对一关联
     */
    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }




    /**
     * 范围查询：按分类ID筛选商品。
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * 范围查询：按品牌ID筛选商品。
     */
    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    /**
     * 范围查询：按价格范围筛选商品。
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * 获取商品的主图。
     */
    public function getMainImageAttribute(): Model|HasMany|null
    {
        return $this->images()->first();
    }


}

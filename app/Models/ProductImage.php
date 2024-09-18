<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'product_images';

    /**
     * 可以批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'image_url',
    ];

    /**
     * 获取与图片关联的商品。
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 获取图片的完整URL。
     *
     * @return string
     */
    public function getFullImageUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->image_url);
    }

    /**
     * 设置图片URL。
     *
     * @param string $value
     * @return void
     */
    public function setImageUrlAttribute($value)
    {
        $this->attributes['image_url'] = str_replace('public/', '', $value);
    }

}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * 用户激活令牌需要在用户模型创建之前生成
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }




    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function favoriteProducts(): BelongsToMany
    {
        //$user->favoriteProducts()->attach([2,3])
        //$user->favoriteProducts()->sync([2,3],false)
        //$user->favoriteProducts()->allRelatedIds()->toArray()
        //$user->favoriteProducts()->detach([2,3])

        return $this->belongsToMany(Product::class,'user_product')
            ->wherePivot('type', 'favorite')
            ->withTimestamps();
    }

    public function purchasedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'user_product')
            ->wherePivot('type', 'purchased')
            ->withTimestamps();
    }

    public function favorite($product_ids)
    {
        if ( ! is_array($product_ids)) {
            $product_ids= compact('product_ids');
        }
        $this->favoriteProducts()->sync($product_ids, false);

    }

    public function purchased($product_ids)
    {
        if ( ! is_array($product_ids)) {
            $product_ids= compact('product_ids');
        }
        $this->purchasedProducts()->attach($product_ids, ['type' => 'purchased']);

    }

    public function unFavorite($product_ids)
    {
        if ( ! is_array($product_ids)) {
            $product_ids= compact('product_ids');
        }
        $this->favoriteProducts()->detach($product_ids);

    }

    public function isFavorite($product_id)
    {
        return $this->favoriteProducts->contains($product_id);
    }
}

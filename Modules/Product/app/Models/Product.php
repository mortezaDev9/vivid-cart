<?php

declare(strict_types=1);

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Modules\Cart\Models\Cart;
use Modules\Cart\Models\CartProduct;
use Modules\Category\Models\Category;
use Modules\Order\Models\OrderItem;
use Modules\Product\Database\Factories\ProductFactory;
use Modules\Review\Models\Review;
use Modules\Wishlist\Models\ProductWishlist;
use Modules\Wishlist\Models\Wishlist;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product) {
            $product->sku = strtoupper(
                substr($product->category->name ?? 'GEN', 0, 3) . '-' . Str::random(4)
            );
        });

        static::created(function (Product $product) {
            $product->sku = strtoupper(substr($product->category->name ?? 'GEN', 0, 3))
                . '-' . str_pad((string) $product->id, 4, '0', STR_PAD_LEFT);
            $product->save();
        });
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): BelongsToMany
    {
        return $this->belongsToMany(Wishlist::class)
            ->using(ProductWishlist::class)
            ->withPivot('quantity');
    }

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class)
            ->using(CartProduct::class)
            ->withPivot('quantity');
    }

    public function item(): HasOne
    {
        return $this->hasOne(OrderItem::class);
    }
}

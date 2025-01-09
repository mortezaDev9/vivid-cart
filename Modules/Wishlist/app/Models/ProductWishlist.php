<?php

namespace Modules\Wishlist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Product\Models\Product;
use Modules\Wishlist\Database\Factories\ProductWishlistFactory;

class ProductWishlist extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected static function newFactory(): ProductWishlistFactory
    {
        return ProductWishlistFactory::new();
    }

    public function wishlist(): BelongsTo
    {
        return $this->belongsTo(Wishlist::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

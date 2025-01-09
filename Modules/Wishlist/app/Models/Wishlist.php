<?php

namespace Modules\Wishlist\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use Modules\Wishlist\Database\Factories\WishlistFactory;

class Wishlist extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $guarded = [];

    protected static function newFactory(): WishlistFactory
    {
        return WishlistFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(ProductWishlist::class)
            ->withPivot('quantity');
    }
}

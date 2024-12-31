<?php

namespace Modules\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Cart\Database\Factories\CartProductFactory;
use Modules\Product\Models\Product;

class CartProduct extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected static function newFactory(): CartProductFactory
    {
        return CartProductFactory::new();
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

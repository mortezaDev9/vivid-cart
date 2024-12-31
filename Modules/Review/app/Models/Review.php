<?php

declare(strict_types=1);

namespace Modules\Review\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Models\Product;
use Modules\Review\Database\Factories\ReviewFactory;
use Modules\User\Models\User;

class Review extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): ReviewFactory
    {
        return ReviewFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

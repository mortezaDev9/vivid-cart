<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

Route::group([
    'prefix'     => 'products',
    'as'         => 'products.',
    'controller' => ProductController::class,
], function () {
    Route::get('{product:slug}', 'index')->name('index');
});

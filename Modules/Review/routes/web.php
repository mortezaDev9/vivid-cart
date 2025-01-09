<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Review\Http\Controllers\ReviewController;

Route::group([
    'prefix'     => 'reviews',
    'as'         => 'reviews.',
    'middleware' => 'auth',
    'controller' => ReviewController::class,
], function () {
    Route::get('', 'index')->name('index');
    Route::post('{product}', 'store')->name('store');
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

Route::group([
    'prefix'     => 'cart',
    'as'         => 'cart.',
    'middleware' => 'auth',
    'controller' => CartController::class,
], function () {
    Route::get('', 'index')->name('index');
    Route::post('toggle/{product}', 'toggle')->name('toggle');
    Route::delete('remove/{product}', 'remove')->name('remove');
    Route::patch('update-quantity/{product}', 'updateProductQuantity')->name('update-quantity');
});

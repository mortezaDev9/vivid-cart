<?php


use Illuminate\Support\Facades\Route;
use Modules\Wishlist\Http\Controllers\WishlistController;

Route::group([
    'prefix'     => 'wishlist',
    'as'         => 'wishlist.',
    'middleware' => 'auth',
    'controller' => WishlistController::class,
], function () {
    Route::get('', 'index')->name('index');
    Route::post('toggle/{product}', 'toggle')->name('toggle');
    Route::delete('remove/{product}', 'remove')->name('remove');
});

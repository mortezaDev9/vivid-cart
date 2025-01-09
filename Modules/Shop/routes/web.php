<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\CheckoutController;
use Modules\Shop\Http\Controllers\ShopController;

Route::get('shop', ShopController::class)->name('shop');

Route::get('checkout', CheckoutController::class)->name('checkout')
    ->middleware('auth');

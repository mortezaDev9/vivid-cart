<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::group([
    'prefix'     => 'orders',
    'as'         => 'orders.',
    'middleware' => 'auth',
    'controller' => OrderController::class,
], function () {
    Route::get('', 'index')->name('index');
    Route::post('', 'store')->name('store');
    Route::get('confirmation/{order}', 'confirmationView')->name('confirmation');
    Route::get('cancellations', 'cancellations')->name('cancellations');
    Route::patch('cancel/{order}', 'cancel')->name('cancel');
});

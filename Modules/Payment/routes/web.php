<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

Route::group([
    'prefix'     => 'payments',
    'as'         => 'payments.',
    'middleware' => 'auth',
    'controller' => PaymentController::class,
], function () {
    Route::post('{order}', 'handle')->name('handle');
    Route::get('verify', 'callback')->name('callback');
});

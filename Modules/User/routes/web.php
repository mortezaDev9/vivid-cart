<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::group([
    'prefix'     => 'account',
    'as'         => 'account.',
    'middleware' => 'auth',
    'controller' => UserController::class,
], function () {
    Route::get('', 'index')->name('index');

    Route::get('edit', 'editProfile')->name('profile.edit');
    Route::put('', 'updateProfile')->name('profile.update');

    Route::get('password/change', 'changePassword')->name('password.change');
    Route::patch('password', 'updatePassword')->name('password.update');
});

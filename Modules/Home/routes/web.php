<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Home\Http\Controllers\AboutController;
use Modules\Home\Http\Controllers\ContactController;
use Modules\Home\Http\Controllers\HomeController;

Route::get('', HomeController::class)->name('home');

Route::get('about', AboutController::class)->name('about');

Route::get('contact', ContactController::class)->name('contact');

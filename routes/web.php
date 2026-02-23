<?php

use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');

Route::get('/status/{reason}', [PublicPageController::class, 'status'])
    ->name('status')
    ->whereIn('reason', ['disabled', 'not-started', 'expired', 'not-found']);

Route::get('/{code}', [PublicPageController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9_-]{1,32}');
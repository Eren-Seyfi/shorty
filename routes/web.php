<?php

use App\Http\Controllers\RedirectShortLinkController;
use App\Http\Controllers\ShortLinkStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});




Route::get('/link/status/{reason}', ShortLinkStatusController::class)
    ->where('reason', 'disabled|not-started|expired')
    ->name('status');



Route::get('/{code}', RedirectShortLinkController::class)
    ->where('code', '[a-zA-Z0-9_-]{1,32}');

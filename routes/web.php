<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('events', [EventController::class, 'store'])->name('events.store');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

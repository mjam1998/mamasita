<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('/admin')->group(function () {
    Route::get('/index', [AdminController::class, 'index'])->name('admin.index');
});

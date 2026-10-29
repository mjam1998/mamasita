<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserListController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\MamaDocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('/locations')->group(function () {
    Route::get('/provinces', [LocationController::class, 'provinces'])->name('locations.provinces');
    Route::get('/provinces/{province}/cities', [LocationController::class, 'getCitiesByProvince'])->name('locations.cities');
});


    Route::get('/mama-documents/{mamaInformation}/{type}', [MamaDocumentController::class, 'viewDocument'])
        ->name('mamas.documents.view');



Route::prefix('/admin')->group(function () {
    Route::get('/index', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/all-users', [UserListController::class, 'allUsers'])->name('admin.list.all');
    Route::get('/all-mama', [UserListController::class, 'allMama'])->name('admin.list.mama');

    Route::prefix('/users')->group(function () {
        Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/update/{user}', [UserController::class, 'update'])->name('admin.users.update');
    });
    Route::prefix('/mama')->group(function () {
        Route::get('/{user}/edit', [UserController::class, 'mamaEdit'])->name('admin.mamas.edit');
        Route::put('/{mama_information}', [UserController::class, 'mamaUpdate'])->name('admin.mamas.update');
    });
});



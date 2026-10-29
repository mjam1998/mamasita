<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPayController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserListController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\MamaDocumentController;
use App\Http\Controllers\TinyMceImageController;
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
    Route::get('/all-pending-mama', [UserListController::class, 'allPendingMama'])->name('admin.list.pending.mama');

    Route::post('/upload-image', [TinyMceImageController::class, 'uploadImage'])->name('admin.upload.image');

    Route::post('/users/search', [UserController::class, 'search'])
        ->name('admin.users.search');

    Route::prefix('/users')->group(function () {
        Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/update/{user}', [UserController::class, 'update'])->name('admin.users.update');
    });
    Route::prefix('/mama')->group(function () {
        Route::get('/{user}/edit', [UserController::class, 'mamaEdit'])->name('admin.mamas.edit');
        Route::put('/{mama_information}', [UserController::class, 'mamaUpdate'])->name('admin.mamas.update');

        Route::get('/{user}/fields', [UserController::class, 'mamaFields'])->name('admin.mamas.fields');
        Route::put('/{user}/fields', [UserController::class, 'updateMamaFields'])->name('admin.mamas.fields.update');
    });
    Route::prefix('/plans')->group(function () {
        Route::get('/index', [AdminPlanController::class, 'index'])->name('admin.plan.index');
        Route::get('/create', [AdminPlanController::class, 'create'])->name('admin.plan.create');
        Route::post('/store', [AdminPlanController::class, 'store'])->name('admin.plan.store');
        Route::get('/{plan}/edit', [AdminPlanController::class, 'edit'])->name('admin.plan.edit');
        Route::put('/update/{plan}', [AdminPlanController::class, 'update'])->name('admin.plan.update');
    });

    Route::prefix('/pays')->group(function () {
        Route::get('/index', [AdminPayController::class, 'index'])->name('admin.pay.index');
        Route::get('/create', [AdminPayController::class, 'create'])->name('admin.pay.create');
        Route::post('/store', [AdminPayController::class, 'store'])->name('admin.pay.store');
        Route::get('/{plan}/edit', [AdminPayController::class, 'edit'])->name('admin.pay.edit');
        Route::put('/update/{plan}', [AdminPayController::class, 'update'])->name('admin.pay.update');
    });

});

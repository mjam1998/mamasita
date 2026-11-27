<?php

use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminCallController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminCourseOrderController;
use App\Http\Controllers\Admin\AdminDoulaSubscriptionController;
use App\Http\Controllers\Admin\AdminExtraPageController;
use App\Http\Controllers\Admin\AdminHomeSectionController;
use App\Http\Controllers\Admin\AdminMonthlySubscriptionController;
use App\Http\Controllers\Admin\AdminOnlineVisitController;
use App\Http\Controllers\Admin\AdminPayController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\AdminUserPayController;
use App\Http\Controllers\Admin\AdminVideoController;
use App\Http\Controllers\Admin\AdminVideoStreamController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserListController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\MamaDocumentController;
use App\Http\Controllers\TicketAttachmentController;
use App\Http\Controllers\TinyMceImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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
        Route::get('/show/{admin_pay}', [AdminPayController::class, 'show'])->name('admin.pay.show');
        Route::get('pays/{admin_pay}/status', [AdminPayController::class, 'editStatus'])->name('admin.pay.status.edit');
        Route::put('pays/{admin_pay}/status', [AdminPayController::class, 'updateStatus'])->name('admin.pay.status.update');

        Route::get('/search/mamas', [AdminPayController::class, 'searchMamas'])->name('admin.pay.search.mamas');
        Route::get('/search/user-pays', [AdminPayController::class, 'searchUserPays'])->name('admin.pay.search.user-pays');
    });

    Route::prefix('/wallets')->group(function () {
        Route::get('/user/{user}', [AdminWalletController::class, 'showUserWallet'])->name('admin.wallets.user');
        Route::get('/transactions/{transaction}', [AdminWalletController::class, 'showTransaction'])->name('admin.wallets.transactions.show');
        Route::get('/user/{user}/transactions/create', [AdminWalletController::class, 'createTransaction'])->name('admin.wallets.transactions.create');
        Route::post('/user/{user}/transactions', [AdminWalletController::class, 'storeTransaction'])->name('admin.wallets.transactions.store');
    });

    Route::prefix('/user-pays')->group(function () {
        Route::get('/index', [AdminUserPayController::class, 'index'])->name('admin.user-pays.index');
        Route::get('/show/{user_pay}', [AdminUserPayController::class, 'show'])->name('admin.user-pays.show');
    });

    Route::prefix('/tickets')->group(function () {
        Route::get('/index', [AdminTicketController::class, 'index'])->name('admin.ticket.index');
        Route::get('/{ticket}', [AdminTicketController::class, 'show'])->name('admin.ticket.show');
        Route::post('/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('admin.ticket.reply');
        Route::patch('/{ticket}/status', [AdminTicketController::class, 'toggleStatus'])
            ->name('admin.ticket.status');

        // روت دانلود فایل
        Route::get('/{ticket}/attachment/{filename}', [TicketAttachmentController::class, 'viewAttachment'])
            ->name('admin.ticket.attachment');
    });

    Route::prefix('/online-visits')->group(function () {
        Route::get('/index', [AdminOnlineVisitController::class, 'index'])->name('admin.online-visit.index');
        Route::get('/show/{online_visit}', [AdminOnlineVisitController::class, 'show'])->name('admin.online-visit.show');
    });

    Route::prefix('/doula-subscription')->group(function () {
        Route::get('/index', [AdminDoulaSubscriptionController::class, 'index'])->name('admin.doula-subscription.index');
        Route::get('/{doula_subscription}', [AdminDoulaSubscriptionController::class, 'show'])->name('admin.doula-subscription.show');
    });

    Route::prefix('/videos')->group(function () {
        Route::get('/index', [AdminVideoController::class, 'index'])->name('admin.videos.index');
        Route::get('/create', [AdminVideoController::class, 'create'])->name('admin.videos.create');
        Route::post('/store', [AdminVideoController::class, 'store'])->name('admin.videos.store');
        Route::get('/{video}/edit', [AdminVideoController::class, 'edit'])->name('admin.videos.edit');
        Route::put('/{video}/update', [AdminVideoController::class, 'update'])->name('admin.videos.update');

    });

    Route::get('/videos/{video}/stream', [AdminVideoStreamController::class, 'stream'])
        ->name('admin.videos.stream');

    Route::prefix('/courses')->group(function () {
        Route::get('/index', [AdminCourseController::class, 'index'])->name('admin.courses.index');
        Route::post('/search', [AdminCourseController::class, 'search'])->name('admin.courses.search');
        Route::get('/create', [AdminCourseController::class, 'create'])->name('admin.courses.create');
        Route::post('/store', [AdminCourseController::class, 'store'])->name('admin.courses.store');
        Route::get('/{course}/edit', [AdminCourseController::class, 'edit'])->name('admin.courses.edit');
        Route::put('/{course}/update', [AdminCourseController::class, 'update'])->name('admin.courses.update');
    });

    Route::prefix('/monthly-subscriptions')->group(function () {
        Route::get('/index', [AdminMonthlySubscriptionController::class, 'index'])->name('admin.monthly-subscriptions.index');
        Route::get('/show/{monthly_subscription}', [AdminMonthlySubscriptionController::class, 'show'])->name('admin.monthly-subscriptions.show');
    });

    Route::prefix('/calls')->group(function () {
        Route::get('/index', [AdminCallController::class, 'index'])->name('admin.calls.index');
        Route::get('/show/{call}', [AdminCallController::class, 'show'])->name('admin.calls.show');

        Route::get('/by-subscription/{track_number}', [AdminCallController::class, 'bySubscription'])
            ->name('admin.calls.by-subscription');
    });

    Route::prefix('/course-orders')->group(function () {
        Route::get('/index', [AdminCourseOrderController::class, 'index'])->name('admin.course-orders.index');
        Route::get('/show/{course_order}', [AdminCourseOrderController::class, 'show'])->name('admin.course-orders.show');
    });

    Route::prefix('/blogs')->group(function () {
        Route::get('/index', [AdminBlogController::class, 'index'])->name('admin.blogs.index');
        Route::get('/create', [AdminBlogController::class, 'create'])->name('admin.blogs.create');
        Route::post('/store', [AdminBlogController::class, 'store'])->name('admin.blogs.store');
        Route::get('/edit/{blog}', [AdminBlogController::class, 'edit'])->name('admin.blogs.edit');
        Route::put('/update/{blog}', [AdminBlogController::class, 'update'])->name('admin.blogs.update');
    });

    Route::prefix('/extra-pages')->group(function () {
        Route::get('/index', [AdminExtraPageController::class, 'index'])->name('admin.extra-pages.index');
        Route::get('/create', [AdminExtraPageController::class, 'create'])->name('admin.extra-pages.create');
        Route::post('/store', [AdminExtraPageController::class, 'store'])->name('admin.extra-pages.store');
        Route::get('/edit/{page}', [AdminExtraPageController::class, 'edit'])->name('admin.extra-pages.edit');
        Route::put('/update/{page}', [AdminExtraPageController::class, 'update'])->name('admin.extra-pages.update');
    });

    Route::prefix('/home-section')->group(function () {
        Route::get('/edit', [AdminHomeSectionController::class, 'edit'])->name('admin.home-section.edit');
        Route::put('/update', [AdminHomeSectionController::class, 'update'])->name('admin.home-section.update');
    });

});

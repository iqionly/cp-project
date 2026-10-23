<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MasterData\MemberController;
use App\Http\Controllers\MasterData\UserController;
use App\Http\Controllers\Members\ContributionController;
use App\Http\Controllers\Members\InvoiceController;
use App\Http\Controllers\Members\ProfileController;
use App\Http\Controllers\MenuSettingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Publics\EventController as PublicsEventController;
use App\Http\Controllers\Publics\HomeController;
use App\Http\Controllers\Publics\PostController as PublicsPostController;
use App\Http\Controllers\Securities\AuthenticationController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home_page'])->name('home_page');

Route::get('/events', [PublicsEventController::class, 'index'])->name('public.events.index');
Route::get('/events/{event}', [PublicsEventController::class, 'show'])->name('public.events.show');

Route::middleware(['auth'])->prefix('/{context?}')->group(function (Router $router) {
    $router->get('/home', [DashboardController::class, 'home'])->name('home');
    $router->get('/logout', [AuthenticationController::class, 'deauthentication'])->name('auth.logout');

    Route::match(['get', 'put', 'post'], '/profile', [ProfileController::class, 'index'])->name('member.profile')->middleware('role:member,admin');

    Route::match(['get', 'post'], '/contrib', [ContributionController::class, 'index'])->name('contrib.index')->middleware('role:member,admin');
    Route::match(['get', 'post'], '/contrib/create/{member?}', [ContributionController::class, 'create'])->name('contrib.create')->middleware('role:member,admin');
    Route::match(['get', 'put', 'patch'], '/contrib/{mp}/edit', [ContributionController::class, 'edit'])->name('contrib.edit')->middleware('role:member,admin');
    Route::put('/contrib/{mp}', [ContributionController::class, 'update'])->name('contrib.update')->middleware('role:admin');
    Route::delete('/contrib/{mp}', [ContributionController::class, 'delete'])->name('contrib.delete')->middleware('role:admin');

    Route::prefix('contrib/{mp}/items')->group(function () {
        Route::post('/', [ContributionController::class, 'storeItem'])->name('contrib.items.store')->middleware('role:member,admin');
        Route::put('/{item}', [ContributionController::class, 'updateItem'])->name('contrib.items.update')->middleware('role:member,admin');
        Route::delete('/{item}', [ContributionController::class, 'destroyItem'])->name('contrib.items.destroy')->middleware('role:member,admin');
    });

    Route::prefix('payments')->group(function () {
        Route::put('/{payment}', [ContributionController::class, 'updatePayment'])->name('contrib.payments.update')->middleware('role:member,admin');
        Route::delete('/{payment}', [ContributionController::class, 'destroyPayment'])->name('contrib.payments.destroy')->middleware('role:member,admin');
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/contrib/admin/{payment}/status/{status?}', [ContributionController::class, 'adminReview'])->name('contrib.admin.review');
    });

    Route::middleware('role:admin')->group(function () {
        Route::prefix('/master-data')->group(function (Router $router) {
            $router->get('/member', [MemberController::class, 'index'])->name('master-data.member.list');
            $router->match(['get', 'post'], '/member/create', [MemberController::class, 'create'])->name('master-data.member.create');
            $router->match(['get', 'put'], '/member/{member}/edit', [MemberController::class, 'edit'])->name('master-data.member.edit');
            $router->delete('/member/{member}', [MemberController::class, 'delete'])->name('master-data.member.delete');

            $router->get('/user', [UserController::class, 'index'])->name('master-data.user.list');
            $router->match(['get', 'put'], '/user/{user}/edit', [UserController::class, 'edit'])->name('master-data.user.edit');
            $router->delete('/user/{user}', [UserController::class, 'delete'])->name('master-data.user.delete');
        });

        Route::prefix('/menu-setting')->group(function (Router $router) {
            $router->get('/', [MenuSettingController::class, 'index'])->name('menu-setting.index');
            $router->match(['get', 'post'], '/create', [MenuSettingController::class, 'create'])->name('menu-setting.create');
            $router->match(['get', 'put'], '/{menu}/edit', [MenuSettingController::class, 'edit'])->name('menu-setting.edit');
            $router->delete('/{menu}', [MenuSettingController::class, 'delete'])->name('menu-setting.delete');
        });

        Route::prefix('/post')->group(function (Router $router) {
            $router->get('/', [PostController::class, 'index'])->name('post.index');
            $router->match(['get', 'post'], '/create', [PostController::class, 'create'])->name('post.create');
            $router->match(['get', 'put'], '/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
            $router->delete('/{post}', [PostController::class, 'delete'])->name('post.delete');
        });

        Route::prefix('/events')->group(function (Router $router) {
            $router->get('/', [EventController::class, 'index'])->name('event.index');
            $router->match(['get', 'post'], '/create', [EventController::class, 'create'])->name('event.create');
            $router->match(['get', 'put'], '/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
            $router->delete('/{event}', [EventController::class, 'delete'])->name('event.delete');
        });

        Route::prefix('/invoices')->group(function (Router $router) {
            $router->get('/generate', [InvoiceController::class, 'generate'])->name('invoice.generate');
            $router->post('/generate', [InvoiceController::class, 'generate'])->name('invoice.generate');
        });
    });

});

Route::get('/login', [AuthenticationController::class, 'login_page'])->name('auth.login');
Route::post('/login', [AuthenticationController::class, 'authentication']);
Route::get('/{menu:slug}', [PublicsPostController::class, 'page'])->name('public.posts');

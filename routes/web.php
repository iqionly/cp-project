<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterData\MemberController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Publics\HomeController;
use App\Http\Controllers\Securities\AuthenticationController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function (Router $router) {
    $router->get('/login', [AuthenticationController::class, 'login_page'])->name('auth.login');
    $router->post('/login', [AuthenticationController::class, 'authentication']);
});

Route::get('/', [HomeController::class, 'home_page'])->name('home_page');

Route::middleware(['auth'])->group(function (Router $router) {
    $router->get('/', [DashboardController::class, 'home']);
    $router->get('/logout', [AuthenticationController::class, 'deauthentication'])->name('auth.logout');

    Route::middleware('role:admin')->group(function () {
        Route::prefix('/master-data')->group(function (Router $router) {
            $router->get('/member', [MemberController::class, 'index'])->name('master-data.member.list');
            $router->match(['get', 'put'], '/member/{member}/edit', [MemberController::class, 'edit'])->name('master-data.member.edit');
            $router->delete('/member/{member}', [MemberController::class, 'delete'])->name('master-data.member.delete');
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
    });
});

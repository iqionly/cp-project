<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $req = $this->app->get('request');
        $rawPath = explode('/', $req->path());
        $paths = array_map(function($path) {
            return Str::title(str_replace('-', ' ', $path));
        }, $rawPath);

        View::share('page_title', array_last($paths));
        View::share('page_breadcumb', $paths);
        View::share('old', $req->old());

        unset($req, $rawPath, $paths);
    }
}

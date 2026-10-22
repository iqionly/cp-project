<?php

namespace App\Providers;

use App\Models\User;
use App\Services\FeeService;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(FeeService::class, function(){
            return new FeeService();
        });

        $this->app->singleton(PaymentService::class, function(Application $app){
            return new PaymentService($app->get(FeeService::class));
        });

        $this->app->singleton(InvoiceService::class, function(Application $app){
            return new InvoiceService($app->get(FeeService::class));
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor/pagination/bootstrap-5');
        Paginator::defaultSimpleView('vendor/pagination/simple-bootstrap-5');

        $req = $this->app->get('request');
        $rawPath = explode('/', $req->path());
        $paths = array_map(function($path) {
            return Str::title(str_replace('-', ' ', $path));
        }, $rawPath);

        View::share('page_title', array_last($paths));
        View::share('page_breadcumb', $paths);
        View::share('old', $req->old());

        unset($req, $rawPath, $paths);

        Gate::define('role', function (User $user, ...$roles) {
            $context = request()->attributes->get('context');
            if($user->roles()->where('role_grouping', $context)->count() === 0) { // user no longer have access
                return false;
            }
            return in_array($context, $roles) ? true : false;
        });

        Request::macro('context', function() {
            $user = $this->user();
            $context = $this->attributes->get('context');
            $object = $user->roles()->where('role_grouping', $context)->first();
            if($object->count() === 0) { // user no longer have access
                return false;
            }
            return $object;
        });

    }

}

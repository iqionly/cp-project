<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ChangeRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $context = $contextBefore = $request->context;
        $route = $request->route();
        if ($request->query->has('cr')) {
            $context = $request->user()->roles->where('id', $request->query->get('cr'))->first()->name;
            URL::defaults(['context' => $context]);

            return redirect(url(preg_replace("/^$contextBefore/", $context, $request->path())));
        }

        URL::defaults(['context' => $context]);

        if ($route && $route->parameter('context') !== null) {
            $request->attributes->set('context', $context);
            $route->forgetParameter('context');
        }

        return $next($request);
    }
}

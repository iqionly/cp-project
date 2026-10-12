<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role_grouping): Response
    {
        if($request->user()->roles()->where('role_grouping', $role_grouping)->count() == 0) {
            return redirect()->back();
        }
        return $next($request);
    }
}

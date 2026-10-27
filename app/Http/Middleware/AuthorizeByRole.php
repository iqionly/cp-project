<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $rawPath = explode('/', $request->path());
        $subcontext = explode(':', $rawPath[0]);
        if (in_array($subcontext[0], $roles)) {
            return $next($request);
        }

        return redirect()
            ->back()
            ->withErrors([
                'auth' => 'You don\'t have permission to access the menu',
            ]);
    }
}

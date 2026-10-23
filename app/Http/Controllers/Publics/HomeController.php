<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use Laravel\Mcp\Request;

class HomeController extends Controller
{
    public function home_page(Request $request)
    {
        if ($request->user()) {
            $context = $request->user()->roles->first();

            return redirect()->route('home', ['context' => $context->role_grouping]);
        }

        return view('pages.publics.homepage');
    }
}

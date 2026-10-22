<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        if($request->user() && $request->attributes->get('context') == null) {
            $context = $request->user()->roles->first();
            return redirect()->route('home', ['context' => $context->role_grouping]);
        }

        return view('pages.homepage');
    }
}

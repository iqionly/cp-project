<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use Laravel\Mcp\Request;

class HomeController extends Controller
{
    public function home_page(Request $request)
    {
        return view('public.homepage');
    }
}

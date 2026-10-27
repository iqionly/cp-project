<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function setting(Request $request)
    {
        return view('pages.systems.settings');
    }
}

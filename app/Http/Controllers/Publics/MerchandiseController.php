<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    /**
     * Display all available merchandise to the public.
     */
    public function index(Request $request): View
    {
        return view('pages.publics.merchandise', [
            'data' => Merchandise::available()
                ->orderBy('name')
                ->paginate(12),
        ]);
    }
}

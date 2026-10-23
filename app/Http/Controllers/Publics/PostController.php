<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use App\Models\MenuPost;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function page(Request $request, MenuPost $menu)
    {
        $menu->load(['post', 'post.user']);

        return view('pages.publics.post', [
            'post' => $menu->post,
            'author' => $menu->post->getRelation('user'),
            'menu' => $menu,
        ]);
    }
}

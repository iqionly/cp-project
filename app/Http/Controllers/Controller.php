<?php

namespace App\Http\Controllers;

use App\Models\MenuPost;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

abstract class Controller
{
    protected function publicMenuPosts()
    {
        return MenuPost::select('post_id', 'slug', 'menu_title')
            ->whereHas('post', function(Builder $query) {
                $query->whereNot('published_at', null);
            })
            ->get();
    }
}

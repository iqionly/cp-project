<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\MenuPost;
use App\Models\Merchandise;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the public homepage.
     */
    public function home_page(Request $request): View
    {
        // 1. Sejarah Singkat (Profile / Sejarah Club)
        $profileMenu = rescue(fn () => MenuPost::whereIn('slug', ['profile', 'sejarah-club', 'sejarah'])->first(), null, false);
        $profilePost = $profileMenu?->post;

        if (! $profilePost) {
            $profilePost = rescue(fn () => Post::query()
                ->where(function ($query) {
                    $query->where('title', 'like', '%Profile%')
                        ->orWhere('title', 'like', '%Sejarah%');
                })
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->first(), null, false);
        }

        // 2. Struktur Pengurus
        $strukturPost = rescue(fn () => Post::query()
            ->where(function ($query) {
                $query->where('title', 'like', '%Struktur%')
                    ->orWhere('description', 'like', '%kepengurusan%')
                    ->orWhere('path_featured_image', 'like', '%struktur%');
            })
            ->whereNotNull('published_at')
            ->first(), null, false);

        // 3. Dokumentasi (4-6 foto terbaru)
        $galleries = rescue(fn () => Gallery::query()
            ->with(['event.post'])
            ->latest('taken_at')
            ->latest('created_at')
            ->take(6)
            ->get(), collect());

        // 4. Merchandise Preview (4 merchandise tersedia)
        $merchandises = rescue(fn () => Merchandise::available()
            ->latest('id')
            ->take(4)
            ->get(), collect());

        return view('pages.publics.homepage', [
            'profilePost' => $profilePost,
            'profileMenu' => $profileMenu,
            'profileUrl' => $profileMenu ? route('public.posts', [$profileMenu->slug]) : url('/profile'),
            'strukturPost' => $strukturPost,
            'galleries' => $galleries,
            'merchandises' => $merchandises,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display public activity documentation / gallery.
     */
    public function index(Request $request): View
    {
        $query = Gallery::query()->with(['event.post']);

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->get('event_id'));
        }

        if ($request->filled('filter')) {
            $filter = $request->get('filter');
            $query->where(function (Builder $q) use ($filter) {
                $q->where('title', 'like', "%{$filter}%")
                    ->orWhere('description', 'like', "%{$filter}%")
                    ->orWhere('tags', 'like', "%{$filter}%")
                    ->orWhereHas('event.post', function (Builder $eq) use ($filter) {
                        $eq->where('title', 'like', "%{$filter}%")
                            ->orWhere('description', 'like', "%{$filter}%");
                    });
            });
        }

        $galleries = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('pages.publics.gallery', [
            'data' => $galleries,
            'page_title' => 'Dokumentasi Kegiatan Club',
            'page_breadcumb' => ['Dokumentasi Kegiatan'],
        ]);
    }
}

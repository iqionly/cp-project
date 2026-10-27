<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    function index(Request $request)
    {
        return view('pages.systems.gallery_index', [
            'data' => Gallery::query()
                ->when($request->has('filter'), function(Builder $query) use ($request) {
                    $query->where('title', 'like', $request->get('filter') .'%');
                    $query->orWhere('tags', 'like', '%' . $request->get('filter') .'%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(
                    $request->input('limit') ?? 9,
                    ['*'],
                    'page',
                    (int) $request->input('page', 1),
                ),
        ]);
    }

    function create(Request $request)
    {

    }

    function edit(Request $request, Gallery $gallery)
    {

    }

    function destroy(Request $request, Gallery $gallery)
    {
        Storage::disk('public')->delete($gallery->getRawOriginal('path_image'));
        $gallery->delete();

        return back()->with('success', 'Success delete the images.');
    }
}

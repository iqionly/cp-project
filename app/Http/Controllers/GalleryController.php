<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Gallery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display a listing of gallery images.
     */
    public function index(Request $request): View
    {
        return view('pages.systems.gallery_index', [
            'data' => Gallery::query()
                ->with(['event.post'])
                ->when($request->filled('filter'), function (Builder $query) use ($request) {
                    $query->where('title', 'like', $request->get('filter').'%')
                        ->orWhere('tags', 'like', '%'.$request->get('filter').'%');
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

    /**
     * Show the form for creating/uploading new gallery image.
     */
    public function create(): View
    {
        $events = Event::with('post')->latest()->get();

        return view('pages.systems.gallery_create', [
            'events' => $events,
        ]);
    }

    /**
     * Store newly uploaded gallery image.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_id' => ['nullable', 'exists:events,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'path_image' => ['required', 'image', 'max:5120'], // Max 5MB
            'taken_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'string'],
        ]);

        $path = $request->file('path_image')->storePublicly('galleries', 'public');

        $tags = [];
        if (! empty($validated['tags'])) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $validated['tags']))));
        }

        Gallery::create([
            'event_id' => $validated['event_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'path_image' => $path,
            'taken_at' => $validated['taken_at'] ?? null,
            'tags' => $tags,
        ]);

        return redirect()->route('gallery.index')
            ->with('success', 'Foto dokumentasi kegiatan berhasil diunggah.');
    }

    /**
     * Delete a gallery image.
     */
    public function destroy(Request $request, Gallery $gallery): RedirectResponse
    {
        if ($gallery->getRawOriginal('path_image')) {
            Storage::disk('public')->delete($gallery->getRawOriginal('path_image'));
        }
        $gallery->delete();

        return back()->with('success', 'Foto dokumentasi berhasil dihapus.');
    }
}

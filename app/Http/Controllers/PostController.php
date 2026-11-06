<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.posts.index', [
            'data' => Post::with('user')
                ->when($request->has('filter'), function (Builder $query) use ($request) {
                    $query->where('title', 'like', $request->get('filter').'%')
                        ->where('description', 'like', '%'.$request->get('filter').'%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(
                    $request->input('limit'),
                    ['*'],
                    'page',
                    (int) $request->input('page', 1),
                ),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'title' => ['required', 'string'],
                'description' => ['required', 'string'],
                'contents' => ['string', 'nullable', 'min:50'],
                'path_featured_image' => ['image'],
            ]);

            $data = $request->except([
                '_token',
                '_method',
                'images',
                'path_featured_image',
                'is_menu',
            ]);

            $post = new Post;

            $post->user()->associate($request->user());

            $uploadedFiles = [];
            $newGalleryPaths = [];

            foreach ($request->file() as $key => $file) {
                if (is_array($file)) {
                    foreach ($file as $subkey => $subfile) {
                        if ($subfile) {
                            $path = $subfile->storePublicly('images', 'public');
                            $uploadedFiles['path_images'][$subkey] = $path;
                            $newGalleryPaths[] = $path;
                        }
                    }

                    continue;
                }
                if ($file) {
                    $path = $file->storePublicly('images', 'public');
                    $uploadedFiles[$key] = $path;
                    $newGalleryPaths[] = $path;
                }
            }

            $post->review($request->has('reviewed'));
            $post->publish($request->has('published'));

            $post->fill(array_merge($data, $uploadedFiles));

            $post->save();

            // Masukkan file yang diupload ke tabel galleries dengan tag "document"
            foreach ($newGalleryPaths as $galleryPath) {
                Gallery::firstOrCreate(
                    ['path_image' => $galleryPath],
                    [
                        'event_id' => null,
                        'title' => $post->title ?? 'Dokumentasi Post',
                        'description' => $post->description,
                        'tags' => ['document'],
                        'taken_at' => now()->toDateString(),
                    ]
                );
            }

            return redirect()->route('post.edit', [
                'post' => $post->id,
            ]);
        }

        return view('pages.posts.edit', [
            'data' => new Post,
            'method' => $request->routeIs('post.create') ? 'post' : 'put',
        ]);
    }

    public function edit(Request $request, Post $post)
    {
        if ($request->isMethod('put')) {
            $request->validate([
                'title' => ['required', 'string'],
                'description' => ['required', 'string'],
                'contents' => ['string', 'nullable', 'min:50'],
                'path_featured_image' => ['image'],
            ]);

            $data = $request->except([
                '_token',
                '_method',
                'images',
                'path_featured_image',
                'is_menu',
            ]);

            $uploadedFiles = [];
            $newGalleryPaths = [];

            foreach ($request->file() as $key => $file) {
                if (is_array($file)) {
                    $subFileUploads = $post->path_images ?? [];
                    foreach ($file as $subkey => $subfile) {
                        if ($subfile) {
                            $path = $subfile->storePublicly('images', 'public');
                            $subFileUploads[$subkey] = $path;
                            $newGalleryPaths[] = $path;
                        }
                    }
                    $uploadedFiles['path_images'] = $subFileUploads;

                    continue;
                }
                if ($file) {
                    $path = $file->storePublicly('images', 'public');
                    $uploadedFiles[$key] = $path;
                    $newGalleryPaths[] = $path;
                }
            }

            $post->review($request->has('reviewed'));
            $post->publish($request->has('published'));

            $post->fill(array_merge($data, $uploadedFiles));

            $post->save();

            // Masukkan file yang diupload ke tabel galleries dengan tag "document"
            foreach ($newGalleryPaths as $galleryPath) {
                Gallery::firstOrCreate(
                    ['path_image' => $galleryPath],
                    [
                        'event_id' => null,
                        'title' => $post->title ?? 'Dokumentasi Post',
                        'description' => $post->description,
                        'tags' => ['document'],
                        'taken_at' => now()->toDateString(),
                    ]
                );
            }

            if ($request->has('is_menu')) {
                $post->menu_post()->updateOrCreate([
                    'post_id' => $post->id,
                ], [
                    'default' => DB::table('menu_posts')->max('default') + 100,
                    'menu_title' => $post->title,
                ]);
            }
        }

        return view('pages.posts.edit', [
            'data' => $post,
            'method' => $request->routeIs('post.create') ? 'post' : 'put',
        ]);
    }

    public function delete(Request $request, Post $post)
    {
        foreach ($post->path_images ?? [] as $img) {
            Storage::disk('public')->delete($img);
        }
        if ($post->getRawOriginal('path_featured_image')) {
            Storage::disk('public')->delete($post->getRawOriginal('path_featured_image'));
        }
        if ($post->delete()) {
            return redirect()->back();
        }
    }
}

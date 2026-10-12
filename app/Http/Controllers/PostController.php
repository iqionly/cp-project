<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.posts.index', [
            'data' => Post::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(
                $request->input('limit'),
                ['*'],
                'page',
                (int) $request->input('page', 1),
            )
        ]);
    }

    public function create(Request $request)
    {
        if($request->isMethod('post')) {
            $request->validate([
                'title' => ['required', 'string'],
                'description' => ['required', 'string'],
                'contents' => ['string', 'nullable'],
                'path_featured_image' => ['image'],
            ]);

            $data = $request->except([
                '_token',
                '_method',
                'images',
                'path_featured_image',
            ]);

            $post = new Post();

            $post->user()->associate($request->user());

            $uploadedFiles = [];
            foreach($request->file() as $key => $file) {
                if(is_array($file)) {
                    foreach($file as $subkey => $subfile) {
                        $uploadedFiles['path_images'][$subkey] = $subfile->storePublicly('images', 'public');
                    }
                    continue;
                }
                $uploadedFiles[$key] = $file->storePublicly('images', 'public');
            }

            $post->review($request->has('reviewed'));
            $post->publish($request->has('published'));

            $post->fill(array_merge($data, $uploadedFiles));

            $post->save();

            return redirect()->route('post.edit', [
                'post' => $post->id,
            ]);
        }

        return view('pages.posts.edit', [
            'data' => new Post(),
            'method' => $request->routeIs('post.create') ? 'post' : 'put',
        ]);
    }

    public function edit(Request $request, Post $post)
    {
        if($request->isMethod('put')) {
            $request->validate([
                'title' => ['required', 'string'],
                'description' => ['required', 'string'],
                'contents' => ['string'],
                'path_featured_image' => ['image'],
            ]);

            $data = $request->except([
                '_token',
                '_method',
                'images',
                'path_featured_image',
            ]);

            $uploadedFiles = [];
            foreach($request->file() as $key => $file) {
                if(is_array($file)) {
                    $subFileUploads = $post->path_images;
                    foreach($file as $subkey => $subfile) {
                        $subFileUploads[$subkey] = $subfile->storePublicly('images', 'public');
                    }
                    $uploadedFiles['path_images'] = $subFileUploads;
                    continue;
                }
                $uploadedFiles[$key] = $file->storePublicly('images', 'public');
            }

            $post->review($request->has('reviewed'));
            $post->publish($request->has('published'));

            $post->fill(array_merge($data, $uploadedFiles));

            $post->save();
        }

        return view('pages.posts.edit', [
            'data' => $post,
            'method' => $request->routeIs('post.create') ? 'post' : 'put',
        ]);
    }

    public function delete(Request $request, Post $post)
    {
        if($post->delete()) {
            return redirect()->back();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.posts.index', [
            'data' => Post::with('user')->paginate(
                $request->input('limit'),
                ['*'],
                'page',
                (int) $request->input('page', 1),
            )
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
        }

        return view('pages.posts.edit', [
            'data' => $post,
        ]);
    }

    public function delete(Request $request, Post $post)
    {
        if($post->delete()) {
            return redirect()->back();
        }
    }
}

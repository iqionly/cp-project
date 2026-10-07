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
            $data = $request->except([
                '_token',
                '_method',
                'member_code',
            ]);

            $post->fill($data);
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

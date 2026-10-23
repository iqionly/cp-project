<?php

namespace App\Http\Controllers;

use App\Models\MenuPost;
use Illuminate\Http\Request;

class MenuSettingController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.posts.menu_index', [
            'data' => MenuPost::with('post')
                ->orderBy('default', 'asc')
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
            ]);

            $data = $request->except([
                '_token',
                '_method',
            ]);

            return redirect()->route('menu-setting.edit', []);
        }

        return view('pages.posts.menu_edit', [
            'data' => new MenuPost,
            'method' => $request->routeIs('menu-setting.create') ? 'post' : 'put',
        ]);
    }

    public function edit(Request $request, MenuPost $menu)
    {
        $menu->load(['post.user']);

        if ($request->isMethod('put')) {
            $request->validate([
                'menu_title' => ['required', 'string'],
                'slug' => ['required', 'string'],
                'order' => ['required', 'integer'],
                'icons' => ['nullable', 'string'],
            ]);

            $data = $request->except([
                '_token',
                '_method',
            ]);

            $data['default'] = $data['order'];
            unset($data['order']);
            $menu->fill($data);

            $menu->save();
        }

        return view('pages.posts.menu_edit', [
            'data' => $menu,
            'selectedPost' => $menu->post,
            'posts' => [],
            'method' => $request->routeIs('menu-setting.create') ? 'post' : 'put',
            'page_title' => 'Menu Setting',
        ]);
    }

    public function delete(Request $request, MenuPost $menu)
    {
        if ($menu->delete()) {
            return redirect()->back();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Post;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.events.index', [
            'data' => Event::with('post')->paginate(
                $request->input('limit'),
                ['*'],
                'page',
                (int) $request->input('page', 1),
            )
        ]);
    }

    public function create(Request $request)
    {
        $event = new Event();
        $post = new Post();

        $event->post()->associate($post);

        if($request->isMethod('post')) {
            $request->validate([
                'post.title' => ['required', 'string'],
                'post.description' => ['required', 'string'],
                'post.contents' => ['string'],
                'post.path_featured_image' => ['image'],
                'event_period' => ['required', 'string'],
                'max_attendant' => ['required', 'integer']
            ]);

            $except = [
                '_token',
                '_method',
            ];

            $dataPost = $request->except(array_merge($except, ['post.images', 'post.path_featured_image',]))['post'] ?? [];

            $dataEvent = $request->except(array_merge($except, ['post']));

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

            $post->review($request->has('post.reviewed'));
            $post->publish($request->has('post.published'));

            $post->fill(array_merge($dataPost, $uploadedFiles));

            if(!empty($dataEvent['event_period'])) {
                $expl = explode(' to ', $dataEvent['event_period']);
                $event->start_at = $expl[0] . ':00';
                $event->end_at = $expl[1] . ':00';
            }
            $event->fill($dataEvent);

            $post->save();

            $event->post()->associate($post);

            $event->save();

            return redirect()->route('event.edit', [
                'event' => $event->id,
            ]);
        }

        return view('pages.events.edit', [
            'data' => $event,
            'method' => $request->routeIs('event.create') ? 'post' : 'put',
        ]);
    }

    public function edit(Request $request, Event $event)
    {
        if($request->isMethod('put')) {
            $request->validate([
                'post.title' => ['required', 'string'],
                'post.description' => ['required', 'string'],
                'post.contents' => ['string'],
                'post.path_featured_image' => ['image'],
                'event_period' => ['required', 'string'],
                'max_attendant' => ['required', 'integer']
            ]);

            $except = [
                '_token',
                '_method',
            ];

            $dataPost = $request->except(array_merge($except, ['post.images', 'post.path_featured_image',]))['post'] ?? [];

            $dataEvent = $request->except(array_merge($except, ['post']));

            $post = &$event->post;

            $uploadedFiles = [];

            foreach($request->file('post') as $key => $file) {
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


            $post->review($request->has('post.reviewed'));
            $post->publish($request->has('post.published'));

            $post->fill(array_merge($dataPost, $uploadedFiles));

            if(!empty($dataEvent['event_period'])) {
                $expl = explode(' to ', $dataEvent['event_period']);
                $event->start_at = $expl[0] . ':00';
                $event->end_at = $expl[1] . ':00';
            }
            $event->fill($dataEvent);

            $post->save();
            $event->save();
        }

        return view('pages.events.edit', [
            'data' => $event,
            'method' => $request->routeIs('event.create') ? 'post' : 'put',
        ]);
    }


    public function delete(Request $request, Event $event)
    {
        if($event->delete()) {
            return redirect()->back();
        }
    }
}

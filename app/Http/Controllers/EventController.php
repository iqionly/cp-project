<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Gallery;
use App\Models\Post;
use DateTime;
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
            ),
        ]);
    }

    public function create(Request $request)
    {
        $event = new Event;
        $post = new Post;

        $event->post()->associate($post);

        if ($request->isMethod('post')) {
            $request->validate([
                'post.title' => ['required', 'string'],
                'post.description' => ['required', 'string'],
                'post.contents' => ['string'],
                'post.path_featured_image' => ['image'],
                'event_period' => ['required', 'string'],
                'max_attendant' => ['required', 'integer', 'min:1'],
                'location' => ['required'],
            ]);

            $except = [
                '_token',
                '_method',
            ];

            $dataPost = $request->except(array_merge($except, ['post.images', 'post.path_featured_image']))['post'] ?? [];

            $dataEvent = $request->except(array_merge($except, ['post']));

            $post->user()->associate($request->user());

            $uploadedFiles = [];
            $newGalleryPaths = [];

            $filesToProcess = $request->file('post') ?? $request->file() ?? [];
            foreach ($filesToProcess as $key => $file) {
                if ($key === 'post' && is_array($file)) {
                    foreach ($file as $innerKey => $innerFile) {
                        if (is_array($innerFile)) {
                            $subFileUploads = $post->path_images ?? [];
                            foreach ($innerFile as $subkey => $subfile) {
                                if ($subfile) {
                                    $path = $subfile->storePublicly('images', 'public');
                                    $subFileUploads[$subkey] = $path;
                                    $newGalleryPaths[] = $path;
                                }
                            }
                            $uploadedFiles['path_images'] = $subFileUploads;

                            continue;
                        }
                        if ($innerFile) {
                            $path = $innerFile->storePublicly('images', 'public');
                            $uploadedFiles[$innerKey] = $path;
                            $newGalleryPaths[] = $path;
                        }
                    }

                    continue;
                }

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

            $post->review($request->has('post.reviewed'));
            $post->publish($request->has('post.published'));

            $post->fill(array_merge($dataPost, $uploadedFiles));

            if (! empty($dataEvent['event_period']) && $dataEvent['event_period'] != 'to') {
                $expl = explode(' to ', $dataEvent['event_period']);
                $event->start_at = $expl[0].':00';
                $event->end_at = $expl[1].':00';
            } else {
                $event->start_at = now();
                $event->end_at = now();
            }
            $event->fill($dataEvent);

            $post->save();

            $event->post()->associate($post);

            $event->save();

            // Masukkan file yang diupload ke tabel galleries dengan tag "event"
            foreach ($newGalleryPaths as $galleryPath) {
                Gallery::firstOrCreate(
                    ['path_image' => $galleryPath],
                    [
                        'event_id' => $event->id,
                        'title' => $post->title ?? 'Dokumentasi Event',
                        'description' => $post->description,
                        'tags' => ['event'],
                        'taken_at' => ! empty($event->start_at) ? date('Y-m-d', strtotime($event->start_at)) : now()->toDateString(),
                    ]
                );
            }

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
        if ($request->isMethod('put')) {
            $request->validate([
                'post.title' => ['required', 'string', 'max:45'],
                'post.description' => ['required', 'string'],
                'post.contents' => ['string'],
                'post.path_featured_image' => ['image'],
                'event_period' => ['required', 'string'],
                'max_attendant' => ['required', 'integer'],
            ]);

            $except = [
                '_token',
                '_method',
            ];

            $dataPost = $request->except(array_merge($except, ['post.images', 'post.path_featured_image']))['post'] ?? [];

            $dataEvent = $request->except(array_merge($except, ['post']));

            $post = &$event->post;

            $uploadedFiles = [];
            $newGalleryPaths = [];

            $filesToProcess = $request->file('post') ?? $request->file() ?? [];
            foreach ($filesToProcess as $key => $file) {
                if ($key === 'post' && is_array($file)) {
                    foreach ($file as $innerKey => $innerFile) {
                        if (is_array($innerFile)) {
                            $subFileUploads = $post->path_images ?? [];
                            foreach ($innerFile as $subkey => $subfile) {
                                if ($subfile) {
                                    $path = $subfile->storePublicly('images', 'public');
                                    $subFileUploads[$subkey] = $path;
                                    $newGalleryPaths[] = $path;
                                }
                            }
                            $uploadedFiles['path_images'] = $subFileUploads;

                            continue;
                        }
                        if ($innerFile) {
                            $path = $innerFile->storePublicly('images', 'public');
                            $uploadedFiles[$innerKey] = $path;
                            $newGalleryPaths[] = $path;
                        }
                    }

                    continue;
                }

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

            $post->review($request->has('post.reviewed'));
            $post->publish($request->has('post.published'));

            $post->fill(array_merge($dataPost, $uploadedFiles));

            if (! empty($dataEvent['event_period'])) {
                $expl = explode(' to ', $dataEvent['event_period']);
                $event->start_at = new DateTime($expl[0])->format('Y-m-d H:i:s');
                $event->end_at = new DateTime($expl[1])->format('Y-m-d H:i:s');
            }
            $event->fill($dataEvent);

            $post->save();
            $event->save();

            // Masukkan file yang diupload ke tabel galleries dengan tag "event"
            foreach ($newGalleryPaths as $galleryPath) {
                Gallery::firstOrCreate(
                    ['path_image' => $galleryPath],
                    [
                        'event_id' => $event->id,
                        'title' => $post->title ?? 'Dokumentasi Event',
                        'description' => $post->description,
                        'tags' => ['event'],
                        'taken_at' => ! empty($event->start_at) ? date('Y-m-d', strtotime($event->start_at)) : now()->toDateString(),
                    ]
                );
            }
        }

        return view('pages.events.edit', [
            'data' => $event,
            'method' => $request->routeIs('event.create') ? 'post' : 'put',
        ]);
    }

    public function delete(Request $request, Event $event)
    {
        if ($event->delete()) {
            return redirect()->back();
        }
    }
}

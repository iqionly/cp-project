<?php

use App\Models\Authorizations\Role;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $adminRole = Role::where('name', 'admin')->first();
    if (! $adminRole) {
        $adminRole = new Role;
        $adminRole->name = 'admin';
        $adminRole->role_grouping = 'admin';
        $adminRole->save();
    }

    $this->admin = User::factory()->create();
    $this->admin->roles()->attach($adminRole->id);

    Storage::fake('public');
});

test('uploading files when creating an event adds records to galleries with event tag and event_id', function () {
    $image = UploadedFile::fake()->image('event_featured.jpg');
    $subImage1 = UploadedFile::fake()->image('event_sub1.jpg');

    $response = $this->actingAs($this->admin)->post(route('event.create', ['context' => 'admin']), [
        'post' => [
            'title' => 'Touring Akbar DTC PLUS',
            'description' => 'Touring akbar menuju kawasan pegunungan.',
            'contents' => '<p>Deskripsi lengkap seputar touring akbar...</p>',
            'path_featured_image' => $image,
            'images' => [
                1 => $subImage1,
            ],
            'published' => 'on',
        ],
        'event_period' => '2026-12-01 08:00 to 2026-12-02 17:00',
        'max_attendant' => 50,
        'location' => 'Puncak',
    ]);

    $response->assertSessionHasNoErrors();

    $event = Event::latest('id')->first();
    expect($event)->not->toBeNull();

    // Verify galleries entries
    $galleries = Gallery::where('event_id', $event->id)->get();
    expect($galleries)->toHaveCount(2);

    foreach ($galleries as $gallery) {
        expect($gallery->tags)->toContain('event');
        expect($gallery->event_id)->toBe($event->id);
        expect($gallery->title)->toBe('Touring Akbar DTC PLUS');
        Storage::disk('public')->assertExists($gallery->getRawOriginal('path_image'));
    }
});

test('uploading files when editing an event adds records to galleries with event tag', function () {
    $post = new Post([
        'title' => 'Event Awal',
        'description' => 'Deskripsi event awal.',
        'contents' => '<p>Konten awal</p>',
        'published_at' => now(),
    ]);
    $post->user_id = $this->admin->id;
    $post->save();

    $event = new Event([
        'post_id' => $post->id,
        'location' => 'Bekasi',
        'max_attendant' => 30,
        'start_at' => now(),
        'end_at' => now()->addDays(1),
    ]);
    $event->post()->associate($post);
    $event->save();

    $newImage = UploadedFile::fake()->image('new_event_doc.jpg');

    $response = $this->actingAs($this->admin)->put(route('event.edit', ['context' => 'admin', 'event' => $event->id]), [
        'post' => [
            'title' => 'Event Awal Updated',
            'description' => 'Deskripsi event awal updated.',
            'contents' => '<p>Konten awal updated</p>',
            'images' => [
                1 => $newImage,
            ],
            'published' => 'on',
        ],
        'event_period' => '2026-12-05 09:00 to 2026-12-05 15:00',
        'max_attendant' => 35,
    ]);

    $response->assertSessionHasNoErrors();

    $gallery = Gallery::where('event_id', $event->id)->first();
    expect($gallery)->not->toBeNull();
    expect($gallery->tags)->toContain('event');
    expect($gallery->title)->toBe('Event Awal Updated');
    Storage::disk('public')->assertExists($gallery->getRawOriginal('path_image'));
});

test('uploading files when creating a post adds records to galleries with document tag and null event_id', function () {
    $featured = UploadedFile::fake()->image('doc_featured.jpg');
    $contentImage = UploadedFile::fake()->image('doc_sub.jpg');

    $response = $this->actingAs($this->admin)->post(route('post.create', ['context' => 'admin']), [
        'title' => 'Panduan AD ART Club',
        'description' => 'Dokumen resmi anggaran dasar dan anggaran rumah tangga DTC PLUS.',
        'contents' => str_repeat('Isi panduan ketentuan dan tata tertib club bagi seluruh anggota resmi. ', 5),
        'path_featured_image' => $featured,
        'images' => [
            1 => $contentImage,
        ],
        'published' => 'on',
    ]);

    $response->assertSessionHasNoErrors();

    $post = Post::latest('id')->first();
    expect($post)->not->toBeNull();

    $galleries = Gallery::whereNull('event_id')->where('title', 'Panduan AD ART Club')->get();
    expect($galleries)->toHaveCount(2);

    foreach ($galleries as $gallery) {
        expect($gallery->tags)->toContain('document');
        expect($gallery->event_id)->toBeNull();
        expect($gallery->description)->toBe('Dokumen resmi anggaran dasar dan anggaran rumah tangga DTC PLUS.');
        Storage::disk('public')->assertExists($gallery->getRawOriginal('path_image'));
    }
});

test('uploading files when editing a post adds records to galleries with document tag', function () {
    $post = new Post([
        'title' => 'SOP Touring',
        'description' => 'Standard operating procedure saat berkendara kelompok.',
        'contents' => str_repeat('Penjelasan detail SOP keselamatan berkendara bagi anggota. ', 5),
        'published_at' => now(),
    ]);
    $post->user_id = $this->admin->id;
    $post->save();

    $newDocImage = UploadedFile::fake()->image('sop_diagram.png');

    $response = $this->actingAs($this->admin)->put(route('post.edit', ['context' => 'admin', 'post' => $post->id]), [
        'title' => 'SOP Touring Update',
        'description' => 'Standard operating procedure update.',
        'contents' => str_repeat('Penjelasan detail SOP keselamatan berkendara update. ', 5),
        'images' => [
            1 => $newDocImage,
        ],
        'published' => 'on',
    ]);

    $response->assertSessionHasNoErrors();

    $gallery = Gallery::whereNull('event_id')->where('title', 'SOP Touring Update')->first();
    expect($gallery)->not->toBeNull();
    expect($gallery->tags)->toContain('document');
    expect($gallery->event_id)->toBeNull();
    Storage::disk('public')->assertExists($gallery->getRawOriginal('path_image'));
});

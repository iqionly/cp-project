<?php

use App\Models\Authorizations\Role;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\MasterData\Member;
use App\Models\Merchandise;
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

    $memberRole = Role::where('name', 'member')->first();
    if (! $memberRole) {
        $memberRole = new Role;
        $memberRole->name = 'member';
        $memberRole->role_grouping = 'member';
        $memberRole->save();
    }
});

test('public visitor can see merchandise list', function () {
    Merchandise::factory()->create([
        'name' => 'Jersey Official Club',
        'is_available' => true,
    ]);

    $response = $this->get(route('public.merchandise.index'));

    $response->assertStatus(200);
    $response->assertSee('Jersey Official Club');
    $response->assertSee('Merchandise Resmi Club');
});

test('public visitor can see activity gallery documentation', function () {
    Gallery::create([
        'title' => 'Touring Nasional 2026',
        'description' => 'Dokumentasi touring akbar di lereng gunung.',
        'path_image' => 'galleries/test.jpg',
        'tags' => ['touring', 'gunung'],
    ]);

    $response = $this->get(route('public.gallery.index'));

    $response->assertStatus(200);
    $response->assertSee('Touring Nasional 2026');
    $response->assertSee('Dokumentasi Kegiatan Club');
});

test('admin can create, update, and delete merchandise', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $admin->roles()->attach($adminRole->id);

    // 1. Create Merchandise
    $createResponse = $this->actingAs($admin)->post(route('merchandise.create', ['context' => 'admin']), [
        'name' => 'Topi Official Club',
        'description' => 'Topi bordir logo club warna hitam.',
        'price' => 75000,
        'stock' => 20,
        'is_available' => '1',
        'path_image' => UploadedFile::fake()->image('hat.jpg'),
    ]);

    $createResponse->assertRedirect(route('merchandise.index', ['context' => 'admin']));
    $this->assertDatabaseHas('merchandises', ['name' => 'Topi Official Club']);

    $merch = Merchandise::where('name', 'Topi Official Club')->first();

    // 2. Edit Merchandise
    $editResponse = $this->actingAs($admin)->put(route('merchandise.edit', ['context' => 'admin', 'merchandise' => $merch->id]), [
        'name' => 'Topi Official Club Premium',
        'description' => 'Topi bordir logo club edisi spesial.',
        'price' => 85000,
        'stock' => 15,
        'is_available' => '1',
    ]);

    $editResponse->assertRedirect(route('merchandise.edit', ['context' => 'admin', 'merchandise' => $merch->id]));
    $this->assertDatabaseHas('merchandises', ['name' => 'Topi Official Club Premium', 'price' => 85000]);

    // 3. Delete Merchandise
    $deleteResponse = $this->actingAs($admin)->delete(route('merchandise.delete', ['context' => 'admin', 'merchandise' => $merch->id]));
    $deleteResponse->assertRedirect(route('merchandise.index', ['context' => 'admin']));
    $this->assertDatabaseMissing('merchandises', ['id' => $merch->id]);
});

test('admin can upload new gallery documentation', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $admin->roles()->attach($adminRole->id);

    $post = Post::factory()->create([
        'user_id' => $admin->id,
        'title' => 'Bakti Sosial Ramadhan Event',
        'published_at' => now(),
    ]);

    $event = Event::factory()->create([
        'post_id' => $post->id,
        'location' => 'Panti Asuhan Kasih Ibu',
        'start_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'end_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
    ]);

    $response = $this->actingAs($admin)->post(route('gallery.store', ['context' => 'admin']), [
        'event_id' => $event->id,
        'title' => 'Penyerahan Santunan',
        'description' => 'Penyerahan santunan kepada anak yatim.',
        'path_image' => UploadedFile::fake()->image('baksos.jpg'),
        'taken_at' => '2026-05-10',
        'tags' => 'baksos, ramadhan, santunan',
    ]);

    $response->assertRedirect(route('gallery.index', ['context' => 'admin']));
    $this->assertDatabaseHas('galleries', [
        'event_id' => $event->id,
        'title' => 'Penyerahan Santunan',
    ]);

    $savedGallery = Gallery::where('title', 'Penyerahan Santunan')->first();
    expect($savedGallery->taken_at->format('Y-m-d'))->toBe('2026-05-10');

    // Test public gallery grid view
    $publicGalleryResponse = $this->get(route('public.gallery.index'));
    $publicGalleryResponse->assertStatus(200);
    $publicGalleryResponse->assertSee('10 May 2026');
    $publicGalleryResponse->assertSee('title="Bakti Sosial Ramadhan Event - Penyerahan santunan kepada anak yatim."', false);

    // Test public event show with attached gallery documentation
    $publicEventResponse = $this->get(route('public.events.show', $event));
    $publicEventResponse->assertStatus(200);
    $publicEventResponse->assertSee('Dokumentasi Kegiatan');
    $publicEventResponse->assertSee('10 May 2026');
    $publicEventResponse->assertSee('title="Bakti Sosial Ramadhan Event - Penyerahan santunan kepada anak yatim."', false);
});

test('member can view agenda and register to an event', function () {
    $user = User::factory()->create();
    $memberRole = Role::where('name', 'member')->first();
    $user->roles()->attach($memberRole->id);

    $member = Member::factory()->create([
        'user_id' => $user->id,
        'first_name' => 'Ahmad',
        'last_name' => 'Fauzi',
    ]);

    $post = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Touring Kemerdekaan',
        'published_at' => now(),
    ]);

    $event = Event::factory()->create([
        'post_id' => $post->id,
        'location' => 'Puncak Pass, Bogor',
        'start_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
        'end_at' => now()->addDays(6)->format('Y-m-d H:i:s'),
        'max_attendant' => 50,
    ]);

    // 1. Member views agenda page
    $viewResponse = $this->actingAs($user)->get(route('member.agenda', ['context' => 'member']));
    $viewResponse->assertStatus(200);
    $viewResponse->assertSee('Touring Kemerdekaan');
    $viewResponse->assertSee('Puncak Pass, Bogor');

    // 2. Member registers for the event
    $registerResponse = $this->actingAs($user)->post(route('member.agenda.register', [
        'context' => 'member',
        'event' => $event->id,
    ]), [
        'notes' => 'Membawa motor sendiri dan perlengkapan safety',
    ]);

    $registerResponse->assertRedirect();
    $this->assertDatabaseHas('event_registrations', [
        'event_id' => $event->id,
        'member_id' => $member->id,
        'notes' => 'Membawa motor sendiri dan perlengkapan safety',
    ]);

    // 3. Member unregisters from the event
    $unregisterResponse = $this->actingAs($user)->delete(route('member.agenda.unregister', [
        'context' => 'member',
        'event' => $event->id,
    ]));

    $unregisterResponse->assertRedirect();
    $this->assertDatabaseMissing('event_registrations', [
        'event_id' => $event->id,
        'member_id' => $member->id,
    ]);
});

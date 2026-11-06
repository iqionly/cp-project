<?php

use App\Models\Authorizations\Role;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Gallery;
use App\Models\MasterData\Member;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $memberRole = Role::where('name', 'member')->first();
    if (! $memberRole) {
        $memberRole = new Role;
        $memberRole->name = 'member';
        $memberRole->role_grouping = 'member';
        $memberRole->save();
    }

    $adminRole = Role::where('name', 'admin')->first();
    if (! $adminRole) {
        $adminRole = new Role;
        $adminRole->name = 'admin';
        $adminRole->role_grouping = 'admin';
        $adminRole->save();
    }

    $this->user = User::factory()->create();
    $this->user->roles()->attach($memberRole->id);

    $this->member = Member::factory()->for($this->user)->create();
});

test('guest cannot access history agenda page and is redirected to login', function () {
    $response = $this->get(route('member.agenda.history', ['context' => 'member']));

    $response->assertRedirect(route('auth.login'));
});

test('member can view history agenda page displaying past events', function () {
    // Past event (ended yesterday)
    $pastPost = new Post([
        'title' => 'Touring Sukabumi 2025',
        'description' => 'Touring tahun lalu menyusuri pesisir selatan.',
        'published_at' => now()->subMonths(3),
    ]);
    $pastPost->user_id = $this->user->id;
    $pastPost->save();

    $pastEvent = new Event([
        'post_id' => $pastPost->id,
        'location' => 'Pelabuhan Ratu',
        'start_at' => now()->subDays(10),
        'end_at' => now()->subDays(8),
        'max_attendant' => 50,
    ]);
    $pastEvent->post()->associate($pastPost);
    $pastEvent->save();

    // Upcoming event (should NOT appear on history)
    $futurePost = new Post([
        'title' => 'Touring Bandung Mendatang',
        'description' => 'Touring yang akan datang bulan depan.',
        'published_at' => now(),
    ]);
    $futurePost->user_id = $this->user->id;
    $futurePost->save();

    $futureEvent = new Event([
        'post_id' => $futurePost->id,
        'location' => 'Ciwidey',
        'start_at' => now()->addDays(5),
        'end_at' => now()->addDays(7),
        'max_attendant' => 40,
    ]);
    $futureEvent->post()->associate($futurePost);
    $futureEvent->save();

    $response = $this->actingAs($this->user)->get(route('member.agenda.history', ['context' => 'member']));

    $response->assertStatus(200);
    $response->assertSee('Riwayat & Arsip Kegiatan Club', false);
    $response->assertSee('Touring Sukabumi 2025');
    $response->assertSee('Selesai');
    $response->assertDontSee('Touring Bandung Mendatang');
});

test('history agenda shows member participation and gallery documentation if present', function () {
    $post = new Post([
        'title' => 'Baksos Ramadhan 2025',
        'description' => 'Kegiatan santunan dan berbagi kebaikan bersama yatim.',
        'published_at' => now()->subMonths(2),
    ]);
    $post->user_id = $this->user->id;
    $post->save();

    $event = new Event([
        'post_id' => $post->id,
        'location' => 'Bekasi Timur',
        'start_at' => now()->subDays(20),
        'end_at' => now()->subDays(19),
        'max_attendant' => 100,
    ]);
    $event->post()->associate($post);
    $event->save();

    // Member registered
    EventRegistration::create([
        'event_id' => $event->id,
        'member_id' => $this->member->id,
        'registered_at' => now()->subDays(25),
    ]);

    // Gallery item linked to event
    Gallery::create([
        'event_id' => $event->id,
        'title' => 'Dokumentasi Baksos',
        'path_image' => 'galleries/baksos1.jpg',
        'tags' => ['event'],
        'taken_at' => now()->subDays(20)->toDateString(),
    ]);

    $response = $this->actingAs($this->user)->get(route('member.agenda.history', ['context' => 'member']));

    $response->assertStatus(200);
    $response->assertSee('Baksos Ramadhan 2025');
    $response->assertSee('Terdaftar / Diikuti');
    $response->assertSee('Foto (1)');
});

test('sidebar navigation displays indonesian menu labels and history agenda link', function () {
    $response = $this->actingAs($this->user)->get(route('member.profile', ['context' => 'member']));

    $response->assertStatus(200);
    $response->assertSee('Profil Saya');
    $response->assertSee('Agenda Kegiatan');
    $response->assertSee('Riwayat Agenda');
    $response->assertSee('Tagihan & Iuran', false);
});

test('admin sidebar navigation displays indonesian master data and setting menus', function () {
    $adminUser = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $adminUser->roles()->attach($adminRole->id);

    $response = $this->actingAs($adminUser)->get(route('event.index', ['context' => 'admin']));

    $response->assertStatus(200);
    $response->assertSee('Data Master');
    $response->assertSee('Data Anggota');
    $response->assertSee('Postingan');
    $response->assertSee('Kegiatan & Acara', false);
    $response->assertSee('Dokumentasi');
    $response->assertSee('Data Pengguna');
    $response->assertSee('Pengaturan');
    $response->assertSee('Buat Tagihan');
    $response->assertSee('Pengaturan Menu');
});

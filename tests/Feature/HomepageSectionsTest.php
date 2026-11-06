<?php

use App\Models\Authorizations\Role;
use App\Models\Gallery;
use App\Models\MenuPost;
use App\Models\Merchandise;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $adminRole = Role::where('name', 'admin')->first();
    if (! $adminRole) {
        $adminRole = new Role;
        $adminRole->name = 'admin';
        $adminRole->role_grouping = 'admin';
        $adminRole->save();
    }
});

test('homepage loads successfully with banner section', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('banner1.jpg');
    $response->assertSee('id="banner"', false);
});

test('homepage renders sejarah club section with short description and lihat selengkapnya link', function () {
    $author = User::factory()->create();

    $post = new Post([
        'title' => 'Sejarah Club',
        'description' => 'District Tiger Cikarang PLUS adalah organisasi club kendaraan bermotor yang bertempat di kabupaten Bekasi.',
        'contents' => '<p>Sejarah lengkap DTC PLUS berawal pada tahun 2004...</p>',
        'published_at' => now(),
        'reviewed_at' => now(),
    ]);
    $post->user_id = $author->id;
    $post->save();

    MenuPost::create([
        'post_id' => $post->id,
        'slug' => 'profile',
        'default' => 40,
        'menu_title' => 'Sejarah Club',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Sejarah Club');
    $response->assertSee('District Tiger Cikarang PLUS');
    $response->assertSee('Lihat Selengkapnya');
    $response->assertSee(url('profile'));
});

test('homepage renders struktur pengurus section and removes standalone struktur from topbar navigation', function () {
    $author = User::factory()->create();

    $post = new Post([
        'title' => 'Struktur Kepengurusan',
        'description' => 'District Tiger Cikarang PLUS memiliki anggota kepungurusan yang saling bersinergi.',
        'path_featured_image' => 'galleries/struktur-anggota.png',
        'published_at' => now(),
        'reviewed_at' => now(),
    ]);
    $post->user_id = $author->id;
    $post->save();

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Struktur Pengurus');
    $response->assertSee('galleries/struktur-anggota.png');
    $response->assertSee('Perbesar Bagan Struktur');

    // Standalone Struktur menu must NOT exist in the navigation
    $response->assertDontSee('<a class="nav-link" href="'.url('struktur').'">', false);
});

test('homepage renders up to 6 latest documentation photos with link to full gallery', function () {
    for ($i = 1; $i <= 8; $i++) {
        Gallery::create([
            'title' => 'Dokumentasi Acara '.$i,
            'description' => 'Momen kebersamaan acara ke '.$i,
            'path_image' => 'galleries/event-'.$i.'.jpg',
            'taken_at' => now()->addDays($i)->toDateString(),
        ]);
    }

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Dokumentasi Club');
    $response->assertSee('Lihat Semua Dokumentasi');
    $response->assertSee(route('public.gallery.index'));

    // Should see latest items (8 down to 3) but not oldest item 1 (capped to 6)
    $response->assertSee('Dokumentasi Acara 8');
    $response->assertSee('Dokumentasi Acara 3');
    $response->assertDontSee('Dokumentasi Acara 1');
});

test('homepage renders merchandise preview with maximum 4 items and link to merchandise catalog', function () {
    for ($i = 1; $i <= 6; $i++) {
        Merchandise::factory()->create([
            'name' => 'Produk Merch '.$i,
            'price' => 50000 + ($i * 10000),
            'stock' => 10,
            'is_available' => true,
        ]);
    }

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Merchandise Resmi Club');
    $response->assertSee('Lihat Semua Merchandise');
    $response->assertSee(route('public.merchandise.index'));

    // Should see newest products (6 down to 3) but not oldest products 1 and 2
    $response->assertSee('Produk Merch 6');
    $response->assertSee('Produk Merch 3');
    $response->assertDontSee('Produk Merch 1');
});

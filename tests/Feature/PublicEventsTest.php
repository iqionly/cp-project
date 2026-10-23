<?php

use App\Models\Event;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public events index displays active event and next upcoming event button with featured image, and hides green badge', function () {
    $user = User::first() ?? User::factory()->create();

    // Active event (currently ongoing)
    $activePost = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Active Event Ongoing',
        'published_at' => now()->subDay(),
        'reviewed_at' => now()->subDay(),
        'path_featured_image' => 'https://example.com/active-event.jpg',
    ]);
    $activeEvent = Event::factory()->for($activePost)->create([
        'start_at' => now()->subHours(2),
        'end_at' => now()->addDays(2),
    ]);

    // Next event that will start (upcoming)
    $upcomingPost = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Next Upcoming Event',
        'published_at' => now(),
        'reviewed_at' => now(),
        'path_featured_image' => 'https://example.com/upcoming-event.jpg',
    ]);
    $upcomingEvent = Event::factory()->for($upcomingPost)->create([
        'start_at' => now()->addDays(3),
        'end_at' => now()->addDays(5),
    ]);

    // Older event (ended in the past)
    $pastPost = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Older Past Event',
        'published_at' => now()->subDays(10),
        'reviewed_at' => now()->subDays(10),
    ]);
    $pastEvent = Event::factory()->for($pastPost)->create([
        'start_at' => now()->subDays(10),
        'end_at' => now()->subDays(5),
    ]);

    $response = $this->get(route('public.events.index'));

    $response->assertStatus(200);

    // Active event is shown as current main display
    $response->assertSee($activePost->title);

    // Green badge "Current Event" has been removed
    $response->assertDontSee('Current Event');

    // Next event button with featured image is shown
    $response->assertSee('Next Event');
    $response->assertSee($upcomingPost->title);
    $response->assertSee($upcomingPost->path_featured_image);

    // Older past event is hidden
    $response->assertDontSee($pastPost->title);
});

test('older ended events return 404 when directly accessed', function () {
    $user = User::first() ?? User::factory()->create();

    $pastPost = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Old Event Ended',
        'published_at' => now()->subDays(10),
        'reviewed_at' => now()->subDays(10),
    ]);
    $pastEvent = Event::factory()->for($pastPost)->create([
        'start_at' => now()->subDays(10),
        'end_at' => now()->subDays(2),
    ]);

    $response = $this->get(route('public.events.show', $pastEvent));

    $response->assertStatus(404);
});

test('unpublished event returns 404 on show and is hidden on index', function () {
    $user = User::first() ?? User::factory()->create();

    $draftPost = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Draft Unpublished Upcoming Event',
        'published_at' => null,
    ]);
    $draftEvent = Event::factory()->for($draftPost)->create([
        'start_at' => now()->addDays(1),
        'end_at' => now()->addDays(2),
    ]);

    $showResponse = $this->get(route('public.events.show', $draftEvent));
    $showResponse->assertStatus(404);

    $indexResponse = $this->get(route('public.events.index'));
    $indexResponse->assertDontSee('Draft Unpublished Upcoming Event');
});

test('public events page handles empty state gracefully', function () {
    $response = $this->get(route('public.events.index'));

    $response->assertStatus(200);
    $response->assertSee('No Events Available');
});

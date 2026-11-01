<?php

use App\Models\Authorizations\Role;
use App\Models\Contribution\Invoice;
use App\Models\Contribution\InvoiceJournal;
use App\Models\Event;
use App\Models\MasterData\Member;
use App\Models\Post;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
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

    $memberRole = Role::where('name', 'member')->first();
    if (! $memberRole) {
        $memberRole = new Role;
        $memberRole->name = 'member';
        $memberRole->role_grouping = 'member';
        $memberRole->save();
    }
});

test('whatsapp notification service correctly formats indonesian phone numbers', function () {
    expect(WhatsAppNotificationService::formatPhoneNumber('08123456789'))->toBe('628123456789')
        ->and(WhatsAppNotificationService::formatPhoneNumber('+628123456789'))->toBe('628123456789')
        ->and(WhatsAppNotificationService::formatPhoneNumber('628123456789'))->toBe('628123456789')
        ->and(WhatsAppNotificationService::formatPhoneNumber('0812-3456-7890'))->toBe('6281234567890')
        ->and(WhatsAppNotificationService::formatPhoneNumber('+62 812 3456 7890'))->toBe('6281234567890')
        ->and(WhatsAppNotificationService::formatPhoneNumber('81234567890'))->toBe('6281234567890')
        ->and(WhatsAppNotificationService::formatPhoneNumber(''))->toBeNull()
        ->and(WhatsAppNotificationService::formatPhoneNumber(null))->toBeNull()
        ->and(WhatsAppNotificationService::formatPhoneNumber('1234'))->toBeNull();
});

test('admin can see member list with whatsapp notification buttons for active iuran and events', function () {
    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $admin->roles()->attach($adminRole->id);

    // Create journal
    $journal = InvoiceJournal::create([
        'name' => 'Iuran Wajib Bulanan',
        'code' => 'FEE-WAJIB',
        'default_amount' => 50000,
    ]);

    // Member 1: Has phone number and active iuran
    $user1 = User::factory()->create(['name' => 'member_active']);
    $member1 = new Member;
    $member1->member_code = 'ID00010';
    $member1->first_name = 'Budi';
    $member1->last_name = 'Santoso';
    $member1->phone_number = '081234567890';
    $member1->user()->associate($user1);
    $member1->save();

    $invoice1 = Invoice::create([
        'member_id' => $member1->id,
        'invoice_number' => 'INV-202611-0001',
        'period_start' => '2026-11-01',
        'issued_at' => '2026-11-01',
        'due_at' => '2026-11-28',
        'status' => 'issued',
    ]);
    $invoice1->invoice_items()->create([
        'invoice_journal_id' => $journal->id,
        'description' => 'Iuran November 2026',
        'quantity' => 1,
        'unit_price' => 50000,
        'amount' => 50000,
    ]);

    // Member 2: Has phone number but NO active iuran
    $user2 = User::factory()->create(['name' => 'member_clean']);
    $member2 = new Member;
    $member2->member_code = 'ID00020';
    $member2->first_name = 'Siti';
    $member2->last_name = 'Rahma';
    $member2->phone_number = '089876543210';
    $member2->user()->associate($user2);
    $member2->save();

    // Member 3: Has NO phone number
    $user3 = User::factory()->create(['name' => 'member_nophone']);
    $member3 = new Member;
    $member3->member_code = 'ID00030';
    $member3->first_name = 'Agus';
    $member3->phone_number = null;
    $member3->user()->associate($user3);
    $member3->save();

    // Create an upcoming active event
    $post = new Post([
        'title' => 'Sunday Morning Ride 2026',
        'description' => 'Sunmori seru keliling kota bersama seluruh member.',
        'published_at' => now(),
    ]);
    $post->user()->associate($admin);
    $post->save();
    Event::create([
        'post_id' => $post->id,
        'location' => 'Monas, Jakarta',
        'start_at' => now()->addDays(5),
        'end_at' => now()->addDays(5)->addHours(4),
        'max_attendant' => 50,
    ]);

    $response = $this->actingAs($admin)->get(route('master-data.member.list', ['context' => 'admin']));

    $response->assertStatus(200);
    $response->assertSee('List Member');
    $response->assertSee('WhatsApp Notification');
    $response->assertSee('Budi Santoso');
    $response->assertSee('Siti Rahma');
    $response->assertSee('Agus');

    // Member 1 has active iuran button link
    $response->assertSee(route('master-data.member.notify-iuran', ['context' => 'admin', 'member' => $member1->id]));

    // Member 2 shows Lunas badge
    $response->assertSee('Lunas');

    // Event notification link/button is present
    $response->assertSee('Event');
});

test('admin can trigger notify iuran via whatsapp redirect for member with active iuran', function () {
    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $admin->roles()->attach($adminRole->id);

    $journal = InvoiceJournal::create([
        'name' => 'Iuran Wajib Bulanan',
        'code' => 'FEE-WAJIB',
        'default_amount' => 75000,
    ]);

    $user = User::factory()->create(['name' => 'john_doe']);
    $member = new Member;
    $member->member_code = 'ID00077';
    $member->first_name = 'John';
    $member->last_name = 'Doe';
    $member->phone_number = '081234567890';
    $member->user()->associate($user);
    $member->save();

    $invoice = Invoice::create([
        'member_id' => $member->id,
        'invoice_number' => 'INV-202611-0077',
        'period_start' => '2026-11-01',
        'issued_at' => '2026-11-01',
        'due_at' => '2026-11-28',
        'status' => 'issued',
    ]);
    $invoice->invoice_items()->create([
        'invoice_journal_id' => $journal->id,
        'description' => 'Iuran November 2026',
        'quantity' => 1,
        'unit_price' => 75000,
        'amount' => 75000,
    ]);

    $response = $this->actingAs($admin)->get(route('master-data.member.notify-iuran', [
        'context' => 'admin',
        'member' => $member->id,
    ]));

    // Should redirect away to wa.me with clean number 6281234567890
    $response->assertStatus(302);
    $targetUrl = $response->headers->get('Location');
    expect($targetUrl)->toContain('https://wa.me/6281234567890?text=')
        ->and($targetUrl)->toContain(rawurlencode('John Doe'))
        ->and($targetUrl)->toContain(rawurlencode('INV-202611-0077'))
        ->and($targetUrl)->toContain(rawurlencode('75.000'));

    // Verify invoice_reminders table has tracking record
    $this->assertDatabaseHas('invoice_reminders', [
        'invoice_id' => $invoice->id,
        'reminder_type' => 'whatsapp',
        'status' => 'sent',
    ]);
});

test('admin can preview notify iuran via json api', function () {
    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $admin->roles()->attach($adminRole->id);

    $journal = InvoiceJournal::create([
        'name' => 'Iuran Wajib Bulanan',
        'code' => 'FEE-WAJIB',
        'default_amount' => 50000,
    ]);

    $user = User::factory()->create(['name' => 'member_preview']);
    $member = new Member;
    $member->member_code = 'ID00088';
    $member->first_name = 'Jane';
    $member->phone_number = '+62 857-1111-2222';
    $member->user()->associate($user);
    $member->save();

    $invoice = Invoice::create([
        'member_id' => $member->id,
        'invoice_number' => 'INV-202611-0088',
        'period_start' => '2026-11-01',
        'issued_at' => '2026-11-01',
        'due_at' => '2026-11-28',
    ]);
    $invoice->invoice_items()->create([
        'invoice_journal_id' => $journal->id,
        'description' => 'Iuran Member November',
        'quantity' => 1,
        'unit_price' => 50000,
        'amount' => 50000,
    ]);

    $response = $this->actingAs($admin)->getJson(route('master-data.member.notify-iuran', [
        'context' => 'admin',
        'member' => $member->id,
    ]));

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
        'phone' => '6285711112222',
        'active_invoices_count' => 1,
        'total_amount' => 50000,
    ]);
    expect($response->json('wa_url'))->toContain('https://wa.me/6285711112222?text=')
        ->and($response->json('message'))->toContain('Jane')
        ->and($response->json('message'))->toContain('INV-202611-0088');
});

test('notify iuran fails gracefully when member has no phone or no active iuran', function () {
    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $admin->roles()->attach($adminRole->id);

    // Member with no phone number
    $user1 = User::factory()->create(['name' => 'nophone']);
    $member1 = new Member;
    $member1->member_code = 'ID00101';
    $member1->phone_number = null;
    $member1->user()->associate($user1);
    $member1->save();

    $response1 = $this->actingAs($admin)->get(route('master-data.member.notify-iuran', [
        'context' => 'admin',
        'member' => $member1->id,
    ]));
    $response1->assertRedirect();
    $response1->assertSessionHas('error');

    // Member with phone but no active iuran
    $user2 = User::factory()->create(['name' => 'clean_member']);
    $member2 = new Member;
    $member2->member_code = 'ID00102';
    $member2->phone_number = '081234567890';
    $member2->user()->associate($user2);
    $member2->save();

    $response2 = $this->actingAs($admin)->get(route('master-data.member.notify-iuran', [
        'context' => 'admin',
        'member' => $member2->id,
    ]));
    $response2->assertRedirect();
    $response2->assertSessionHas('info');
});

test('admin can trigger notify event via whatsapp redirect for member', function () {
    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $admin->roles()->attach($adminRole->id);

    $user = User::factory()->create(['name' => 'rider_one']);
    $member = new Member;
    $member->member_code = 'ID00099';
    $member->first_name = 'Rider';
    $member->phone_number = '087812345678';
    $member->user()->associate($user);
    $member->save();

    $post = new Post([
        'title' => 'Touring Lintas Jawa 2026',
        'description' => 'Touring akbar menjelajahi rute pantai selatan.',
        'published_at' => now(),
    ]);
    $post->user()->associate($admin);
    $post->save();
    $event = Event::create([
        'post_id' => $post->id,
        'location' => 'Pantai Pangandaran',
        'start_at' => now()->addDays(10),
        'end_at' => now()->addDays(12),
        'max_attendant' => 100,
    ]);

    $response = $this->actingAs($admin)->get(route('master-data.member.notify-event', [
        'context' => 'admin',
        'member' => $member->id,
        'event' => $event->id,
    ]));

    $response->assertStatus(302);
    $targetUrl = $response->headers->get('Location');
    expect($targetUrl)->toContain('https://wa.me/6287812345678?text=')
        ->and($targetUrl)->toContain(rawurlencode('Touring Lintas Jawa 2026'))
        ->and($targetUrl)->toContain(rawurlencode('Pantai Pangandaran'))
        ->and($targetUrl)->toContain(rawurlencode(route('public.events.show', ['event' => $event->id])));
});

test('admin can preview notify event via json api', function () {
    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $admin->roles()->attach($adminRole->id);

    $user = User::factory()->create(['name' => 'rider_two']);
    $member = new Member;
    $member->member_code = 'ID00100';
    $member->first_name = 'Ahmad';
    $member->phone_number = '081299998888';
    $member->user()->associate($user);
    $member->save();

    $post = new Post([
        'title' => 'Track Day Sentul 2026',
        'description' => 'Latihan bersama di Sirkuit Sentul.',
        'published_at' => now(),
    ]);
    $post->user()->associate($admin);
    $post->save();
    $event = Event::create([
        'post_id' => $post->id,
        'location' => 'Sirkuit Sentul, Bogor',
        'start_at' => now()->addDays(3),
        'end_at' => now()->addDays(3)->addHours(6),
        'max_attendant' => 30,
    ]);

    $response = $this->actingAs($admin)->getJson(route('master-data.member.notify-event', [
        'context' => 'admin',
        'member' => $member->id,
        'event' => $event->id,
    ]));

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
        'phone' => '6281299998888',
        'event_title' => 'Track Day Sentul 2026',
    ]);
    expect($response->json('wa_url'))->toContain('https://wa.me/6281299998888?text=')
        ->and($response->json('message'))->toContain('Track Day Sentul 2026')
        ->and($response->json('message'))->toContain('Sirkuit Sentul, Bogor');
});

test('notify event fails gracefully when member has no phone or no active event exists', function () {
    $admin = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $admin->roles()->attach($adminRole->id);

    // Member with no phone number
    $user1 = User::factory()->create(['name' => 'nophone_event']);
    $member1 = new Member;
    $member1->member_code = 'ID00103';
    $member1->phone_number = null;
    $member1->user()->associate($user1);
    $member1->save();

    $response1 = $this->actingAs($admin)->get(route('master-data.member.notify-event', [
        'context' => 'admin',
        'member' => $member1->id,
    ]));
    $response1->assertRedirect();
    $response1->assertSessionHas('error');

    // Member with phone but no active/upcoming events exist in db
    $user2 = User::factory()->create(['name' => 'phone_no_event']);
    $member2 = new Member;
    $member2->member_code = 'ID00104';
    $member2->phone_number = '081234567890';
    $member2->user()->associate($user2);
    $member2->save();

    $response2 = $this->actingAs($admin)->get(route('master-data.member.notify-event', [
        'context' => 'admin',
        'member' => $member2->id,
    ]));
    $response2->assertRedirect();
    $response2->assertSessionHas('error');
});

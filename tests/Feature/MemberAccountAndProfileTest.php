<?php

use App\Models\Authorizations\Role;
use App\Models\MasterData\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

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

test('admin can view create member account page', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(1);

    $response = $this->actingAs($admin)->get(route('master-data.member.create', ['context' => 'admin']));

    $response->assertStatus(200);
    $response->assertSee('Create Member Account');
    $response->assertSee('Member Username');
    $response->assertSee('Default Password');
});

test('admin can create member account with manual username and default password', function () {
    $admin = User::factory()->create();
    $admin->roles()->attach(1);

    $response = $this->actingAs($admin)->post(route('master-data.member.create', ['context' => 'admin']), [
        'username' => 'newbie_member',
        'password' => 'secret123',
        'member_code' => 'ID99001',
    ]);

    $response->assertRedirect(route('master-data.member.list'));
    $response->assertSessionHas('success');

    // User is created with username, default password, and email is null
    $user = User::where('name', 'newbie_member')->first();
    expect($user)->not->toBeNull()
        ->and($user->email)->toBeNull()
        ->and(Hash::check('secret123', $user->password))->toBeTrue()
        ->and($user->roles->pluck('name')->contains('member'))->toBeTrue();

    // Member record is associated with user and has member_code
    $member = Member::where('user_id', $user->id)->first();
    expect($member)->not->toBeNull()
        ->and($member->member_code)->toBe('ID99001')
        ->and($member->first_name)->toBeNull()
        ->and($user->isProfileComplete())->toBeFalse();
});

test('member with incomplete profile is redirected to profile completion page on login', function () {
    $user = User::factory()->create([
        'name' => 'first_timer',
        'email' => null,
        'password' => Hash::make('password'),
    ]);
    $user->roles()->attach(2);

    $member = new Member;
    $member->member_code = 'ID00099';
    $member->user()->associate($user);
    $member->save();

    // Member can authenticate with username and default password
    $loginResponse = $this->post(route('auth.login'), [
        'user' => 'first_timer',
        'pin' => 'password',
    ]);

    $loginResponse->assertRedirect('/member/home');

    // Visiting home redirects to profile because details are incomplete
    $homeResponse = $this->actingAs($user)->get('/member/home');
    $homeResponse->assertRedirect(route('member.profile'));
    $homeResponse->assertSessionHas('warning');
});

test('member can update details, change username, email, and password on profile page', function () {
    $user = User::factory()->create([
        'name' => 'temp_username',
        'email' => null,
        'password' => Hash::make('default_password'),
    ]);
    $user->roles()->attach(2);

    $member = new Member;
    $member->member_code = 'ID00088';
    $member->user()->associate($user);
    $member->save();

    $profilePage = $this->actingAs($user)->get(route('member.profile', ['context' => 'member']));
    $profilePage->assertStatus(200);
    $profilePage->assertSee('Account Information (Users)');
    $profilePage->assertSee('Personal Information (Member Details)');

    // Member submits details
    $updateResponse = $this->actingAs($user)->put(route('member.profile', ['context' => 'member']), [
        'name' => 'permanent_username',
        'email' => 'member@example.com',
        'password' => 'new_secure_password',
        'password_confirmation' => 'new_secure_password',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'gender' => 'M',
        'birth_date' => '1995-05-15',
        'phone_number' => '08123456789',
        'address' => 'Jl. Merdeka No. 45, Bandung',
        'nik' => '3201234567890001',
        'plate_number' => 'D 1234 XY',
        'emergency_person' => 'Jane Doe',
        'emergency_phone_number' => '08198765432',
    ]);

    $updateResponse->assertRedirect(route('member.profile'));
    $updateResponse->assertSessionHas('success');

    // Refresh user & member
    $user->refresh();
    $member->refresh();

    expect($user->name)->toBe('permanent_username')
        ->and($user->email)->toBe('member@example.com')
        ->and(Hash::check('new_secure_password', $user->password))->toBeTrue()
        ->and($member->first_name)->toBe('John')
        ->and($member->last_name)->toBe('Doe')
        ->and($member->address)->toBe('Jl. Merdeka No. 45, Bandung')
        ->and($member->nik)->toBe('3201234567890001')
        ->and($user->isProfileComplete())->toBeTrue();

    // Now visiting home does not redirect
    $homeResponse = $this->actingAs($user)->get('/member/home');
    $homeResponse->assertStatus(200);
});

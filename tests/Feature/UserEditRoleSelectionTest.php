<?php

use App\Models\Authorizations\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminRole = Role::firstOrCreate(
        ['name' => 'admin'],
        ['role_grouping' => 'admin', 'description' => 'Administrator']
    );

    $this->memberRole = Role::firstOrCreate(
        ['name' => 'member'],
        ['role_grouping' => 'member', 'description' => 'Member']
    );

    $this->admin = User::factory()->create();
    $this->admin->roles()->attach($this->adminRole->id);
});

test('admin can view user edit page with role selection field', function () {
    $targetUser = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
    $targetUser->roles()->attach($this->memberRole->id);

    $response = $this->actingAs($this->admin)->get(route('master-data.user.edit', [
        'context' => 'admin',
        'user' => $targetUser->id,
    ]));

    $response->assertStatus(200);
    $response->assertSee('Roles');
    $response->assertSee('name="roles[]"', false);
    $response->assertSee('value="'.$this->memberRole->id.'"', false);
    $response->assertSee('value="'.$this->adminRole->id.'"', false);
});

test('admin can update user roles via multiple select', function () {
    $targetUser = User::factory()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);
    $targetUser->roles()->attach($this->memberRole->id);

    // Assign both member and admin roles
    $response = $this->actingAs($this->admin)->put(route('master-data.user.edit', [
        'context' => 'admin',
        'user' => $targetUser->id,
    ]), [
        'name' => 'Jane Doe Updated',
        'email' => 'jane.updated@example.com',
        'roles' => [$this->adminRole->id, $this->memberRole->id],
        'verified' => '1',
    ]);

    $response->assertSessionHasNoErrors();
    $targetUser->refresh();

    expect($targetUser->name)->toBe('Jane Doe Updated');
    expect($targetUser->email)->toBe('jane.updated@example.com');
    expect($targetUser->roles->pluck('id')->toArray())
        ->toEqualCanonicalizing([$this->adminRole->id, $this->memberRole->id]);
});

test('admin can change user roles to admin only and keep existing password intact when not provided', function () {
    $originalPassword = 'secret-password-123';
    $targetUser = User::factory()->create([
        'name' => 'Role Changer',
        'email' => 'changer@example.com',
        'password' => Hash::make($originalPassword),
    ]);
    $targetUser->roles()->attach($this->memberRole->id);

    $response = $this->actingAs($this->admin)->put(route('master-data.user.edit', [
        'context' => 'admin',
        'user' => $targetUser->id,
    ]), [
        'name' => 'Role Changer',
        'email' => 'changer@example.com',
        'roles' => [$this->adminRole->id],
    ]);

    $response->assertSessionHasNoErrors();
    $targetUser->refresh();

    // Roles updated to admin only
    expect($targetUser->roles->pluck('id')->toArray())->toEqualCanonicalizing([$this->adminRole->id]);

    // Password must remain original and not wiped out
    expect(Hash::check($originalPassword, $targetUser->password))->toBeTrue();
});

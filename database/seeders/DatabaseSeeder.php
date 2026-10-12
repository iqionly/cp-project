<?php

namespace Database\Seeders;

use App\Models\Authorizations\Role;
use App\Models\Event;
use App\Models\MasterData\Member;
use App\Models\Post;
use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::beginTransaction();

        $roleids = [];

        foreach([
            [ 'admin', 'admin' ],
            [ 'member', 'member' ],
        ] as $idx => $data) {
            $role = new Role();
            $role->id = ++$idx;
            $role->name = $data[0];
            $role->role_grouping = $data[1];
            $role->save();
            $roleids[] = $role->id;
        }

        $admin = new User();
        $admin->id = 1;
        $admin->name = 'admin';
        $admin->email = 'test@example.test';
        $admin->email_verified_at = date('Y-m-d H:i:s');
        $admin->password = Hash::make('password');
        $admin->save();
        $admin->roles()->attach([1]);
        $admin->save();

        $adminMember = new Member();
        $adminMember->id = 1;
        $adminMember->user()->associate($admin);
        $adminMember->member_code = "ID0001";
        $adminMember->first_name = "Admin";
        $adminMember->last_name = "Admin";
        $adminMember->gender = "M";
        $adminMember->birth_date = "2009-03-21";
        $adminMember->nik = "4364893101771821";
        $adminMember->plate_number = "D19181AC";
        $adminMember->phone_number = "081222335342";
        $adminMember->save();

        Member::factory(50)->create();
        Post::factory(50)->for(User::query()->find(1))->create();
        Event::factory(50)->for(Post::factory()->for(User::query()->find(1)))->create();

        DB::commit();
    }
}

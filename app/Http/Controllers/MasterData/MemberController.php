<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Authorizations\Role;
use App\Models\MasterData\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /**
     * Generate an unused, sequential member code like ID00001.
     */
    protected function generateMemberCode(): string
    {
        $lastMember = Member::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = ($lastMember ? $lastMember->id : 0) + 1;
        $code = 'ID'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);

        while (Member::withTrashed()->where('member_code', $code)->exists()) {
            $nextId++;
            $code = 'ID'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    public function index(Request $request)
    {
        return view('pages.masterdata.member_index', [
            'data' => Member::with('user')->paginate(
                $request->input('limit'),
                ['*'],
                'page',
                (int) $request->input('page', 1),
            ),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users,name'],
                'password' => ['required', 'string', 'min:6'],
                'member_code' => ['nullable', 'string', 'max:7', 'unique:members,member_code'],
            ]);

            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->input('username'),
                    'password' => Hash::make($request->input('password')),
                    'email' => null,
                ]);

                $memberRole = Role::where('name', 'member')->first() ?? Role::where('role_grouping', 'member')->first();
                if ($memberRole) {
                    $user->roles()->syncWithoutDetaching([$memberRole->id]);
                }

                $memberCode = $request->input('member_code') ?: $this->generateMemberCode();

                $member = new Member;
                $member->member_code = $memberCode;
                $member->user()->associate($user);
                $member->save();
            });

            return redirect()->route('master-data.member.list')
                ->with('success', 'Member account created successfully for username: '.$request->input('username').' with default password: '.$request->input('password'));
        }

        return view('pages.masterdata.member_create', [
            'defaultMemberCode' => $this->generateMemberCode(),
            'defaultPassword' => 'password',
        ]);
    }

    public function edit(Request $request, Member $member)
    {
        if ($request->isMethod('put')) {
            $data = $request->except([
                '_token',
                '_method',
                'member_code',
                'email',
            ]);

            if ($request->filled('email') && $member->user) {
                $request->validate([
                    'email' => ['email', 'unique:users,email,'.$member->user->id],
                ]);
                $member->user->email = $request->input('email');
                $member->user->save();
            }

            $member->fill($data);
            $member->save();

            return redirect()->back()->with('success', 'Member details updated successfully.');
        }

        return view('pages.masterdata.member_edit', [
            'data' => $member,
        ]);
    }

    public function delete(Request $request, Member $member)
    {
        if ($member->delete()) {
            return redirect()->back();
        }
    }
}

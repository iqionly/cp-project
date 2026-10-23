<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display and update the authenticated member's profile details.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $member = $user->member;

        if ($request->isMethod('post') || $request->isMethod('put')) {
            $memberId = $member ? $member->id : null;

            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255', 'unique:users,name,'.$user->id],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
                'password' => ['nullable', 'string', 'min:6', 'confirmed'],
                'first_name' => ['required', 'string', 'max:16'],
                'last_name' => ['required', 'string', 'max:16'],
                'gender' => ['required', 'in:M,F'],
                'birth_date' => ['required', 'date'],
                'phone_number' => ['required', 'string', 'max:18'],
                'address' => ['required', 'string'],
                'nik' => ['required', 'string', 'max:16', 'unique:members,nik,'.$memberId],
                'plate_number' => ['required', 'string', 'max:10'],
                'emergency_person' => ['nullable', 'string', 'max:32'],
                'emergency_phone_number' => ['nullable', 'string', 'max:18'],
            ]);

            DB::transaction(function () use ($request, $user, &$member) {
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->input('password'));
                }
                $user->save();

                if (! $member) {
                    $lastMember = Member::withTrashed()->orderBy('id', 'desc')->first();
                    $nextId = ($lastMember ? $lastMember->id : 0) + 1;
                    $memberCode = 'ID'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);

                    $member = new Member;
                    $member->member_code = $memberCode;
                    $member->user()->associate($user);
                }

                $member->first_name = $request->input('first_name');
                $member->last_name = $request->input('last_name');
                $member->gender = $request->input('gender');
                $member->birth_date = $request->input('birth_date');
                $member->phone_number = $request->input('phone_number');
                $member->address = $request->input('address');
                $member->nik = $request->input('nik');
                $member->plate_number = $request->input('plate_number');
                $member->emergency_person = $request->input('emergency_person');
                $member->emergency_phone_number = $request->input('emergency_phone_number');
                $member->save();
            });

            return redirect()->route('member.profile')
                ->with('success', 'Your profile details have been saved successfully.');
        }

        return view('pages.members.profile', [
            'user' => $user,
            'member' => $member,
            'isComplete' => $user->isProfileComplete(),
        ]);
    }
}

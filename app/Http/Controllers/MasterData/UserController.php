<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Authorizations\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.masterdata.user_index', [
            'data' => User::query()->paginate(
                $request->input('limit'),
                ['*'],
                'page',
                (int) $request->input('page', 1),
            ),
        ]);
    }

    public function edit(Request $request, User $user)
    {
        if ($request->isMethod('put')) {
            $request->validate([
                'name' => ['required', 'string'],
                'email' => ['required', 'email'],
                'roles' => ['nullable', 'array'],
                'roles.*' => ['exists:roles,id'],
                'old_password' => ['nullable', 'current_password:web', 'required_with:password'],
                'password' => ['nullable', 'confirmed', 'required_with:old_password'],
            ]);

            $data = $request->except([
                '_token',
                '_method',
                'old_password',
                'password',
                'password_confirmation',
                'verified',
                'roles',
            ]);

            $user->verify($request->has('verified'));

            if ($request->filled('password')) {
                $user->password = bcrypt($request->get('password'));
            }

            $user->fill($data);
            $user->save();

            $user->roles()->sync($request->input('roles', []));

            session()->flash('success', 'Data user dan role berhasil diperbarui.');
        }

        $user->load('roles');
        $roles = Role::orderBy('name')->get();

        return view('pages.masterdata.user_edit', [
            'data' => $user,
            'roles' => $roles,
            'userRoleIds' => $user->roles->pluck('id')->toArray(),
        ]);
    }

    public function delete(Request $request, User $user)
    {
        if ($user->delete()) {
            return redirect()->back();
        }
    }
}

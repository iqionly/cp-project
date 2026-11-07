<?php

namespace App\Http\Controllers\Securities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login_page(Request $request)
    {
        if(Auth::check()) {
            $user = Auth::user();
            $role = $user->roles->first();

            if(!$user->isProfileComplete() && $request->attributes->get('context') != 'admin' && !$user->roles()->where('role_grouping', 'admin')->exists()) {
                return redirect(route('member.profile', ['context' => $role->role_grouping]))->with('warning', 'Please complete your details info (email, address, and member information) to continue.');
            }

            return redirect()->to('/' . $role->role_grouping . '/home');
        }
        return view('securities.page_login');
    }

    public function authentication(Request $request)
    {
        $credentials = $request->validate([
            'user' => ['string'],
            'pin' => ['string'],
        ]);

        // if($credentials['user'])
        if ($email = filter_var($credentials['user'], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $email;
        } else {
            $credentials['name'] = $credentials['user'];
        }
        $credentials['password'] = $credentials['pin'];
        unset($credentials['user']);
        unset($credentials['pin']);

        if ($user = Auth::attempt($credentials, $request->get('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $role = $user->roles->first();

            if(!$user->isProfileComplete() && $request->attributes->get('context') != 'admin' && !$user->roles()->where('role_grouping', 'admin')->exists()) {
                return redirect(route('member.profile', ['context' => $role->role_grouping]))->with('warning', 'Please complete your details info (email, address, and member information) to continue.');
            }

            return redirect()->intended('/'.$role->role_grouping.'/home');
        }

        return back()->withErrors([
            'user' => 'The provided credentials do not match our records.',
        ])->onlyInput('user');
    }

    public function deauthentication(Request $request)
    {
        Auth::logout();

        return redirect()->route('auth.login');
    }
}

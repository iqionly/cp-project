<?php

namespace App\Http\Controllers\Securities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{

    public function login_page(Request $request)
    {
        return view('securities.page_login');
    }

    public function authentication(Request $request)
    {
        $credentials = $request->validate([
            'user' => ['string'],
            'pin' => ['string']
        ]);

        // if($credentials['user'])
        if($email = filter_var($credentials['user'], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $email;
        } else {
            $credentials['name'] = $credentials['user'];
        }
        $credentials['password'] = $credentials['pin'];
        unset($credentials['user']);
        unset($credentials['pin']);

        if(Auth::attempt($credentials, $request->get('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended();
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

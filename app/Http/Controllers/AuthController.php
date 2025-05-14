<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($credentials['name'] === 'adminsb' && $credentials['password'] === 'adminsb#25') {
            session(['authenticated' => true]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'name' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        session()->forget('authenticated');
        return redirect()->route('login');
    }
}

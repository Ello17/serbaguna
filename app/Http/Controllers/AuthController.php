<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'Login successful');
        }

        return redirect()->back()->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}

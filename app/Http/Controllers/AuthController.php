<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'senha');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['senha']])) {
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }
}

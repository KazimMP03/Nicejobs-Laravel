<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        return view('auth.login');
    }

    /**
     * Processa a tentativa de login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.'
        ]);

        $user = Provider::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email não cadastrado']);
        }

        if (Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
            
            return redirect()->route('home')
                ->with('success', 'Login realizado com sucesso!');
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Credenciais inválidas. Verifique seu e-mail e senha.',
            ]);
    }

    /**
     * Processa o logout do usuário
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('status', 'Você saiu da sua conta com sucesso.');
    }
}
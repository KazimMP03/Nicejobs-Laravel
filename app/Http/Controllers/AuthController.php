<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\CustomUser;
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
        // Se qualquer um dos guards já estiver logado, redireciona
        if (Auth::guard('web')->check() || Auth::guard('custom')->check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Processa a tentativa de login
     */
    public function login(Request $request)
    {
        // 1) validação
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required'    => 'O campo E-mail é obrigatório.',
            'email.email'       => 'Por favor, insira um E-mail válido.',
            'password.required' => 'O campo Senha é obrigatório.',
            'password.min'      => 'A senha deve ter pelo menos 8 caracteres.',
        ]);

        // 2) tenta achar primeiro na tabela providers
        $user  = Provider::where('email', $credentials['email'])->first();
        $guard = 'web';

        // 3) se não achar, tenta na tabela custom_users
        if (! $user) {
            $user  = CustomUser::where('email', $credentials['email'])->first();
            $guard = 'custom';
        }

        // 4) se não encontrar em nenhum, erro de e-mail
        if (! $user) {
            return back()->withErrors([
                'email' => 'E-mail não cadastrado.'
            ]);
        }

        // 5) verifica senha e faz login com o guard correspondente
        if (Hash::check($credentials['password'], $user->password)) {
            Auth::guard($guard)->login($user, $request->filled('remember'));
            $request->session()->regenerate();

            // Define o tipo de usuário na sessão
            $request->session()->put('user_type', $guard === 'web' ? 'provider' : 'custom_user');

            // Redireciona de acordo com o tipo
            if ($guard === 'web') {
                return redirect()->route('provider.home')
                    ->with('success', 'Login realizado com sucesso!');
            }

            if ($guard === 'custom') {
                return redirect()->route('custom-user.home')
                    ->with('success', 'Login realizado com sucesso!');
            }
        }

        // 6) credenciais inválidas
        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Credenciais inválidas. Verifique seu e-mail e senha.',
            ]);
    }

    /**
     * Processa o logout do usuário (tanto provider quanto custom)
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('custom')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Você saiu da conta com sucesso.');
    }
}

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
        // Verifica se o usuário já está autenticado
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // Retorna a view de login
        return view('auth.login');
    }

    /**
     * Processa o login do usuário
     * @param Request $request
     */
    public function login(Request $request)
    {
        // Verifica se o usuário já está autenticado
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ], [ // Customizando mensagens de erro
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.'
        ]);

        // Verifica se o usuário já está autenticado
        $user = Provider::where('email', $credentials['email'])->first();

        // Se o usuário não for encontrado, retorna erro
        if (!$user) {
            // Retorna para a view anterior com erro
            return back()->withErrors(['email' => 'Email não cadastrado']);
        }

        // Verifica se a senha informada é válida
        if (Hash::check($credentials['password'], $user->password)) {
            // Se a senha for válida, autentica o usuário
            Auth::login($user, $request->filled('remember'));
            // Regenera o token da sessão para segurança
            $request->session()->regenerate();

            // Redireciona para a página inicial com mensagem de sucesso
            return redirect()->route('home')->with('success', 'Login realizado com sucesso!');
        }

        // Se a senha for inválida, retorna erro
        return back()->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Credenciais inválidas. Verifique seu e-mail e senha.',
            ]);
    }

    /**
     * Processa o logout do usuário
     * @param Request $request
     */
    public function logout(Request $request)
    {
        // Verifica se o usuário está autenticado
        Auth::logout();
        // Invalida a sessão do usuário 
        $request->session()->invalidate();
        // Regenera o token da sessão para segurança
        $request->session()->regenerateToken();

        // Redireciona para a página de login com mensagem de sucesso
        return redirect()->route('login')->with('status', 'Você saiu da sua conta com sucesso.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use App\Models\Provider;
use App\Models\CustomUser;
use App\Mail\ResetPasswordMail;

class PasswordResetController extends Controller
{
    // Exibe o formulário de recuperação de senha
    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    // Envia o e-mail com o link de recuperação
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'Digite um e-mail válido.',
        ]);

        $email = $request->email;

        // Verifica se existe o e-mail em uma das tabelas
        $user = Provider::where('email', $email)->first();
        $guard = 'web';

        if (!$user) {
            $user = CustomUser::where('email', $email)->first();
            $guard = 'custom';
        }

        if (!$user) {
            return back()->withErrors(['email' => 'E-mail não cadastrado.'])->withInput();
        }

        // Gera token
        $token = Str::random(64);

        // Remove tokens antigos
        DB::table('password_resets')->where('email', $email)->delete();

        // Salva novo token
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Envia o e-mail com o link de redefinição
        Mail::to($email)->send(new ResetPasswordMail($token));

        return back()->with('success', 'Enviamos um link para redefinir sua senha por e-mail.');
    }

    // Exibe o formulário de nova senha
    public function showResetForm($token)
    {
        return view('auth.reset', ['token' => $token]);
    }

    // Atualiza a senha no banco de dados
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token'    => 'required'
        ], [
            'email.required'    => 'O campo e-mail é obrigatório.',
            'email.email'       => 'Digite um e-mail válido.',
            'password.required' => 'Digite a nova senha.',
            'password.min'      => 'A nova senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Token inválido ou expirado.']);
        }

        // Verifica se o usuário é provider ou custom_user
        $user = Provider::where('email', $request->email)->first();
        $table = 'providers';

        if (!$user) {
            $user = CustomUser::where('email', $request->email)->first();
            $table = 'custom_users';
        }

        if (!$user) {
            return back()->withErrors(['email' => 'Usuário não encontrado.']);
        }

        // Verifica se a nova senha é diferente da antiga
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'A nova senha não pode ser igual à anterior.']);
        }

        // Atualiza a senha
        $user->password = Hash::make($request->password);
        $user->save();

        // Remove o token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Senha redefinida com sucesso! Faça login com a nova senha.');
    }
}

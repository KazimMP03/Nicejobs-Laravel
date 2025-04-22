<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * Exibe o formulário de cadastro de usuário
     */
    public function showCustomUserRegistrationForm()
    {
        // Retorna a view de cadastro de usuário
        return view('auth.register-custom-user');
    }

    /**
     * Exibe o formulário de cadastro de prestador de serviços
     */
    public function showProviderRegistrationForm()
    {
        // Retorna a view de cadastro de prestador de serviços
        return view('auth.register-provider');
    }
}
<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial
     */
    public function index()
    {
        // Verifica se o usuário já está autenticado
        if (auth()->check()) {
            // Se o usuário estiver autenticado, redireciona para a página inicial
            return redirect()->route('home');
        }
        // Se o usuário não estiver autenticado, redireciona para a página de login
        return redirect()->route('login');
    }
}
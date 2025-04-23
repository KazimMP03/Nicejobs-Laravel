<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showCustomUserRegistrationForm()
    {
        return view('auth.register-custom-user');
    }

    public function showProviderRegistrationForm()
    {
        return view('auth.register-provider');
    }
}
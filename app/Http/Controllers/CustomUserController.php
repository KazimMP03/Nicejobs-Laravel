<?php

namespace App\Http\Controllers;

use App\Models\CustomUser;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CustomUserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_type' => 'required|in:PF,PJ',
            'tax_id' => 'required|string|unique:custom_users,tax_id',
            'email' => 'required|email|unique:custom_users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date' => 'nullable|date|required_if:user_type,PF',
            'foundation_date' => 'nullable|date|required_if:user_type,PJ',
            'status' => 'required|boolean',
            'addresses' => 'nullable|array',
            'addresses.*.cep' => 'required|string',
            'addresses.*.logradouro' => 'required|string',
            'addresses.*.bairro' => 'required|string',
            'addresses.*.numero' => 'required|string',
            'addresses.*.cidade' => 'required|string',
            'addresses.*.estado' => 'required|string',
            'addresses.*.complemento' => 'nullable|string',
        ], [
            'birth_date.required_if' => 'A data de nascimento é obrigatória para pessoa física',
            'foundation_date.required_if' => 'A data de fundação é obrigatória para pessoa jurídica',
            'tax_id.unique' => 'Este CPF/CNPJ já está cadastrado',
            'email.unique' => 'Este e-mail já está em uso'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        
        // Criptografa a senha antes de armazenar
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);

        // Upload da foto de perfil
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $data['profile_photo'] = $path;
        }

        $customUser = CustomUser::create($data);

        // Associa os endereços, se fornecidos
        if (!empty($data['addresses'])) {
            foreach ($data['addresses'] as $addressData) {
                $address = Address::create($addressData);
                $customUser->addresses()->attach($address->id);
            }
        }

        return redirect()
            ->route('login')
            ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }
}
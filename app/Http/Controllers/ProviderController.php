<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProviderController extends Controller
{
    public function store(Request $request)
    {
        // Converter a string de disponibilidade para JSON
        $request->merge([
            'availability' => $this->convertAvailabilityToJson($request->availability),
            'status' => $request->has('status') ? filter_var($request->status, FILTER_VALIDATE_BOOLEAN) : true
        ]);

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_type' => 'required|in:PF,PJ',
            'tax_id' => 'required|string|unique:providers,tax_id',
            'email' => 'required|email|unique:providers,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date' => 'nullable|date|required_if:user_type,PF',
            'foundation_date' => 'nullable|date|required_if:user_type,PJ',
            'status' => 'required|boolean',
            'provider_description' => 'required|string',
            'service_category' => 'required|string',
            'service_description' => 'required|string',
            'work_radius' => 'required|integer|min:1',
            'availability' => 'required|json',
            'addresses' => 'nullable|array',
            'addresses.*.cep' => 'required|string',
            'addresses.*.logradouro' => 'required|string',
            'addresses.*.bairro' => 'required|string',
            'addresses.*.numero' => 'required|string',
            'addresses.*.cidade' => 'required|string',
            'addresses.*.estado' => 'required|string',
            'addresses.*.complemento' => 'nullable|string',
        ], [
            'availability.json' => 'O campo disponibilidade deve ser um JSON válido',
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

        $provider = Provider::create($data);

        // Associa os endereços, se fornecidos
        if (!empty($data['addresses'])) {
            foreach ($data['addresses'] as $addressData) {
                $address = Address::create($addressData);
                $provider->addresses()->attach($address->id);
            }
        }

        return redirect()
            ->route('login')
            ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    protected function convertAvailabilityToJson($availability)
    {
        if (is_array($availability)) {
            return json_encode($availability);
        }

        if (is_string($availability) && json_decode($availability) !== null) {
            return $availability;
        }

        return json_encode([
            'weekdays' => true,
            'weekends' => false,
            'morning' => true,
            'afternoon' => true,
            'night' => false
        ]);
    }
}
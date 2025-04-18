<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_type' => 'required|in:PF,PJ',
            'tax_id' => 'required|string|unique:providers,tax_id',
            'email' => 'required|email|unique:providers,email',
            'phone' => 'required|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date' => 'nullable|date',
            'foundation_date' => 'nullable|date',
            'status' => 'required|boolean',
            'provider_description' => 'required|string',
            'service_category' => 'required|string',
            'service_description' => 'required|string',
            'work_radius' => 'required|integer',
            'availability' => 'required|json',
            'addresses' => 'nullable|array',
            'addresses.*.cep' => 'required|string',
            'addresses.*.logradouro' => 'required|string',
            'addresses.*.bairro' => 'required|string',
            'addresses.*.numero' => 'required|string',
            'addresses.*.cidade' => 'required|string',
            'addresses.*.estado' => 'required|string',
            'addresses.*.complemento' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();

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

        return response()->json($provider, 201);
    }
}

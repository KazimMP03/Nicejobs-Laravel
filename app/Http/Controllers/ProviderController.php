<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProviderController extends Controller
{
    /**
     * Cria um novo Provider e associa endereços,
     * definindo o primeiro como padrão.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1) Validação dos dados do prestador e endereços
        $validator = Validator::make($request->all(), [
            'user_name'            => 'required|string|max:255',
            'user_type'            => 'required|in:PF,PJ',
            'tax_id'               => 'required|string|unique:providers,tax_id',
            'email'                => 'required|email|unique:providers,email',
            'password'             => 'required|string|min:8|confirmed',
            'phone'                => 'required|string',
            'profile_photo'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date'           => 'nullable|date|required_if:user_type,PF',
            'foundation_date'      => 'nullable|date|required_if:user_type,PJ',
            'status'               => 'required|boolean',
            'provider_description' => 'required|string',
            'work_radius'          => 'required|integer|min:1',
            'availability'         => ['required', Rule::in(['weekdays','weekends','both'])],
            'addresses'            => 'nullable|array',
            'addresses.*.cep'         => 'required|string|max:9',
            'addresses.*.logradouro'  => 'required|string|max:255',
            'addresses.*.bairro'      => 'required|string|max:255',
            'addresses.*.numero'      => 'required|string|max:20',
            'addresses.*.cidade'      => 'required|string|max:255',
            'addresses.*.estado'      => 'required|string|max:2',
            'addresses.*.complemento' => 'nullable|string|max:255',
        ], [
            'availability.in'            => 'Disponibilidade inválida. Escolha “weekdays”, “weekends” ou “both”.',
            'birth_date.required_if'     => 'A data de nascimento é obrigatória para PF.',
            'foundation_date.required_if'=> 'A data de fundação é obrigatória para PJ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // 2) Preparar dados
        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);

        // 3) Upload de foto, se houver
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request
                ->file('profile_photo')
                ->store('profile_photos', 'public');
        }

        // 4) Cria o prestador
        $provider = Provider::create($data);

        // 5) Associa endereços e marca o primeiro como padrão
        if (! empty($data['addresses'])) {
            foreach ($data['addresses'] as $i => $addrData) {
                $address = Address::create($addrData);
                $provider->addresses()->attach(
                    $address->id,
                    ['is_default' => ($i === 0)]
                );
            }
        }

        // 6) Redireciona ao login
        return redirect()->route('login')
                         ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }
}


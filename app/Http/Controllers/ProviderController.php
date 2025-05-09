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
     * Armazena um novo usuário do tipo "Provider" (prestador de serviço).
     * Valida os dados, cria o usuário, salva a imagem de perfil (opcional),
     * associa os endereços fornecidos e define o primeiro como padrão.
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados do prestador e endereços vinculados
        $validator = Validator::make($request->all(), [
            'user_name'            => 'required|string|max:255',
            'user_type'            => 'required|in:PF,PJ',
            'tax_id'               => 'required|string|unique:providers,tax_id',
            'email'                => 'required|email|unique:providers,email',
            'password'             => 'required|string|min:8|confirmed',
            'phone'                => 'required|string|min:10|max:11',

            'profile_photo'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'birth_date'           => 'nullable|date|required_if:user_type,PF',
            'foundation_date'      => 'nullable|date|required_if:user_type,PJ',

            'status'               => 'required|boolean',
            'provider_description' => 'required|string',
            'work_radius'          => 'required|integer|min:1',

            'availability'         => ['required', Rule::in(['weekdays', 'weekends', 'both'])],

            // Endereços vinculados (se fornecidos)
            'addresses'                => 'nullable|array',
            'addresses.*.cep'          => 'required|string|max:9',
            'addresses.*.logradouro'   => 'required|string|max:255',
            'addresses.*.bairro'       => 'required|string|max:255',
            'addresses.*.numero'       => 'required|string|max:20',
            'addresses.*.cidade'       => 'required|string|max:255',
            'addresses.*.estado'       => 'required|string|max:2',
            'addresses.*.complemento'  => 'nullable|string|max:255',
        ], [
            // Mensagens customizadas
            'availability.in'             => 'Disponibilidade inválida. Escolha “weekdays”, “weekends” ou “both”.',
            'birth_date.required_if'      => 'A data de nascimento é obrigatória para PF.',
            'foundation_date.required_if' => 'A data de fundação é obrigatória para PJ.',
            'phone.min'                   => 'O telefone deve ter no mínimo 10 dígitos.',
            'phone.max'                   => 'O telefone deve ter no máximo 11 dígitos.',
        ]);

        // Retorna com erros se a validação falhar
        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // 2. Preparação dos dados validados para persistência
        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']); // segurança

        // 3. Armazenamento da imagem de perfil (se enviada)
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request
                ->file('profile_photo')
                ->store('profile_photos', 'public');
        }

        // 4. Criação do prestador no banco
        $provider = Provider::create($data);

        // 5. Associa os endereços fornecidos ao prestador (marcando o primeiro como padrão)
        if (!empty($data['addresses'])) {
            foreach ($data['addresses'] as $index => $addressData) {
                $address = Address::create($addressData);
                $provider->addresses()->attach($address->id, [
                    'is_default' => ($index === 0),
                ]);
            }
        }

        // 6. Redirecionamento com feedback
        return redirect()->route('login')
                         ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }
}

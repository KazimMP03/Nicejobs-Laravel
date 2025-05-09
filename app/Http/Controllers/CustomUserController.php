<?php

namespace App\Http\Controllers;

use App\Models\CustomUser;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomUserController extends Controller
{
    /**
     * Armazena um novo CustomUser (cliente) no sistema.
     * Cria o usuário, salva endereços e marca o primeiro como padrão.
     */
    public function store(Request $request)
    {
        // 1) Validação dos dados do cliente e endereços
        $validator = Validator::make($request->all(), [
            'user_name'       => 'required|string|max:255',
            'user_type'       => 'required|in:PF,PJ',
            'tax_id'          => 'required|string|unique:custom_users,tax_id',
            'email'           => 'required|email|unique:custom_users,email',
            'password'        => 'required|string|min:8|confirmed',
            'phone'           => 'required|string|min:10|max:11',

            'birth_date'      => 'nullable|date|required_if:user_type,PF',
            'foundation_date' => 'nullable|date|required_if:user_type,PJ',

            'availability'    => ['required', Rule::in(['weekdays', 'weekends', 'both'])],
            'terms'           => 'required|accepted',

            'addresses'                => 'nullable|array',
            'addresses.*.cep'          => 'required|string|max:9',
            'addresses.*.logradouro'   => 'required|string|max:255',
            'addresses.*.bairro'       => 'required|string|max:255',
            'addresses.*.numero'       => 'required|string|max:20',
            'addresses.*.cidade'       => 'required|string|max:255',
            'addresses.*.estado'       => 'required|string|max:2',
            'addresses.*.complemento'  => 'nullable|string|max:255',
        ], [
            'birth_date.required_if'       => 'A data de nascimento é obrigatória para PF.',
            'foundation_date.required_if'  => 'A data de fundação é obrigatória para PJ.',
            'tax_id.unique'                => 'Este CPF/CNPJ já está cadastrado.',
            'email.unique'                 => 'Este e-mail já está em uso.',
            'terms.required'               => 'Você deve aceitar os termos de serviço.',
            'availability.in'              => 'Disponibilidade inválida. Use: weekdays, weekends ou both.',
            'phone.min'                    => 'O telefone deve ter no mínimo 10 dígitos.',
            'phone.max'                    => 'O telefone deve ter no máximo 11 dígitos.',
        ]);

        // Retorna com erros se a validação falhar
        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // 2) Preparar dados
        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        $data['status'] = true;
        unset($data['password_confirmation'], $data['terms']);

        // 3) Cria o cliente
        $customUser = CustomUser::create($data);

        // 4) Associa endereços e marca o primeiro como padrão
        if (!empty($data['addresses'])) {
            foreach ($data['addresses'] as $index => $addrData) {
                $address = Address::create($addrData);
                $customUser->addresses()->attach($address->id, [
                    'is_default' => ($index === 0),
                ]);
            }
        }

        // 5) Redireciona ao login
        return redirect()->route('login')
                         ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }
}

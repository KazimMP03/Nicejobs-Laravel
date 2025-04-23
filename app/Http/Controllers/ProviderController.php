<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        // 1) Converte disponibilidade para JSON e força status boolean
        $request->merge([
            'availability' => $this->convertAvailabilityToJson($request->availability),
            'status'       => $request->has('status')
                                ? filter_var($request->status, FILTER_VALIDATE_BOOLEAN)
                                : true,
        ]);

        // 2) Validação dos dados do prestador e endereços
        $validator = Validator::make($request->all(), [
            'user_name'           => 'required|string|max:255',
            'user_type'           => 'required|in:PF,PJ',
            'tax_id'              => 'required|string|unique:providers,tax_id',
            'email'               => 'required|email|unique:providers,email',
            'password'            => 'required|string|min:8|confirmed',
            'phone'               => 'required|string',
            'profile_photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date'          => 'nullable|date|required_if:user_type,PF',
            'foundation_date'     => 'nullable|date|required_if:user_type,PJ',
            'status'              => 'required|boolean',
            'provider_description'=> 'required|string',
            'service_category'    => 'required|string',
            'service_description' => 'required|string',
            'work_radius'         => 'required|integer|min:1',
            'availability'        => 'required|json',
            'addresses'           => 'nullable|array',
            'addresses.*.cep'         => 'required|string|max:9',
            'addresses.*.logradouro'  => 'required|string|max:255',
            'addresses.*.bairro'      => 'required|string|max:255',
            'addresses.*.numero'      => 'required|string|max:20',
            'addresses.*.cidade'      => 'required|string|max:255',
            'addresses.*.estado'      => 'required|string|max:2',
            'addresses.*.complemento' => 'nullable|string|max:255',
        ], [
            'birth_date.required_if'      => 'A data de nascimento é obrigatória para PF.',
            'foundation_date.required_if' => 'A data de fundação é obrigatória para PJ.',
            'availability.json'           => 'Disponibilidade deve ser um JSON válido.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $data = $validator->validated();

        // 3) Criptografa a senha e remove confirmação
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);

        // 4) Upload de foto, se houver
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request
                ->file('profile_photo')
                ->store('profile_photos', 'public');
        }

        // 5) Cria o prestador
        $provider = Provider::create($data);

        // 6) Associa endereços e marca o primeiro como padrão
        if (! empty($data['addresses'])) {
            foreach ($data['addresses'] as $i => $addrData) {
                $address = Address::create($addrData);
                $provider->addresses()->attach(
                    $address->id,
                    ['is_default' => ($i === 0)]
                );
            }
        }

        return redirect()->route('login')
                         ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    /**
     * Converte a disponibilidade em array/string para JSON válido.
     *
     * @param  mixed  $availability
     * @return string
     */
    protected function convertAvailabilityToJson($availability)
    {
        if (is_array($availability)) {
            return json_encode($availability);
        }

        if (is_string($availability) && json_decode($availability) !== null) {
            return $availability;
        }

        // Padrão se vier inválido
        return json_encode([
            'weekdays'  => true,
            'weekends'  => false,
            'morning'   => true,
            'afternoon' => true,
            'night'     => false,
        ]);
    }
}

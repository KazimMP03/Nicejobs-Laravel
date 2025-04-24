<?php

namespace App\Http\Controllers;

use App\Models\CustomUser;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomUserController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Remover máscaras antes da validação
            $request->merge([
                'tax_id' => preg_replace('/[^0-9]/', '', $request->tax_id),
                'phone' => preg_replace('/[^0-9]/', '', $request->phone)
            ]);

            $validator = Validator::make($request->all(), [
                'user_name' => 'required|string|max:255',
                'user_type' => 'required|in:PF,PJ',
                'tax_id' => 'required|string|unique:custom_users,tax_id',
                'email' => 'required|email|unique:custom_users,email',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|min:10|max:11',
                'birth_date' => 'required_if:user_type,PF|date|nullable',
                'foundation_date' => 'required_if:user_type,PJ|date|nullable',
                'terms' => 'required|accepted',
                'addresses' => 'nullable|array',
                'addresses.*.cep' => 'required|string|max:9',
                'addresses.*.logradouro' => 'required|string|max:255',
                'addresses.*.bairro' => 'required|string|max:255',
                'addresses.*.numero' => 'required|string|max:20',
                'addresses.*.cidade' => 'required|string|max:255',
                'addresses.*.estado' => 'required|string|max:2',
                'addresses.*.complemento' => 'nullable|string|max:255',
            ], [
                'birth_date.required_if' => 'A data de nascimento é obrigatória para pessoa física',
                'foundation_date.required_if' => 'A data de fundação é obrigatória para pessoa jurídica',
                'tax_id.unique' => 'Este CPF/CNPJ já está cadastrado',
                'email.unique' => 'Este e-mail já está em uso',
                'terms.required' => 'Você deve aceitar os termos de serviço',
                'phone.min' => 'O telefone deve ter no mínimo 10 dígitos',
                'phone.max' => 'O telefone deve ter no máximo 11 dígitos'
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $validator->validated();

            // Preparar dados para criação
            $data['password'] = Hash::make($data['password']);
            $data['status'] = true;
            $data['availability'] = json_encode([]);
            
            unset(
                $data['password_confirmation'],
                $data['terms'],
                $data['addresses']
            );

            // Criar usuário
            $customUser = CustomUser::create($data);

            // Adicionar endereços
            if ($request->has('addresses')) {
                foreach ($request->addresses as $index => $addressData) {
                    $address = Address::create($addressData);
                    $customUser->addresses()->attach($address->id, [
                        'is_default' => ($index === 0)
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('login')
                ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Erro ao processar cadastro: ' . $e->getMessage())
                ->withInput();
        }
    }
}
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
            'phone'           => 'required|string',

            'birth_date'      => 'nullable|date|required_if:user_type,PF',
            'foundation_date' => 'nullable|date|required_if:user_type,PJ',   
            
            'status' => 'required|boolean',
        ], [
            'birth_date.required_if'       => 'A data de nascimento é obrigatória para PF.',
            'foundation_date.required_if'  => 'A data de fundação é obrigatória para PJ.',
            'tax_id.unique'                => 'Este CPF/CNPJ já está cadastrado.',
            'email.unique'                 => 'Este e-mail já está em uso.',
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
        CustomUser::create($data);

        // 4) Redireciona ao login
        return redirect()->route('login')
                         ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    /**
     * Exibe a página de edição de perfil (foto e informações gerais)
     */
    public function editProfile()
    {
        $customUser = auth()->user();

        if (!($customUser instanceof CustomUser)) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('custom_users.edit', compact('customUser'));
    }

    /**
     * Atualiza as informações gerais do CustomUser (que podem ser atualizadas)
     */
    public function updateProfile(Request $request)
    {
        $customUser = auth()->user();

        if (!($customUser instanceof CustomUser)) {
            abort(403, 'Acesso não autorizado.');
        }

        $data = $request->validate([
            'user_name' => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
        ]);

        $customUser->update($data);

        return redirect()->route('custom-user.profile.show')
                        ->with('success', 'Perfil atualizado com sucesso.');
    }
}

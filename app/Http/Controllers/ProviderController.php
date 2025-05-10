<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Address;
use App\Models\ServiceCategory;
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
            'user_name' => 'required|string|max:255',
            'user_type' => 'required|in:PF,PJ',
            'tax_id' => 'required|string|unique:providers,tax_id',
            'email' => 'required|email|unique:providers,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string',

            'birth_date' => 'nullable|date|required_if:user_type,PF',
            'foundation_date' => 'nullable|date|required_if:user_type,PJ',

            'status' => 'required|boolean',
            'provider_description' => 'required|string',
            'work_radius' => 'required|integer|min:1',

            'availability' => ['required', Rule::in(['weekdays', 'weekends', 'both'])],
        ], [
            // Mensagens customizadas
            'availability.in' => 'Disponibilidade inválida. Escolha “weekdays”, “weekends” ou “both”.',
            'birth_date.required_if' => 'A data de nascimento é obrigatória para PF.',
            'foundation_date.required_if' => 'A data de fundação é obrigatória para PJ.',
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

        // 3. Criação do prestador no banco
        Provider::create($data);

        // 4. Redirecionamento com feedback
        return redirect()->route('login')
            ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    /**
     * Exibe o formulário para atualizar a foto de perfil do Provider.
     */
    public function editProfile()
    {
        $provider = auth()->user();

        if (!($provider instanceof Provider)) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('providers.edit-profile', compact('provider'));
    }

    /**
     * Atualiza a foto de perfil do Provider autenticado.
     */
    public function updateProfilePhoto(Request $request)
    {
        $provider = auth()->user();

        if (!($provider instanceof Provider)) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('profile_photo')->store('profile_photos', 'public');

        $provider->update(['profile_photo' => $path]);

        return redirect()->route('provider.profile.edit')->with('success', 'Foto de perfil atualizada com sucesso.');
    }


    /**
     * Exibe o formulário para que o Provider selecione suas categorias de serviço.
     * A funcionalidade é exclusiva para usuários autenticados como Provider.
     */
    public function editCategories(Request $request)
    {
        $provider = auth()->user();

        // Impedir acesso de outros tipos de usuário
        if (!($provider instanceof Provider)) {
            abort(403, 'Acesso não autorizado.');
        }

        $categories = ServiceCategory::all();

        return view('providers.edit-categories', compact('provider', 'categories'));
    }

    /**
     * Atualiza as categorias associadas ao Provider autenticado.
     * Apenas associa ou desassocia categorias existentes.
     */
    public function updateCategories(Request $request)
    {
        $provider = auth()->user();

        if (!($provider instanceof Provider)) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:service_categories,id',
        ]);

        $provider->categories()->sync($request->categories);

        return redirect()->route('home')->with('success', 'Categorias atualizadas com sucesso!');
    }
}

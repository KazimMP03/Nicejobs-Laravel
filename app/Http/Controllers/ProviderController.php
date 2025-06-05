<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProviderController extends Controller
{
    /**
     * Cadastro de um novo Provider (PF ou PJ).
     */
    public function store(Request $request)
    {
        // 1) Validação inicial (apenas na tabela providers)
        $data = $request->validate([
            'user_name'        => 'required|string|max:255',
            'user_type'        => 'required|in:PF,PJ',
            'tax_id'           => 'required|string|unique:providers,tax_id',
            'email'            => 'required|email|unique:providers,email',
            'password'         => 'required|string|min:8|confirmed',
            'phone'            => 'required|string',

            'birth_date'       => 'nullable|date|required_if:user_type,PF',
            'foundation_date'  => 'nullable|date|required_if:user_type,PJ',

            'status'           => 'required|boolean',
            'provider_description' => 'required|string',
            'work_radius'      => 'required|integer|min:1',

            'availability'     => ['required', Rule::in(['weekdays', 'weekends', 'both'])],
        ], [
            'birth_date.required_if'       => 'A data de nascimento é obrigatória para PF.',
            'foundation_date.required_if'  => 'A data de fundação é obrigatória para PJ.',
            'tax_id.unique'                => 'Este CPF/CNPJ já está cadastrado.',
            'email.unique'                 => 'Este e-mail já está em uso.',
        ]);

        $errors = [];

        // Verifica se o e-mail já está em uso por outro usuário
        if (\App\Models\CustomUser::where('email', $data['email'])->exists()) {
            $errors['email'] = 'Este e-mail já está em uso.';
        }

        // Verifica se o CPF/CNPJ já está em uso por outro usuário
        if (\App\Models\CustomUser::where('tax_id', $data['tax_id'])->exists()) {
            $errors['tax_id'] = 'Este CPF/CNPJ já está cadastrado.';
        }

        // Se houver qualquer erro, retorna com todos
        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // 3) Hash da senha
        $data['password'] = Hash::make($data['password']);

        // 4) Criação do provider
        Provider::create($data);

        return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso! Faça login.');
    }

    /**
     * Exibe a página de edição do perfil do Provider (foto e informações gerais).
     */
    public function editProfile()
    {
        $provider = auth()->user();

        if (!($provider instanceof Provider)) {
            abort(403, 'Acesso não autorizado.');
        }

        $categories = ServiceCategory::all();

        return view('providers.edit', compact('provider'));
    }

    /**
     * Atualiza as informações gerais do Provider (descrição, raio, disponibilidade).
     */
    public function updateInfo(Request $request)
    {
        $provider = auth()->user();

        $data = $request->validate([
            'user_name' => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'provider_description' => 'nullable|string|max:1000',
            'work_radius'          => 'required|integer|min:1',
            'availability'         => 'required|in:weekdays,weekends,both',
        ]);

        $provider->update($data);

        return redirect()->route('provider.profile.show')->with('success', 'Informações atualizadas com sucesso.');
    }

    /**
     * Atualiza a foto de perfil do Provider.
     */
    public function updateProfilePhoto(Request $request)
    {
        $provider = auth()->user();

        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('profile_photo')->store('provider_photos', 'public');

        $provider->update(['profile_photo' => $path]);

        return redirect()->route('provider.profile.edit')->with('success', 'Foto de perfil atualizada com sucesso.');
    }

    /**
     * Atualiza as categorias associadas ao Provider, limitado a 3.
     */
    public function updateCategories(Request $request)
    {
        $provider = auth()->user();

        $data = $request->validate([
            'categories'   => 'required|array|max:3',
            'categories.*' => 'exists:service_categories,id',
        ]);

        $provider->categories()->sync($data['categories']);

        return redirect()->route('provider.profile.edit')->with('success', 'Categorias atualizadas com sucesso.');
    }
}

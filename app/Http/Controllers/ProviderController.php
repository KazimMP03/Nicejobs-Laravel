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
        ]);

        $data['password'] = Hash::make($data['password']);

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

        return view('providers.edit-profile', compact('provider', 'categories'));
    }

    /**
     * Atualiza as informações gerais do Provider (descrição, raio, disponibilidade).
     */
    public function updateInfo(Request $request)
    {
        $provider = auth()->user();

        $data = $request->validate([
            'provider_description' => 'nullable|string|max:1000',
            'work_radius'          => 'required|integer|min:1',
            'availability'         => 'required|in:weekdays,weekends,both',
        ]);

        $provider->update($data);

        return redirect()->route('provider.profile.edit')->with('success', 'Informações atualizadas com sucesso.');
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
     * Exibe o formulário de seleção de categorias do Provider.
     */
    public function showCategories()
    {
        $provider = auth()->user();

        $categories = ServiceCategory::all();

        return view('providers.show-categories', compact('provider', 'categories'));
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

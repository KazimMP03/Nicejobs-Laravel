<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Exibe a lista de endereços do usuário logado.
     */
    public function index()
    {
        // Usuário autenticado
        $user = auth()->user();

        // Se o usuário da seção é um CustomUser ou Provider
        if ($user instanceof \App\Models\CustomUser) {
            // Obtém os endereços associados ao usuário e ordena por is_default
            // Usando a tabela pivot address_custom_user
            $addresses = $user->addresses()->withPivot('is_default')
                ->orderByDesc('address_custom_user.is_default')->get();
        } elseif ($user instanceof \App\Models\Provider) {
            // Obtém os endereços associados ao usuário e ordena por is_default
            // Usando a tabela pivot address_provider
            $addresses = $user->addresses()->withPivot('is_default')
                ->orderByDesc('address_provider.is_default')->get();
        } else {
            abort(403, 'Acesso não autorizado'); // Mensagem de erro genérica
        }

        // Retorna a view com a lista de endereços
        return view('addresses.index', compact('addresses'));
    }

    /**
     * Mostra o formulário para criação de um novo endereço.
     */
    public function create()
    {
        return view('addresses.create');
    }

    /**
     * Armazena um novo endereço e define a flag is_default conforme as regras:
     * 1. Se for o primeiro, torna padrão.
     * 2. Caso contrário, não-padrão.
     * 3. Se for padrão, limpa qualquer outro padrão existente.
     */
    public function store(Request $request)
    {
        // 1) Validação dos campos
        $validator = Validator::make($request->all(), [
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
            'complemento' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $user = auth()->user();

        // 2) Cria registro na tabela addresses
        $address = Address::create($data);

        // 3) Verifica existência de outros endereços
        $hasOther = $user->addresses()->exists();
        $isDefault = !$hasOther;

        // 4) Associa via pivot e seta is_default
        $user->addresses()->attach($address->id, ['is_default' => $isDefault]);

        // 5) Se for padrão, limpa flags antigas
        if ($isDefault) {
            $user->addresses()
                ->wherePivot('address_id', '!=', $address->id)
                ->updateExistingPivot($user->addresses()->pluck('address_id')->toArray(), ['is_default' => false]);
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Endereço cadastrado com sucesso!');
    }

    /**
     * Formulário de edição de um endereço existente.
     */
    public function edit(Address $address)
    {
        $user = auth()->user();
        if (!$user->addresses->contains($address->id)) {
            abort(403, 'Acesso não autorizado');
        }

        return view('addresses.edit', compact('address'));
    }

    /**
     * Atualiza um endereço e, se necessário, a lógica de is_default:
     * - Se marcado manualmente como padrão, limpa os demais.
     */
    public function update(Request $request, Address $address)
    {
        $user = auth()->user();
        if (!$user->addresses->contains($address->id)) {
            abort(403, 'Acesso não autorizado');
        }

        $validator = Validator::make($request->all(), [
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
            'complemento' => 'nullable|string|max:255',
            'is_default' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Atualiza campos básicos
        $address->update($data);

        // Se marcou como padrão, limpa os demais
        if (array_key_exists('is_default', $data) && $data['is_default']) {
            $user->addresses()
                ->wherePivot('address_id', '!=', $address->id)
                ->updateExistingPivot($user->addresses()->pluck('address_id')->toArray(), ['is_default' => false]);
            $user->addresses()->updateExistingPivot($address->id, ['is_default' => true]);
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Endereço atualizado com sucesso!');
    }

    /**
     * Remove um endereço e garante que sempre haja um padrão:
     * - Se remover o padrão, o primeiro restante vira padrão.
     */
    public function destroy(Address $address)
    {
        $user = auth()->user();

        // 1) Recupera o endereço via relação para ter o pivot carregado
        $userAddress = $user
            ->addresses()
            ->withPivot('is_default')
            ->find($address->id);

        if (!$userAddress) {
            abort(403, 'Acesso não autorizado');
        }

        // 2) Lê o flag is_default corretamente
        $wasDefault = $userAddress->pivot->is_default;

        // 3) Desanexa o endereço
        $user->addresses()->detach($address->id);

        // 4) Se era padrão, marca o próximo como padrão
        if ($wasDefault) {
            $next = $user->addresses()->first();
            if ($next) {
                $user->addresses()
                    ->updateExistingPivot($next->id, ['is_default' => true]);
            }
        }

        // 5) Remove o registro se não mais referenciado
        if (
            $address->customUsers()->count() === 0
            && $address->providers()->count() === 0
        ) {
            $address->delete();
        }

        return redirect()->route('addresses.index')
            ->with('success', 'Endereço removido com sucesso!');
    }

    /**
     * Define um endereço como padrão.
     */
    public function setDefault(Address $address)
    {
        // Verifica se o usuário autenticado tem acesso ao endereço
        $user = auth()->user();

        // Verifica se o endereço pertence ao usuário
        if (!$user->addresses->contains($address->id)) {
            abort(403, 'Acesso não autorizado'); // 403 Forbidden
        }

        // Se já for padrão, não faz nada
        $user->addresses()->updateExistingPivot(
            $user->addresses->pluck('id')->toArray(),
            ['is_default' => false]
        );

        // Se não for padrão, define como padrão
        $user->addresses()->updateExistingPivot($address->id, ['is_default' => true]);

        // Retorna para a lista de endereços com mensagem de sucesso
        return redirect()->route('addresses.index')->with('success', 'Endereço definido como padrão.');
    }

}

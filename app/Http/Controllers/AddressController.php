<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Exibe a lista de endereços do usuário logado
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtém todos os endereços associados ao usuário autenticado
        $addresses = auth()->user()->addresses()->get();
        // Retorna a view de listagem com os endereços
        return view('addresses.index', compact('addresses'));
    }

    /**
     * Mostra o formulário de criação de novo endereço
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retorna a view com o formulário de criação
        return view('addresses.create');
    }

    /**
     * Armazena um novo endereço no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valida os dados do formulário
        $validator = Validator::make($request->all(), [
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
            'complemento' => 'nullable|string|max:255',
        ]);

        // Se a validação falhar, redireciona de volta com erros
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cria um novo endereço com os dados validados
        $address = Address::create($validator->validated());
        
        // Associa o novo endereço ao usuário logado
        auth()->user()->addresses()->attach($address->id);

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('addresses.index')
            ->with('success', 'Endereço cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um endereço existente
     * @param  Address  $address
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        // Verifica se o endereço pertence ao usuário logado
        if (!auth()->user()->addresses->contains($address->id)) {
            abort(403, 'Acesso não autorizado'); // Retorna erro 403 se não pertencer
        }

        // Retorna a view de edição com os dados do endereço
        return view('addresses.edit', compact('address'));
    }

    /**
     * Atualiza um endereço existente no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Address $address)
    {
        // Verifica se o endereço pertence ao usuário logado
        if (!auth()->user()->addresses->contains($address->id)) {
            abort(403, 'Acesso não autorizado');
        }

        // Valida os dados do formulário
        $validator = Validator::make($request->all(), [
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
            'complemento' => 'nullable|string|max:255',
        ]);

        // Se a validação falhar, redireciona de volta com erros
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Atualiza o endereço com os dados validados
        $address->update($validator->validated());

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('addresses.index')
            ->with('success', 'Endereço atualizado com sucesso!');
    }

    /**
     * Remove um endereço do banco de dados
     * @param  Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Address $address)
    {
        // Verifica se o endereço pertence ao usuário logado
        if (!auth()->user()->addresses->contains($address->id)) {
            abort(403, 'Acesso não autorizado');
        }

        // Remove a relação entre o usuário e o endereço
        auth()->user()->addresses()->detach($address->id);
        
        // Verifica se o endereço não está sendo usado por outros usuários ou provedores
        if ($address->customUsers()->count() === 0 && $address->providers()->count() === 0) {
            // Se não estiver em uso, deleta o registro do endereço
            $address->delete();
        }

        // Redireciona para a listagem com mensagem de sucesso
        return redirect()->route('addresses.index')
            ->with('success', 'Endereço removido com sucesso!');
    }
}
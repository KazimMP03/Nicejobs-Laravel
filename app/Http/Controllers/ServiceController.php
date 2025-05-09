<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Exibe todos os serviços com os relacionamentos carregados (categoria e provedor).
     */
    public function index()
    {
        $services = Service::with(['provider', 'category'])->get();

        return view('services.index', compact('services'));
    }

    /**
     * Exibe o formulário para cadastro de um novo serviço.
     */
    public function create()
    {
        // Recupera todas as categorias para o select do formulário
        $categories = ServiceCategory::all();

        return view('services.create', compact('categories'));
    }

    /**
     * Armazena um novo serviço no banco de dados.
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $data = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'price'               => 'required|numeric|min:0',
        ]);

        // Define o provedor logado como dono do serviço e status inicial como pendente
        $data['provider_id'] = auth()->id();
        $data['status']      = Service::STATUS_PENDING;

        // Cria o novo serviço
        Service::create($data);

        return redirect()->route('services.index')
                         ->with('success', 'Serviço cadastrado com sucesso!');
    }

    /**
     * Exibe o formulário de edição para um serviço específico.
     */
    public function edit(Service $service)
    {
        // Recupera todas as categorias para possibilitar edição
        $categories = ServiceCategory::all();

        return view('services.edit', compact('service', 'categories'));
    }

    /**
     * Atualiza os dados de um serviço existente.
     */
    public function update(Request $request, Service $service)
    {
        // Validação dos dados atualizados
        $data = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'price'               => 'required|numeric|min:0',
            'status'              => 'required|in:' . implode(',', [
                Service::STATUS_PENDING,
                Service::STATUS_ACTIVE,
                Service::STATUS_INACTIVE,
                Service::STATUS_CANCELED
            ]),
        ]);

        // Atualiza o serviço com os novos dados
        $service->update($data);

        return redirect()->route('services.index')
                         ->with('success', 'Serviço atualizado com sucesso!');
    }

    /**
     * Remove um serviço do banco de dados.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
                         ->with('success', 'Serviço removido com sucesso!');
    }
}

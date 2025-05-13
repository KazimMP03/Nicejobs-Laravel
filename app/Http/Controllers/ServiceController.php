<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Exibe todos os serviços do Provider logado.
     */
    public function index()
    {
        $provider = auth('web')->user();

        if (!$provider instanceof \App\Models\Provider) {
            abort(403, 'Acesso não autorizado.');
        }

        $services = Service::with('category')
            ->where('provider_id', $provider->id)
            ->get();

        return view('services.index', compact('services'));
    }

    /**
     * Exibe o formulário para cadastro de um novo serviço.
     */
    public function create()
    {
        $provider = auth('web')->user();

        if (!$provider instanceof \App\Models\Provider) {
            abort(403, 'Acesso não autorizado.');
        }

        $categories = ServiceCategory::all();

        return view('services.create', compact('categories'));
    }

    /**
     * Armazena um novo serviço no banco de dados.
     */
    public function store(Request $request)
    {
        $provider = auth('web')->user();

        if (!$provider instanceof \App\Models\Provider) {
            abort(403, 'Acesso não autorizado.');
        }

        $data = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'price'               => 'required|numeric|min:0',
        ]);

        $data['provider_id'] = $provider->id;
        $data['status']      = Service::STATUS_PENDING;

        Service::create($data);

        return redirect()->route('services.index')
                         ->with('success', 'Serviço cadastrado com sucesso!');
    }

    /**
     * Exibe o formulário de edição para um serviço específico.
     */
    public function edit(Service $service)
    {
        $provider = auth('web')->user();

        if (!$provider instanceof \App\Models\Provider || $service->provider_id !== $provider->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $categories = ServiceCategory::all();

        return view('services.edit', compact('service', 'categories'));
    }

    /**
     * Atualiza os dados de um serviço existente.
     */
    public function update(Request $request, Service $service)
    {
        $provider = auth('web')->user();

        if (!$provider instanceof \App\Models\Provider || $service->provider_id !== $provider->id) {
            abort(403, 'Acesso não autorizado.');
        }

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

        $service->update($data);

        return redirect()->route('services.index')
                         ->with('success', 'Serviço atualizado com sucesso!');
    }

    /**
     * Remove um serviço do banco de dados.
     */
    public function destroy(Service $service)
    {
        $provider = auth('web')->user();

        if (!$provider instanceof \App\Models\Provider || $service->provider_id !== $provider->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $service->delete();

        return redirect()->route('services.index')
                         ->with('success', 'Serviço removido com sucesso!');
    }
}

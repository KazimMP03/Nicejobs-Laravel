<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    /**
     * Exibe todas as categorias de serviço cadastradas.
     */
    public function index()
    {
        $categories = ServiceCategory::all();

        return view('service_categories.index', compact('categories'));
    }

    /**
     * Exibe o formulário para criação de uma nova categoria.
     */
    public function create()
    {
        return view('service_categories.create');
    }

    /**
     * Armazena uma nova categoria de serviço no banco de dados.
     */
    public function store(Request $request)
    {
        // Valida os dados do formulário
        $data = $request->validate([
            'name'        => 'required|string|unique:service_categories,name',
            'slug'        => 'required|string|unique:service_categories,slug',
            'description' => 'nullable|string',
        ]);

        // Cria a nova categoria
        ServiceCategory::create($data);

        return redirect()->route('service-categories.index')
                         ->with('success', 'Categoria de serviço criada com sucesso!');
    }

    /**
     * Exibe o formulário de edição para uma categoria específica.
     */
    public function edit(ServiceCategory $serviceCategory)
    {
        return view('service_categories.edit', compact('serviceCategory'));
    }

    /**
     * Atualiza uma categoria existente com novos dados.
     */
    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        // Valida os dados considerando o ID da categoria atual para evitar conflitos no unique
        $data = $request->validate([
            'name'        => 'required|string|unique:service_categories,name,' . $serviceCategory->id,
            'slug'        => 'required|string|unique:service_categories,slug,' . $serviceCategory->id,
            'description' => 'nullable|string',
        ]);

        // Atualiza a categoria
        $serviceCategory->update($data);

        return redirect()->route('service-categories.index')
                         ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove uma categoria do banco de dados.
     */
    public function destroy(ServiceCategory $serviceCategory)
    {
        // (Opcional) Verificar se há serviços vinculados antes de deletar

        $serviceCategory->delete();

        return redirect()->route('service-categories.index')
                         ->with('success', 'Categoria removida com sucesso!');
    }
}

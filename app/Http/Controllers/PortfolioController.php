<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Exibe a tela de criação de portfólio.
     */
    public function create()
    {
        /** @var Provider $provider */
        $provider = Auth::user();

        if (! ($provider instanceof Provider)) {
            abort(403, 'Acesso não autorizado.');
        }

        // Se já existir portfólio para este provider, redireciona para a edição daquele registro
        $existing = Portfolio::where('provider_id', $provider->id)->first();
        if ($existing) {
            return redirect()
                ->route('provider.portfolio.edit', $existing)
                ->with('error', 'Você já possui um portfólio. Edite-o abaixo.');
        }

        return view('portfolio.create');
    }

    /**
     * Armazena um novo portfólio (até 9 arquivos).
     */
    public function store(Request $request)
    {
        /** @var Provider $provider */
        $provider = Auth::user();

        if (! ($provider instanceof Provider)) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'files'       => 'required|array|max:9',
            'files.*'     => 'file|mimetypes:'
                             . 'image/jpeg,image/png,image/jpg,image/gif,image/webp,'
                             . 'video/mp4,video/quicktime,video/avi|max:20480',
        ]);

        // Upload de cada arquivo para storage/app/public/portfolios
        $paths = [];
        foreach ($request->file('files') as $file) {
            $paths[] = $file->store('portfolios', 'public');
        }

        // Cria o registro no banco e captura a instância
        $portfolio = Portfolio::create([
            'provider_id' => $provider->id,
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'media_paths' => $paths, // cast no Model para array→JSON
        ]);

        // Redireciona para a edição deste portfólio
        return redirect()
            ->route('provider.portfolio.edit', $portfolio)
            ->with('success', 'Portfólio criado com sucesso!');
    }

    /**
     * Exibe um portfólio específico.
     * (pode ser usado para exibir ao cliente, mas não faz parte do CRUD interno aqui)
     */
    public function show(Portfolio $portfolio)
    {
        return view('portfolio.show', compact('portfolio'));
    }

    /**
     * Exibe a tela de edição de portfólio. Só o dono (provider) pode editar.
     */
    public function edit(Portfolio $portfolio)
    {
        $this->authorizeProvider($portfolio);

        return view('portfolio.edit', compact('portfolio'));
    }

    /**
     * Atualiza um portfólio existente (mescla novos arquivos, até 9 no total).
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $this->authorizeProvider($portfolio);

        $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'files'       => 'nullable|array|max:9',
            'files.*'     => 'file|mimetypes:'
                             . 'image/jpeg,image/png,image/jpg,image/gif,image/webp,'
                             . 'video/mp4,video/quicktime,video/avi|max:20480',
        ]);

        // Recupera paths já existentes (cast para array no Model)
        $existingPaths = $portfolio->media_paths ?? [];

        // Se vierem novos arquivos, faz upload e adiciona sem exceder 9 itens
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if (count($existingPaths) >= 9) {
                    break;
                }
                $existingPaths[] = $file->store('portfolios', 'public');
            }
        }

        $portfolio->update([
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'media_paths' => $existingPaths,
        ]);

        // Redireciona de volta para a edição deste portfólio
        return redirect()
            ->route('provider.portfolio.edit', $portfolio)
            ->with('success', 'Portfólio atualizado com sucesso!');
    }

    /**
     * Remove completamente o portfólio e seus arquivos.
     */
    public function destroy(Portfolio $portfolio)
    {
        $this->authorizeProvider($portfolio);

        // Exclui cada arquivo do disco “public/portfolios/…”
        foreach ($portfolio->media_paths as $path) {
            Storage::disk('public')->delete($path);
        }

        $portfolio->delete();

        // Volta para a rota anterior (página que chamou a exclusão)
        return redirect()
            ->back()
            ->with('success', 'Portfólio excluído com sucesso!');
    }

    /**
     * Remove apenas um arquivo específico (imagem ou vídeo) do portfólio.
     */
    public function deleteImage(Request $request, Portfolio $portfolio)
    {
        $this->authorizeProvider($portfolio);

        $pathToDelete = $request->input('media_path');
        $paths        = $portfolio->media_paths ?? [];

        if (($key = array_search($pathToDelete, $paths)) !== false) {
            // 1) Exclui do disco
            Storage::disk('public')->delete($pathToDelete);

            // 2) Remove do array e reindexa
            unset($paths[$key]);
            $paths = array_values($paths);

            // 3) Atualiza no banco
            $portfolio->update([
                'media_paths' => $paths,
            ]);

            return back()->with('success', 'Arquivo removido com sucesso.');
        }

        return back()->with('error', 'Arquivo não encontrado.');
    }

    /**
     * Garante que o usuário autenticado é o dono do portfólio.
     */
    private function authorizeProvider(Portfolio $portfolio): void
    {
        /** @var Provider $user */
        $user = Auth::user();

        if (! ($user instanceof Provider) || $user->id !== $portfolio->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }
    }
}

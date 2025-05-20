<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function create()
    {
        $provider = auth()->user();

        if (!($provider instanceof Provider)) {
            abort(403);
        }

        if (Portfolio::where('provider_id', $provider->id)->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'Você já possui um portfólio. Edite ou exclua o atual.');
        }

        return view('portfolio.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'required|array|max:9',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('portfolios', 'public');
            $imagePaths[] = $path;
        }

        $provider = auth()->user();

        if (!($provider instanceof Provider )) {
            abort(403, 'Acesso não autorizado.');
        }

        $portfolio = Portfolio::updateOrCreate(
            ['provider_id' => $provider->id],
            [
                'title' => $request->title,
                'description' => $request->description,
                'media_paths' => $imagePaths
            ]
        );

        return redirect()->back()->with('success', 'Portfólio salvo com sucesso!');
    }

    public function show(Portfolio $portfolio)
    {
        return view('portfolio.show', compact('portfolio'));
    }

    public function edit(Portfolio $portfolio)
    {
        if (auth()->user() instanceof Provider && auth()->user()->id !== $portfolio->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('portfolio.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        if (auth()->user() instanceof Provider && auth()->user()->id !== $portfolio->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'nullable|array|max:9',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePaths = $portfolio->media_paths;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (count($imagePaths) >= 9) break;
                $path = $image->store('portfolios', 'public');
                $imagePaths[] = $path;
            }
        }

        $portfolio->update([
            'title' => $request->title,
            'description' => $request->description,
            'media_paths' => $imagePaths
        ]);

        return redirect()->back()->with('success', 'Portfólio atualizado com sucesso!');
    }

    public function destroy(Portfolio $portfolio)
    {
        if (auth()->user() instanceof Provider && auth()->user()->id !== $portfolio->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }

        foreach ($portfolio->media_paths as $path) {
            Storage::disk('public')->delete($path);
        }

        $portfolio->delete();

        return redirect()->route('dashboard')->with('success', 'Portfólio excluído com sucesso!');
    }

    public function deleteImage(Request $request, Portfolio $portfolio)
    {
        if (auth()->user() instanceof Provider && auth()->user()->id !== $portfolio->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $imagePathToDelete = $request->input('image_path');
        $imagePaths = $portfolio->media_paths;

        if (($key = array_search($imagePathToDelete, $imagePaths)) !== false) {
            Storage::disk('public')->delete($imagePathToDelete);
            unset($imagePaths[$key]);
            $imagePaths = array_values($imagePaths);
        }

        $portfolio->update([
            'media_paths' => $imagePaths
        ]);

        return back()->with('success', 'Imagem removida com sucesso.');
    }
}

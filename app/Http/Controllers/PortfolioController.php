<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends Controller
{
    /**
     * Armazena um novo portfólio.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados da requisição
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media_path' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // Máximo de 10MB
        ]);

        // Retorna erros de validação, se houver
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Dados validados
        $data = $validator->validated();

        // Upload do arquivo de mídia
        if ($request->hasFile('media')) {
            // Armazena o arquivo na pasta 'portfolios' no disco 'public'
            $path = $request->file('media')->store('portfolios', 'public');
            $data['media_path'] = $path;
        }

        // Cria o portfólio com os dados fornecidos
        $portfolio = Portfolio::create([
            'provider_id' => $data['provider_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'media_path' => $data['media_path'],
        ]);

        // Retorna o portfólio criado com status 201 (Created)
        return response()->json($portfolio, 201);
    }
}

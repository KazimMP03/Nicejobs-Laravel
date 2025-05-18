<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Lista os chats do usuário autenticado.
     */
    public function index()
    {
        $user = auth()->user();

        $chats = Chat::whereHas('serviceRequest', function ($q) use ($user) {
            if ($user instanceof \App\Models\Provider) {
                $q->where('provider_id', $user->id);
            } else {
                $q->where('custom_user_id', $user->id);
            }
        })->with('serviceRequest')->get();

        return view('chat.index', compact('chats'));
    }

    /**
     * Exibe o chat vinculado à ServiceRequest.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        // Segurança: só participantes podem ver
        if ($user->id !== $serviceRequest->custom_user_id && $user->id !== $serviceRequest->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Cria o chat se ainda não existir
        $chat = Chat::firstOrCreate([
            'service_request_id' => $serviceRequest->id
        ]);

        $messages = $chat->messages()->latest()->get();

        return view('chat.show', compact('chat', 'messages'));
    }

    /**
     * Armazena uma nova mensagem no chat.
     */
    public function storeMessage(Request $request, Chat $chat)
    {
        $user = auth()->user();
        $sr = $chat->serviceRequest;

        // Segurança: só envolvidos podem enviar
        if ($user->id !== $sr->custom_user_id && $user->id !== $sr->provider_id) {
            abort(403);
        }

        $data = $request->validate([
            'type' => 'required|in:text,emoji,image,file,audio,video',
            'message' => 'nullable|string|max:5000',
            'file' => 'nullable|file|max:20480', // até 20MB
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('chat_files', 'public');
        }

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => $user instanceof \App\Models\Provider ? 'provider' : 'custom_user',
            'sender_id' => $user->id,
            'type' => $data['type'],
            'message' => $data['message'] ?? null,
            'file_path' => $path,
        ]);

        return redirect()->route('chat.show', $sr);
    }
}

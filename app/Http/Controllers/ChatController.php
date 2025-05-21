<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Lista os chats do usuário autenticado.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $status = $request->input('status', 'all');

        $chats = Chat::whereHas('serviceRequest', function ($q) use ($user, $status) {
            if ($user instanceof \App\Models\Provider) {
                $q->where('provider_id', $user->id);
            } else {
                $q->where('custom_user_id', $user->id);
            }

            // Filtro de status
            if ($status === 'active') {
                $q->whereIn('status', ['chat_opened', 'accepted']);
            } elseif ($status === 'archived') {
                $q->whereIn('status', ['completed', 'cancelled', 'rejected']);
            }
        })->with('serviceRequest')->get();

        return view('chat.index', compact('chats', 'status'));
    }

    /**
     * Exibe o chat vinculado à ServiceRequest.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        if ($user->id !== $serviceRequest->custom_user_id && $user->id !== $serviceRequest->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $chat = Chat::firstOrCreate([
            'service_request_id' => $serviceRequest->id
        ]);

        $messages = $chat->messages()->oldest()->get();

        return view('chat.show', compact('chat', 'messages'));
    }

    /**
     * Armazena uma nova mensagem no chat.
     */
    public function storeMessage(Request $request, Chat $chat)
    {
        $user = auth()->user();
        $sr = $chat->serviceRequest;

        // Verifica se o usuário está vinculado à ServiceRequest
        if ($user->id !== $sr->custom_user_id && $user->id !== $sr->provider_id) {
            abort(403, 'Acesso não autorizado.');
        }

        // 🚫 Impede envio de mensagens se a ServiceRequest estiver finalizada
        if ($sr->isFinalized()) {
            return back()->with('error', 'Este chat está arquivado. Não é possível enviar mensagens.');
        }

        $data = $request->validate([
            'type' => 'required|in:text,emoji,image,file,audio,video',
            'message' => 'nullable|string|max:5000',
            'file' => 'nullable|file',
        ]);

        $path = null;
        $originalName = null;
        $mimeType = null;
        $size = null;
        $duration = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Limite em MB definido no .env
            $maxMb = config('chat.max_upload_size_mb', 20);
            $maxSizeBytes = $maxMb * 1024 * 1024;

            if ($file->getSize() > $maxSizeBytes) {
                return back()->withErrors([
                    'file' => "O arquivo excede o limite de {$maxMb}MB."
                ])->withInput();
            }

            $path = $file->store('chat_files', 'public');
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $size = $file->getSize();

            if (in_array($data['type'], ['audio', 'video'])) {
                $duration = $this->getMediaDuration($file->getPathname());
            }
        }

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => $user instanceof \App\Models\Provider ? 'provider' : 'custom_user',
            'sender_id' => $user->id,
            'type' => $data['type'],
            'message' => $data['message'] ?? null,
            'file_path' => $path,
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'size' => $size,
            'duration' => $duration,
        ]);

        return redirect()->route('chat.show', $sr);
    }

    /**
     * Retorna a duração de mídia (áudio/vídeo) em segundos usando ffprobe.
     */
    private function getMediaDuration(string $filePath): ?int
    {
        try {
            $command = "ffprobe -v error -select_streams a:0 -show_entries stream=duration " .
                       "-of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($filePath);
            $output = shell_exec($command);

            if ($output) {
                return (int) round((float) $output);
            }
        } catch (\Exception $e) {
            // Ignorar falha silenciosamente
        }

        return null;
    }
}

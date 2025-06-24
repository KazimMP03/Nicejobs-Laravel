<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    /**
     * Lista os chats do usuário autenticado, ordenando pelo status da ServiceRequest (PostgreSQL).
     */
    public function index(Request $request)
    {
        $user         = auth()->user();
        $statusFilter = $request->input('status', 'all');

        // 1) Obtém a ordem de status definida no Model
        $orderList = ServiceRequest::statusOrder(); 
        // Exemplo: ['accepted', 'requested', 'chat_opened', ...] ou qualquer outra ordem que você tenha definido.

        // 2) Monta expressão CASE WHEN para PostgreSQL
        $cases = [];
        foreach ($orderList as $idx => $st) {
            // Atenção: certifique-se de que $st não contenha apóstrofos inesperados.
            $cases[] = "WHEN service_requests.status = '{$st}' THEN {$idx}";
        }
        // Se surgir status inesperado, ficará por último (índice = count($orderList))
        $elseIndex = count($orderList);
        $caseExpr = "(CASE " . implode(' ', $cases) . " ELSE {$elseIndex} END)";

        // 3) Inicia query com join para permitir ordenar pelo campo de service_requests
        $query = Chat::select('chats.*')
            ->join('service_requests', 'chats.service_request_id', '=', 'service_requests.id');

        // 4) Filtra apenas chats onde o usuário é provider ou custom_user na ServiceRequest
        if ($user instanceof \App\Models\Provider) {
            $query->where('service_requests.provider_id', $user->id);
        } else {
            $query->where('service_requests.custom_user_id', $user->id);
        }

        // 5) Filtro de status (“active” / “archived”), ajustando conforme o que considerar “ativo”
        if ($statusFilter === 'active') {
            $query->whereIn('service_requests.status', [
                ServiceRequest::STATUS_CHAT_OPENED,
                ServiceRequest::STATUS_PENDING_ACCEPT,
                ServiceRequest::STATUS_ACCEPTED,
            ]);
        } elseif ($statusFilter === 'archived') {
            $query->whereIn('service_requests.status', [
                ServiceRequest::STATUS_COMPLETED,
                ServiceRequest::STATUS_CANCELLED,
                ServiceRequest::STATUS_REJECTED,
            ]);
        }
        // Se $statusFilter for 'all', não aplica whereIn adicional.

        // 6) Ordena pelo CASE WHEN de status. Opcionalmente, depois ordena por data de atualização do chat:
        $query->orderByRaw($caseExpr)
              ->orderBy('chats.updated_at', 'desc');

        // 7) Carrega relacionamento para evitar N+1
        $chats = $query->with('serviceRequest')->get();

        return view('chat.index', [
            'chats'  => $chats,
            'status' => $statusFilter,
        ]);
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

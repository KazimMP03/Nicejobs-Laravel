@extends('layouts.app')

@section('content')
<!-- Estilo da página -->
<link rel="stylesheet" href="{{ asset('css/chat/chat.css') }}">

<!-- Estilos: Bootstrap (layout/modal) + Font Awesome (ícones) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<div class="container chat-container">
    <h3>Chat com 
        {{ auth()->user() instanceof \App\Models\Provider 
            ? $chat->serviceRequest->customUser->user_name 
            : $chat->serviceRequest->provider->user_name }}
    </h3>

    <div id="chat-box" class="chat-box">
        @foreach ($messages as $msg)
            <div class="chat-message {{ $msg->sender_type === (auth()->user() instanceof \App\Models\Provider ? 'provider' : 'custom_user') ? 'me' : 'them' }}">
                <div class="chat-bubble">
                    @if ($msg->type === 'text' || $msg->type === 'emoji')
                        {{ $msg->message }}
                    @elseif ($msg->type === 'image')
                        <img src="{{ asset('storage/' . $msg->file_path) }}" class="chat-image" onclick="openImage('{{ asset('storage/' . $msg->file_path) }}')">
                    @elseif ($msg->type === 'file')
                        @php
                            $ext = pathinfo($msg->original_name, PATHINFO_EXTENSION);
                            $icon = match(strtolower($ext)) {
                                'pdf'   => 'fa-file-pdf text-file-pdf',
                                'doc', 'docx' => 'fa-file-word text-file-word',
                                'xls', 'xlsx' => 'fa-file-excel text-file-excel',
                                'ppt', 'pptx' => 'fa-file-powerpoint text-file-ppt',
                                'zip', 'rar', '7z' => 'fa-file-archive text-file-archive',
                                'txt'   => 'fa-file-lines text-file-text',
                                'csv', 'xml', 'json' => 'fa-file-code text-file-code',
                                default => 'fa-file text-file-default',
                            };
                        @endphp
                        <div class="file-message">
                            <i class="fas {{ $icon }}"></i>
                            <span>{{ $msg->original_name }}</span>
                            <div>
                                <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Abrir</a>
                                <a href="{{ asset('storage/' . $msg->file_path) }}" download class="btn btn-sm btn-outline-secondary">Salvar como…</a>
                            </div>
                        </div>
                    @elseif ($msg->type === 'audio')
                        <audio controls src="{{ asset('storage/' . $msg->file_path) }}"></audio>
                    @elseif ($msg->type === 'video')
                        <video controls class="chat-video">
                            <source src="{{ asset('storage/' . $msg->file_path) }}">
                        </video>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <form id="chat-form" action="{{ route('chat.message.store', $chat) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="chat-input-container">
            <button type="button" id="emoji-btn"><i class="far fa-smile"></i></button>

            <div class="position-relative">
                <button type="button" id="attach-btn"><i class="fas fa-paperclip"></i></button>
                <div id="attachment-menu" class="attachment-panel d-none">
                    <button type="button" class="attachment-item" onclick="triggerFileInput('image')">
                        <i class="fas fa-photo-video"></i><span>Fotos e vídeos</span>
                    </button>
                    <button type="button" class="attachment-item" onclick="triggerFileInput('camera')">
                        <i class="fas fa-camera"></i><span>Câmera</span>
                    </button>
                    <button type="button" class="attachment-item" onclick="triggerFileInput('file')">
                        <i class="fas fa-file-alt"></i><span>Documento</span>
                    </button>
                </div>
            </div>

            <input type="file" id="hidden-image-input" class="d-none" accept="image/*,video/*">
            <input type="file" id="hidden-camera-input" class="d-none" accept="image/*" capture="environment">
            <input type="file" id="hidden-file-input" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z,.xml,.csv,.json">

            <input type="text" name="message" id="message-input" placeholder="Mensagem" autocomplete="off">
            <input type="hidden" name="type" id="message-type" value="text">
            <button type="submit" id="send-btn"><i class="fas fa-paper-plane"></i></button>
            <button type="button" id="audio-btn"><i class="fas fa-microphone"></i></button>
        </div>
    </form>
</div>

{{-- MODAL DE PRÉ-VISUALIZAÇÃO --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="preview-form" method="POST" action="{{ route('chat.message.store', $chat) }}" enctype="multipart/form-data" class="modal-content">
      @csrf
      <input type="hidden" name="type" id="modal-type">

      <div class="modal-header">
        <h5 class="modal-title">Pré-visualização do envio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <div id="file-preview" class="mb-3 text-center d-none"></div>
        <p><strong>Arquivo:</strong> <span id="file-name"></span></p>
        <p><strong>Tamanho:</strong> <span id="file-size"></span></p>
        <input type="text" name="message" class="form-control" placeholder="Mensagem (opcional)">
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/chat.js') }}"></script>
@endsection

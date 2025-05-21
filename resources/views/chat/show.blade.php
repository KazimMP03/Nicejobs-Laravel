@extends('layouts.app')

@section('content')

<!-- ================= ESTILOS ================= -->
<!-- CSS do chat -->
<link rel="stylesheet" href="{{ asset('css/chat/chat.css') }}">

<!-- Bootstrap + Font Awesome (ícones) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


<!-- ================= CONTAINER DO CHAT ================= -->
<div class="container chat-container">

    <!-- Título do chat com nome do outro participante -->
    <h3>Chat com 
        {{ auth()->user() instanceof \App\Models\Provider 
            ? $chat->serviceRequest->customUser->user_name 
            : $chat->serviceRequest->provider->user_name }}
    </h3>

    <!-- Caixa de mensagens -->
    <div id="chat-box" class="chat-box">
        @foreach ($messages as $msg)
            <div class="chat-message 
                {{ 
                    $msg->sender_type === (auth()->user() instanceof \App\Models\Provider ? 'provider' : 'custom_user') 
                    ? 'me' 
                    : 'them' 
                }}">
                <div class="chat-bubble">

                    {{-- Texto ou emoji --}}
                    @if ($msg->type === 'text' || $msg->type === 'emoji')
                        {{ $msg->message }}

                    {{-- Imagem --}}
                    @elseif ($msg->type === 'image')
                        <img 
                            src="{{ asset('storage/' . $msg->file_path) }}" 
                            class="chat-image" 
                            onclick="openImage('{{ asset('storage/' . $msg->file_path) }}')">

                    {{-- Arquivo --}}
                    @elseif ($msg->type === 'file')
                        @php
                            $ext = pathinfo($msg->original_name, PATHINFO_EXTENSION);
                            $sizeKb = $msg->size ? number_format($msg->size / 1024, 0) . ' KB' : '';
                            $desc = match(strtolower($ext)) {
                                'pdf' => 'Documento PDF',
                                'doc', 'docx' => 'Documento do Word',
                                'xls', 'xlsx' => 'Planilha do Excel',
                                'ppt', 'pptx' => 'Apresentação do PowerPoint',
                                'txt' => 'Arquivo de texto',
                                'zip', 'rar', '7z' => 'Arquivo compactado',
                                'json', 'xml', 'csv' => 'Arquivo de dados',
                                default => strtoupper($ext) . ' file'
                            };
                            $icon = match(strtolower($ext)) {
                                'pdf' => 'fa-file-pdf text-file-pdf',
                                'doc', 'docx' => 'fa-file-word text-file-word',
                                'xls', 'xlsx' => 'fa-file-excel text-file-excel',
                                'ppt', 'pptx' => 'fa-file-powerpoint text-file-ppt',
                                'zip', 'rar', '7z' => 'fa-file-archive text-file-archive',
                                'txt' => 'fa-file-lines text-file-text',
                                'csv', 'xml', 'json' => 'fa-file-code text-file-code',
                                default => 'fa-file text-file-default',
                            };
                        @endphp

                        <div class="file-message-new">
                            <div class="file-icon">
                                <i class="fas {{ $icon }}"></i>
                            </div>
                            <div class="file-info">
                                <div class="file-name" title="{{ $msg->original_name }}">
                                    {{ \Illuminate\Support\Str::limit($msg->original_name, 40) }}
                                </div>
                                <div class="file-desc">
                                    {{ $desc }} • {{ $sizeKb }}
                                </div>
                                <div class="file-actions">
                                    <a href="{{ asset('storage/' . $msg->file_path) }}" 
                                        target="_blank" 
                                        class="btn btn-sm btn-outline-primary">Abrir</a>
                                    <a href="{{ asset('storage/' . $msg->file_path) }}" 
                                        download 
                                        class="btn btn-sm btn-outline-secondary">Salvar como…</a>
                                </div>
                            </div>
                        </div>

                    {{-- Áudio --}}
                    @elseif ($msg->type === 'audio')
                        <audio controls src="{{ asset('storage/' . $msg->file_path) }}"></audio>

                    {{-- Vídeo --}}
                    @elseif ($msg->type === 'video')
                        <video controls class="chat-video">
                            <source src="{{ asset('storage/' . $msg->file_path) }}">
                        </video>
                    @endif

                    {{-- Horário da mensagem --}}
                    <div class="bubble-time" title="{{ $msg->created_at->format('d/m/Y H:i') }}">
                        {{ $msg->created_at->format('H:i') }}
                    </div>

                </div>
            </div>
        @endforeach
    </div>


    <!-- ================= FORMULÁRIO DE ENVIO ================= -->
    <form id="chat-form" 
          action="{{ route('chat.message.store', $chat) }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf

        <div class="chat-input-container">

            {{-- Botão de emoji --}}
            <button type="button" id="emoji-btn">
                <i class="far fa-smile"></i>
            </button>

            {{-- Botão de anexos --}}
            <div class="position-relative">
                <button type="button" id="attach-btn">
                    <i class="fas fa-paperclip"></i>
                </button>
                <div id="attachment-menu" class="attachment-panel d-none">
                    <button type="button" class="attachment-item" onclick="triggerFileInput('image')">
                        <i class="fas fa-photo-video"></i> <span>Fotos e vídeos</span>
                    </button>
                    <button type="button" class="attachment-item" onclick="openCamera()">
                        <i class="fas fa-camera"></i> <span>Câmera</span>
                    </button>
                    <button type="button" class="attachment-item" onclick="triggerFileInput('file')">
                        <i class="fas fa-file-alt"></i> <span>Documento</span>
                    </button>
                </div>
            </div>

            {{-- Inputs ocultos para upload --}}
            <input type="file" id="hidden-image-input" class="d-none" accept="image/*,video/*">
            <input type="file" id="hidden-camera-input" class="d-none" accept="image/*" capture="environment">
            <input type="file" id="hidden-file-input" class="d-none" 
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z,.xml,.csv,.json">

            {{-- Input de mensagem --}}
            <input type="text" name="message" id="message-input" placeholder="Mensagem" autocomplete="off">
            <input type="hidden" name="type" id="message-type" value="text">

            {{-- Botões de enviar e de áudio --}}
            <button type="submit" id="send-btn"><i class="fas fa-paper-plane"></i></button>
            <button type="button" id="audio-btn"><i class="fas fa-microphone"></i></button>

        </div>
    </form>
</div>


<!-- ================= MODAL DE PRÉ-VISUALIZAÇÃO ================= -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="preview-form" 
              method="POST" 
              action="{{ route('chat.message.store', $chat) }}" 
              enctype="multipart/form-data" 
              class="modal-content">
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


<!-- ================= MODAL DE VISUALIZAÇÃO DE IMAGEM ================= -->
<div class="modal fade" id="imageViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-0">
            <div class="modal-body p-0 text-center">
                <img id="modal-image" src="" class="img-fluid rounded" style="max-height: 90vh;">
            </div>
        </div>
    </div>
</div>


<!-- ================= MODAL DA CÂMERA ================= -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Tirar Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar" onclick="stopCamera()"></button>
            </div>

            <div class="modal-body text-center">
                {{-- Vídeo da câmera --}}
                <video id="camera-stream" autoplay playsinline style="max-width: 100%; border-radius: 10px;"></video>

                {{-- Prévia da imagem capturada --}}
                <div id="captured-image-preview" class="d-none">
                    <img id="captured-image" src="" class="img-fluid rounded">
                </div>
            </div>

            <div class="modal-footer flex-column">
                {{-- Botão de capturar --}}
                <button id="capture-btn" class="btn btn-light rounded-circle" style="width:60px;height:60px;">
                    <i class="fas fa-camera"></i>
                </button>

                {{-- Formulário após captura --}}
                <form id="camera-form" 
                      method="POST" 
                      action="{{ route('chat.message.store', $chat) }}" 
                      enctype="multipart/form-data" 
                      class="w-100 d-none">
                    @csrf
                    <input type="hidden" name="type" value="image">
                    <input type="file" name="file" id="captured-file-input" class="d-none">
                    <input type="text" name="message" class="form-control mb-2 mt-2" placeholder="Mensagem (opcional)">

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-light" onclick="retakePhoto()">
                            <i class="fas fa-arrow-left"></i> Refazer
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Enviar
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- ================= EMOJI PICKER ================= -->
<emoji-picker id="emoji-picker" 
              style="position: absolute; bottom: 80px; left: 20px; z-index: 999; display: none;">
</emoji-picker>


<!-- ================= SCRIPTS ================= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@1.11.2/index.js"></script>
<script src="{{ asset('js/chat.js') }}"></script>

@endsection

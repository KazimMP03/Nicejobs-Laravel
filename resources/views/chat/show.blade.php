@extends('layouts.app')

@section('content')

<!-- ================= ESTILOS ================= -->
<link rel="stylesheet" href="{{ asset('css/chat/chat.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- ================= CONTAINER DO CHAT ================= -->
<div class="container chat-container">

    <h3>Chat com 
        {{ auth()->user() instanceof \App\Models\Provider 
            ? $chat->serviceRequest->customUser->user_name 
            : $chat->serviceRequest->provider->user_name }}
    </h3>

    {{-- Alerta se chat estiver arquivado --}}
    @if($chat->serviceRequest->isFinalized())
        <div class="alert alert-warning">
            Este chat está <strong>arquivado</strong>. Não é possível enviar novas mensagens.
        </div>
    @endif

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
                                'doc' => 'fa-file-word text-file-word',
                                'docx' => 'fa-file-word text-file-word',
                                'xls' => 'fa-file-excel text-file-excel',
                                'xlsx' => 'fa-file-excel text-file-excel',
                                'ppt' => 'fa-file-powerpoint text-file-ppt',
                                'pptx' => 'fa-file-powerpoint text-file-ppt',
                                'zip' => 'fa-file-archive text-file-archive',
                                'rar' => 'fa-file-archive text-file-archive',
                                '7z' => 'fa-file-archive text-file-archive',
                                'txt' => 'fa-file-lines text-file-text',
                                'csv' => 'fa-file-code text-file-code',
                                'xml' => 'fa-file-code text-file-code',
                                'json' => 'fa-file-code text-file-code',
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

                    <div class="bubble-time" title="{{ $msg->created_at->format('d/m/Y H:i') }}">
                        {{ $msg->created_at->format('H:i') }}
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    {{-- ================= FORMULÁRIO DE ENVIO ================= --}}
    @if(!$chat->serviceRequest->isFinalized())
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

                {{-- Inputs ocultos --}}
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
    @else
        {{-- Se chat arquivado, desativa formulário --}}
        <div class="alert alert-secondary mt-3">
            📦 Este chat está arquivado. Não é possível enviar novas mensagens.
        </div>
    @endif

</div>

{{-- ================= EMOJI PICKER ================= --}}
<emoji-picker id="emoji-picker" style="display: none; position: absolute; bottom: 70px; left: 20px; z-index: 9999;"></emoji-picker>

{{-- ================== MODAIS E SCRIPTS ================== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@1.11.2/index.js"></script>
<script src="{{ asset('js/chat.js') }}"></script>

@endsection

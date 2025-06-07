{{-- resources/views/chat/show.blade.php --}}
@extends('layouts.app')

@section('content')

<!-- ================= ESTILOS ================= -->
<link rel="stylesheet" href="{{ asset('css/chat/chat.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- ================= CABEÇALHO ================= -->
<div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
    <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
        style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
        Chat com
        {{ auth()->user() instanceof \App\Models\Provider
            ? $chat->serviceRequest->customUser->user_name
            : $chat->serviceRequest->provider->user_name }}
    </h3>
    <a href="{{ url()->previous() }}"
       class="btn btn-cancel fw-bold"
        style="margin-right: 50px;">
        <i class="fas fa-arrow-left me-2"></i> Voltar
    </a>
</div>

<!-- ================= CONTAINER CENTRAL ================= -->
<div class="d-flex justify-content-center align-items-start w-100" style="flex: 1; margin-top: 20px;">
    <div class="shadow-sm rounded bg-white p-4" style="width: 850px;">

        {{-- Alerta se arquivado --}}
        @if($chat->serviceRequest->isFinalized())
            <div class="alert alert-warning mb-3">
                Este chat está <strong>arquivado</strong>. Não é possível enviar novas mensagens.
            </div>
        @endif

        <!-- Caixa de mensagens -->
        <div id="chat-box" class="chat-box mb-4">
            @foreach ($messages as $msg)
                <div class="chat-message {{ $msg->sender_type === (auth()->user() instanceof \App\Models\Provider ? 'provider' : 'custom_user') ? 'me' : 'them' }}">
                    <div class="chat-bubble">
                        {{-- Conteúdo da mensagem --}}
                        @include('chat.partials.message-content', ['msg' => $msg])
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
                  enctype="multipart/form-data"
                  class="chat-input-container">
                @csrf

                {{-- Emoji --}}
                <button type="button" id="emoji-btn" class="btn btn-light">
                    <i class="far fa-smile"></i>
                </button>

                {{-- Anexos --}}
                <div class="position-relative">
                    <button type="button" id="attach-btn" class="btn btn-light">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <div id="attachment-menu" class="attachment-panel d-none">
                        <button type="button" class="attachment-item" onclick="triggerFileInput('image')">
                            <i class="fas fa-photo-video"></i> Fotos e vídeos
                        </button>
                        <button type="button" class="attachment-item" onclick="openCamera()">
                            <i class="fas fa-camera"></i> Câmera
                        </button>
                        <button type="button" class="attachment-item" onclick="triggerFileInput('file')">
                            <i class="fas fa-file-alt"></i> Documento
                        </button>
                    </div>
                </div>

                {{-- Inputs ocultos --}}
                <input type="file"
                       id="hidden-image-input"
                       name="file"
                       class="d-none"
                       accept="image/*,video/*">
                <input type="file"
                       id="hidden-file-input"
                       name="file"
                       class="d-none"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z,.xml,.csv,.json">

                {{-- Campo de texto --}}
                <input type="text"
                       name="message"
                       id="message-input"
                       class="flex-grow-1 mx-2"
                       placeholder="Mensagem"
                       autocomplete="off">

                {{-- Tipo de mensagem --}}
                <input type="hidden"
                       name="type"
                       id="message-type"
                       value="text">

                {{-- Enviar texto --}}
                <button type="submit" id="send-btn" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                </button>

                {{-- Gravar áudio --}}
                <button type="button" id="audio-btn" class="btn btn-light">
                    <i class="fas fa-microphone"></i>
                </button>
            </form>
        @else
            <div class="alert alert-secondary mt-3">
                📦 Este chat está arquivado. Não é possível enviar novas mensagens.
            </div>
        @endif

    </div>
</div>

{{-- Emoji picker e modais --}}
<emoji-picker id="emoji-picker"></emoji-picker>
@include('chat.partials.modals')

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@1.11.2/index.js"></script>
<script src="{{ asset('js/chat.js') }}"></script>

@endsection

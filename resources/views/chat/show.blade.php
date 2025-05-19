@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/chat/chat.css') }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://kit.fontawesome.com/a2d9a1a1a2.js" crossorigin="anonymous"></script>

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
                        <div class="file-message">
                           @php
                                $ext = pathinfo($msg->original_name, PATHINFO_EXTENSION);
                                $icon = match(strtolower($ext)) {
                                    'pdf'               => 'fa-file-pdf text-file-pdf',
                                    'doc', 'docx'       => 'fa-file-word text-file-word',
                                    'xls', 'xlsx'       => 'fa-file-excel text-file-excel',
                                    'ppt', 'pptx'       => 'fa-file-powerpoint text-file-ppt',
                                    'zip', 'rar', '7z'  => 'fa-file-archive text-file-archive',
                                    'txt'               => 'fa-file-lines text-file-text',
                                    'csv', 'xml', 'json'=> 'fa-file-code text-file-code',
                                    default             => 'fa-file text-file-default',
                                };
                            @endphp
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
            <button type="button" id="attach-btn" data-bs-toggle="modal" data-bs-target="#attachModal"><i class="fas fa-paperclip"></i></button>
            <input type="text" name="message" id="message-input" placeholder="Mensagem" autocomplete="off">
            <input type="hidden" name="type" id="message-type" value="text">
            <button type="submit" id="send-btn"><i class="fas fa-paper-plane"></i></button>
            <button type="button" id="audio-btn"><i class="fas fa-microphone"></i></button>
        </div>
    </form>
</div>

{{-- MODAL DE ANEXO --}}
<div class="modal fade" id="attachModal" tabindex="-1" aria-labelledby="attachModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('chat.message.store', $chat) }}" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Enviar anexo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <select name="type" class="form-select mb-2" required>
          <option value="">Escolha o tipo</option>
          <option value="image">Foto ou Vídeo</option>
          <option value="file">Documento</option>
        </select>
        <input type="file" name="file" class="form-control mb-2" required>
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

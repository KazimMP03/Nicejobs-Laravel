{{-- resources/views/chat/partials/message-content.blade.php --}}

@php
    // Variáveis auxiliares para arquivos
    $ext = $msg->type === 'file' ? pathinfo($msg->original_name, PATHINFO_EXTENSION) : null;
    $sizeKb = $msg->size ? number_format($msg->size / 1024, 0) . ' KB' : '';
    $desc = $ext ? strtoupper($ext) . ' File' : '';
    $icon = 'fa-file';
@endphp

@if ($msg->type === 'text' || $msg->type === 'emoji')
    {{ $msg->message }}

@elseif ($msg->type === 'image')
    <img
        src="{{ asset('storage/' . $msg->file_path) }}"
        class="chat-image"
        onclick="openImage('{{ asset('storage/' . $msg->file_path) }}')"
        alt="Imagem enviada"
    >

@elseif ($msg->type === 'file')
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
                <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Abrir</a>
                <a href="{{ asset('storage/' . $msg->file_path) }}" download class="btn btn-sm btn-outline-secondary">Salvar como…</a>
            </div>
        </div>
    </div>

@elseif ($msg->type === 'audio')
    <audio controls src="{{ asset('storage/' . $msg->file_path) }}"></audio>

@elseif ($msg->type === 'video')
    <video controls class="chat-video">
        <source src="{{ asset('storage/' . $msg->file_path) }}" type="video/mp4">
        Seu navegador não suporta vídeo.
    </video>
@endif

@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Chat com 
        {{ auth()->user() instanceof \App\Models\Provider 
            ? $chat->serviceRequest->customUser->user_name 
            : $chat->serviceRequest->provider->user_name }}
    </h3>

    <div class="border p-3 mb-4" style="height: 300px; overflow-y: auto;">
        @foreach ($messages as $msg)
            <div class="mb-2 {{ $msg->sender_type === (auth()->user() instanceof \App\Models\Provider ? 'provider' : 'custom_user') ? 'text-end' : 'text-start' }}">
                <div class="d-inline-block bg-light p-2 rounded">
                    @if ($msg->type === 'text' || $msg->type === 'emoji')
                        {{ $msg->message }}
                    @elseif ($msg->type === 'image')
                        <img src="{{ asset('storage/' . $msg->file_path) }}" class="img-fluid" style="max-height: 200px;">
                    @elseif ($msg->type === 'file')
                        <a href="{{ asset('storage/' . $msg->file_path) }}" download>📎 Arquivo</a>
                    @elseif ($msg->type === 'audio')
                        <audio controls src="{{ asset('storage/' . $msg->file_path) }}"></audio>
                    @elseif ($msg->type === 'video')
                        <video controls style="max-width: 100%">
                            <source src="{{ asset('storage/' . $msg->file_path) }}">
                        </video>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <form action="{{ route('chat.message.store', $chat) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <select name="type" class="form-select mb-2" onchange="toggleInputs(this.value)">
            <option value="text">Texto</option>
            <option value="emoji">Emoji</option>
            <option value="image">Imagem</option>
            <option value="file">Arquivo</option>
            <option value="audio">Áudio</option>
            <option value="video">Vídeo</option>
        </select>

        <div id="text-input">
            <input type="text" name="message" class="form-control mb-2" placeholder="Digite...">
        </div>

        <div id="file-input" class="d-none">
            <input type="file" name="file" class="form-control mb-2">
        </div>

        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>

<script>
function toggleInputs(type) {
    const fileTypes = ['image', 'file', 'audio', 'video'];
    document.getElementById('text-input').classList.toggle('d-none', fileTypes.includes(type));
    document.getElementById('file-input').classList.toggle('d-none', !fileTypes.includes(type));
}
</script>
@endsection

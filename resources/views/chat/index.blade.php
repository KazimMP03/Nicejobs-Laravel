@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Minhas Conversas</h2>

    @forelse($chats as $chat)
        @php
            $sr = $chat->serviceRequest;
            $otherUser = auth()->user() instanceof \App\Models\Provider
                ? $sr->customUser
                : $sr->provider;
        @endphp

        <a href="{{ route('chat.show', $sr->id) }}" class="d-block mb-3">
            Conversa sobre: <strong>{{ $sr->description }}</strong><br>
            com {{ $otherUser->user_name }}
        </a>
    @empty
        <p>Você ainda não possui conversas iniciadas.</p>
    @endforelse
</div>
@endsection

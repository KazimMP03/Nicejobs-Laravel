<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sender_type',
        'sender_id',
        'type',
        'message',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'duration',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->morphTo(__FUNCTION__, 'sender_type', 'sender_id');
    }
}


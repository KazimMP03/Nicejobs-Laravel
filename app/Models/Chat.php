<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChatMessage;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['service_request_id'];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}


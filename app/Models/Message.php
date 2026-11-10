<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['message_content', 'sender_id', 'receiver_id', 'conversation_id', 'timestamp', 'message_status', 'file_path', 'mime_type', 'message_type'];

    // Relationship with the User model (sender)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relationship with the User model (receiver)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Relationship with the Conversation model
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

}


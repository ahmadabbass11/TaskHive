<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'description', 'creation_date'];

    // Relationship with the User model (many-to-many through user_conversation table)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_conversation','conversation_id', 'user_id');
    }

    // Relationship with the Message model
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}

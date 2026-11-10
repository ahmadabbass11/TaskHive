<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback'; // or 'feedbacks' if you chose that table name

    protected $fillable = [
        'user_id',
        'content',
    ];

    /**
     * Relationship: Each Feedback belongs to one User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

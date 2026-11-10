<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_description',
    ];

    // A Role can belong to many Users (via pivot table: user_role)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role')
                    ->withPivot('have_permission')
                    ->withTimestamps();
    }
}


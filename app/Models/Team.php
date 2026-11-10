<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'team_admin', 'create_date'];

    // Relationship with the User model (team admin)
    public function teamAdmin()
    {
        return $this->belongsTo(User::class, 'team_admin');
    }

    // Relationship with the User model (many-to-many through user_team table)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_team');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'team_id');
    }

}
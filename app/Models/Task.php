<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'title', 'description', 'status', 'assignee', 'due_date', 'priority'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function files()
{
    return $this->hasMany(File::class);
}


}

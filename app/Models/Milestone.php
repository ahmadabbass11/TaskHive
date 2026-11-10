<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'start_date', 'end_date', 'deliverables', 'title'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

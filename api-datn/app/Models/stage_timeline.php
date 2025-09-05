<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stage_timeline extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_term_id',
        'number_of_rounds',
        'start_date',
        'end_date',
        'description',
        'status'
    ];

    public function projectTerm()
    {
        return $this->belongsTo(ProjectTerm::class);
    }
}

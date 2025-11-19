<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostponeProjectTermFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'postpone_project_term_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    public function postponeProjectTerm()
    {
        return $this->belongsTo(PostponeProjectTerm::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostponeProjectTerm extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_term_id',
        'assignment_id',
        'status',
    ];

    public function postponeProjectTermFiles()
    {
        return $this->hasMany(PostponeProjectTermFile::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}

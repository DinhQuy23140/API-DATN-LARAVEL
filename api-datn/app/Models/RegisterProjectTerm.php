<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterProjectTerm extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'project_term_id',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function projectTerm()
    {
        return $this->belongsTo(ProjectTerm::class);
    }
}

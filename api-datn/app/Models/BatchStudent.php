<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchStudent extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'project_terms_id', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function project_term()
    {
        return $this->belongsTo(ProjectTerm::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}

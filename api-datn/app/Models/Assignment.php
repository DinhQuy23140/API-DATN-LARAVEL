<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'batch_student_id',
        'project_id',
        'status',
    ];

    public function batch_student() {
        return $this->belongsTo(BatchStudent::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'assignment_supervisors')
            ->withPivot('role')
            ->withTimestamps();
    }
}

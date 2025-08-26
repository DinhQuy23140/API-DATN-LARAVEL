<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'supervisor_id',
        'project_id',
        'status',
    ];

    public function batch_student() {
        return $this->belongsTo(BatchStudent::class);
    }

    public function supervisor() {
        return $this->belongsTo(Supervisor::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }
}

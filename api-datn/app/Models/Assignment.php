<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'project_id',
        'project_term_id',
        'status',
        'counter_argument_id', // id giảng viên phản biện
        'counter_argument_status', // trạng thái chấm đề cương phản biện
        'counter_argument_comment' // nhận xét phản biện
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function assignment_supervisors()
    {
        return $this->hasMany(AssignmentSupervisor::class);
    }

    public function project_term() {
        return $this->belongsTo(ProjectTerm::class);
    }

    public function council_project()
    {
        return $this->hasOne(CouncilProjects::class);
    }
}

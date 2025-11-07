<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'project_term_id',
        'max_students',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function project_term()
    {
        return $this->belongsTo(ProjectTerm::class);
    }

    public function assignment_supervisors()
    {
        return $this->hasMany(AssignmentSupervisor::class);
    }

    public function council_members()
    {
        return $this->hasMany(CouncilMembers::class);
    }

    public function council_projects()
    {
        return $this->hasMany(CouncilProjects::class, 'reviewer_id');
    }

    public function commentLogs() {
        return $this->hasMany(CommentLog::class);
    }

    public function proposeTopics() {
        return $this->hasMany(ProposedTopic::class);
    }
}

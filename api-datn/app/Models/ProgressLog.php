<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressLog extends Model
{
    use HasFactory;
    protected $table = 'progress_logs';
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'content',
        'start_date_time',
        'end_date_time',
        'instructor_comment',
        'student_status',
        'instructor_status',
    ];

    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
    ];

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function commentLogs() {
        return $this->hasMany(CommentLog::class);
    }
}
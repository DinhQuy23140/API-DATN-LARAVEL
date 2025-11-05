<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'progress_log_id',
        'supervisor_id',
        'content',
    ];

    public function progressLog() {
        return $this->belongsTo(ProgressLog::class);
    }

    public function supervisor() {
        return $this->belongsTo(Supervisor::class);
    }
}

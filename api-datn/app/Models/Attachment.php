<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'progress_log_id',
        'file_name',
        'file_url',
        'file_type',
        'upload_time',
    ];

    public function progressLog()
    {
        return $this->belongsTo(ProgressLog::class);
    }
}

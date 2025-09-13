<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportFiles extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'file_name',
        'file_url',
        'file_type',
        'type_report'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

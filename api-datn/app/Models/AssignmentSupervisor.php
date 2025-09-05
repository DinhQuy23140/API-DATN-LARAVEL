<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSupervisor extends Model
{
    use HasFactory;
    protected $fillable = [
        'assignment_id',
        'supervisor_id',
        'role',
        'status',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}

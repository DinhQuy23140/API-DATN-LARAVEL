<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouncilMembers extends Model
{
    use HasFactory;

    protected $fillable = [
        'council_id',
        'supervisor_id',
        'role',
        'order',
        'number_student',
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}

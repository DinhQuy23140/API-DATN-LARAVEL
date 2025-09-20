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
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function council_project() {
        return $this->hasMany(CouncilProjectDefences::class, 'council_member_id', 'id');
    }

    public function council_project_defences() {
        return $this->hasMany(CouncilProjectDefences::class, 'council_member_id', 'id');
    }
}

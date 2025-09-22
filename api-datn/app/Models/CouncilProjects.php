<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouncilProjects extends Model
{
    use HasFactory;
    protected $fillable = [
        'council_id',
        'assignment_id',
        'council_member_id',
        'room',
        'date',
        'time',
        'review_score',
    ];

    public function council()
    {
        return $this->belongsTo(Council::class, 'council_id');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
    
    public function council_member()
    {
        return $this->belongsTo(CouncilMembers::class, 'council_member_id');
    }

    public function council_project_defences()
    {
        return $this->hasMany(CouncilProjectDefences::class, 'council_project_id', 'id');
    }
}

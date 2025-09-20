<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouncilProjectDefences extends Model
{
    use HasFactory;
    protected $fillable = [
        'council_project_id',
        'council_member_id',
        'score',
        'comments',
    ];

    public function council_project()
    {
        return $this->belongsTo(CouncilProjects::class, 'council_project_id');
    }

    public function council_member()
    {
        return $this->belongsTo(CouncilMembers::class, 'council_member_id');
    }
}

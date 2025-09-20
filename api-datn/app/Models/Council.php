<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Council extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'description', 'project_term_id', 'department_id', 'address', 'date', 'status'];

    public function project_term()
    {
        return $this->belongsTo(ProjectTerm::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function council_members()
    {
        return $this->hasMany(CouncilMembers::class);
    }

    public function council_projects()
    {
        return $this->hasMany(CouncilProjects::class, 'council_id');
    }
}

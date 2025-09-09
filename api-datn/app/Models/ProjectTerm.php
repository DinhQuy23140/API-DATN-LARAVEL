<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTerm extends Model
{
    use HasFactory;
    protected $fillable = ['academy_year_id', 'stage', 'description', 'start_date', 'end_date', 'status'];

    public function academy_year()
    {
        return $this->belongsTo(AcademyYear::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function supervisors()
    {
        return $this-> hasMany(Supervisor::class);
    }

    public function stageTimelines()
    {
        return $this->hasMany(stage_timeline::class);
    }
}

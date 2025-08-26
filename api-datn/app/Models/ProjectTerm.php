<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTerm extends Model
{
    use HasFactory;
    protected $fillable = ['academy_year_id', 'term_name', 'start_date', 'end_date'];

    public function academy_year()
    {
        return $this->belongsTo(AcademyYear::class);
    }

    public function batch_students()
    {
        return $this->hasMany(BatchStudent::class);
    }

    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }
}

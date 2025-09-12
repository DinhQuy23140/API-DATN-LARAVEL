<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marjor extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'description', 'faculty_id'];

    public function faculties()
    {
        return $this->belongsTo(Faculties::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function cohorts()
    {
        return $this->hasMany(Cohort::class);
    }
}

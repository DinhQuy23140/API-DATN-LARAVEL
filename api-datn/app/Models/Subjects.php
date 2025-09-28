<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\lecturerSubjects;

class Subjects extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'description',
        'number_of_credits',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function lecturer_subjects()
    {
        return $this->hasMany(lecturerSubjects::class, 'subject_id');
    }
}

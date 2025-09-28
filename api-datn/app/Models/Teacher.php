<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_code',
        'degree',
        'department_id',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->hasOne(Supervisor::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculties::class, 'faculty_id');
    }

    public function departmentRoles()
    {
        return $this->hasMany(departmentRoles::class, 'teacher_id');
    }

    public function lecturerSubjects()
    {
        return $this->hasMany(lecturerSubjects::class, 'teacher_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Faculties;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'description', 'faculty_id'];
    
    public function faculties() {
        return $this->belongsTo(Faculties::class, 'faculty_id');
    }

    public function teachers() {
        return $this->hasMany(Teacher::class);
    }

    public function councils()
    {
        return $this->hasMany(Council::class);
    }

    public function departmentRoles()
    {
        return $this->hasMany(departmentRoles::class, 'department_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subjects::class, 'department_id');
    }

    public function marjors()
    {
        return $this->hasMany(Marjor::class);
    }

}

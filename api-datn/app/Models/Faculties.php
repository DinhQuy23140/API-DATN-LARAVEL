<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Faculties extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'short_name', 'description' ,'assistant_id', 'dean_id', 'vice_dean_id', 'phone', 'email', 'address'];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function assistant()
    {
        return $this->belongsTo(Teacher::class, 'assistant_id');
    }

    public function dean()
    {
        return $this->belongsTo(Teacher::class, 'dean_id');
    }

    public function viceDean()
    {
        return $this->belongsTo(Teacher::class, 'vice_dean_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'faculty_id');
    }

    public function facultyRoles() {
        return $this->hasMany(FacultyRoles::class, 'faculty_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departmentRoles extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id',
        'teacher_id',
        'role',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}

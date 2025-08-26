<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Batch_student;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_code',
        'class_code',
        'major_id',
        'department_id',
        'course_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch_students() {
        return $this->hasMany(BatchStudent::class);
    }

}

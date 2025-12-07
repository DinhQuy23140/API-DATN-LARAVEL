<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Batch_student;

class Student extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id',
    //     'student_code',
    //     'class_code',
    //     'major_id',
    //     'department_id',
    //     'course_year',
    // ];
        protected $fillable = [
        'user_id',
        'student_code',
        'class_code',
        'marjor_id',
        'course_year',
        'graduation_project',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignment() {
        return $this->hasOne(Assignment::class);    
    }

    public function marjor() {
        return $this->belongsTo(Marjor::class);
    }

    public function registerProjectTerms()
    {
        return $this->hasMany(RegisterProjectTerm::class);
    }

}

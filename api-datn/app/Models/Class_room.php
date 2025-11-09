<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_room extends Model
{
    use HasFactory;
    protected $fillable = [ 'class_code', 'class_name', 'number_students', 'admission_year', 'cohort', 'description', 'marjor_id', 'department_id' ];

    public function marjor() {
        return $this->belongsTo(Marjor::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    // public function homeroom_teacher() {
    //     return $this->belongsTo(Teacher::class);
    // }
}

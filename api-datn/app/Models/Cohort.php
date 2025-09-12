<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    use HasFactory;
    protected $fillable = ['number_course', 'year_of_admission', 'number_students', 'marjor_id'];
    
    public function marjor()
    {
        return $this->belongsTo(Marjor::class);
    }

    public function class_rooms()
    {
        return $this->hasMany(Class_room::class);
    }
}

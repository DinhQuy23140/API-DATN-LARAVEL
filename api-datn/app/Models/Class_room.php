<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_room extends Model
{
    use HasFactory;
    protected $fillable = ['class_code', 'class_name', 'number_students', 'cohort_id'];
    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }
}

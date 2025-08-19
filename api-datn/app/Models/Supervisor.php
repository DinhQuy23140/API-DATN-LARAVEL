<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'max_students',
        'expertise',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function assignment()
    {
        return $this->hasMany(Assignment::class);
    }
}

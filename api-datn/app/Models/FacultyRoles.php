<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultyRoles extends Model
{
    use HasFactory;
    protected $fillable = [
        'faculty_id',
        'user_id',
        'role',
    ];

    public function faculty() {
        return $this->belongsTo(Faculties::class, 'faculty_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

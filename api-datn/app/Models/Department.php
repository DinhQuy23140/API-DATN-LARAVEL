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
}

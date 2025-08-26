<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademyYear extends Model
{
    use HasFactory;
    protected $fillable = ['year_name'];

    public function project_terms()
    {
        return $this->hasMany(ProjectTerm::class);
    }
}

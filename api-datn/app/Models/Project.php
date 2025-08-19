<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
    ];

    public function assignment()
    {
        return $this->hasMany(Assignment::class);
    }

    public function progressLog()
    {
        return $this->hasMany(ProgressLog::class);
    }
}

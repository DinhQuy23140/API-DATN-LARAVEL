<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Faculties extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'description'];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function marjors()
    {
        return $this->hasMany(Marjor::class);
    }
}

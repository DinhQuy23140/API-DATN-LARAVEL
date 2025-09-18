<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouncilProjectDefences extends Model
{
    use HasFactory;
    protected $fillable = ['council_id', 'assignment_id', 'reviewer_id', 'room', 'date', 'time'];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Supervisor::class, 'reviewer_id');
    }
}

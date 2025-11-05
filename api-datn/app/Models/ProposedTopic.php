<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposedTopic extends Model
{
    use HasFactory;
    protected $fillable = [
        'supervisor_id',
        'title',
        'description',
        'proposed_at',
    ];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}

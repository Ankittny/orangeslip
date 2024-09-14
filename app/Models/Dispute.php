<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispute extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'hr_id', 'candidate_id', 'reported_by', 'is_resolved', 'candidate_agreed_to_resolution', 
        'hr_agreed_to_resolution', 'resolve_date'
        
    ];

    protected $dates = [
        'deleted_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobRole extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name', 'status','industry_id'
        
    ];

    protected $dates = [
        'deleted_at'
    ];
    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateOtherDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'candidate_id','type', 'value','description'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}

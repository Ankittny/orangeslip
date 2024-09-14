<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Verification extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'candidate_id', 'hr_id', 'verification_type'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function candidate()
    {
        return $this->belongsTo(CandidateDetail::class, 'candidate_id');
    }
    public function hr()
    {
        return $this->belongsTo(User::class, 'hr_id');
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    public function businessUser()
    {
        return $this->belongsTo(User::class, 'business_id');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferLetter extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [

        
 
'candidate_id','hr_id','business_id','post','joining_date','place_of_joining','time_of_joining','annual_ctc','salary_breakup','offer_letter','is_accepted','rejected_reason','is_rescheduled','joining_confirmed'
        
    ];

    protected $dates = [
        'deleted_at'
    ];


    public function hrDetails()
    {
        return $this->belongsTo(User::class, 'hr_id');
    }
    public function businessDetails()
    {
        return $this->belongsTo(User::class, 'business_id');
    }
    public function candidateDetails()
    {
        return $this->belongsTo(CandidateDetail::class, 'candidate_id');
    }
    public function jobRole()
    {
        return $this->belongsTo(JobRole::class, 'post');
    }
}

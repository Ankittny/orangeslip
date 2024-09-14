<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateDetail extends Model
{
    use SoftDeletes;


    protected $fillable = [
'user_id','candidate_code','name','email','phone','phone2','gender','dob','age','country','state','city','religion','marital_status','father_name','mother_name','spouse_name','present_address','permanent_address','job_role','total_experience','caste','expected_salary','area_of_interest','joining_date_prefer','photo','signature','cv_scan','added_by','hr_id','business_id','is_selected','offer_letter_generated','joining_confirmed','rating','behaviour','timely_response','communication_skill','review','status','assign_to','pan_no','pan_file','aadhaar_no','aadhaar_file','dl_no','dl_file','dl_exp_date','passport_no','passport_file','passport_exp_date','physical_joinig_point'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function candidateAward()
    {
        return $this->hasMany(CandidateAward::class, 'candidate_id');
    }

    public function candidateCarrierBreak()
    {
        return $this->hasMany(CandidateCarrierBreak::class, 'candidate_id');
    }

    public function candidateCertificate()
    {
        return $this->hasMany(CandidateCertificate::class, 'candidate_id');
    }

    public function candidateLanguage()
    {
        return $this->hasMany(CandidateLanguage::class, 'candidate_id');
    }

    public function candidateProfessionalDetail()
    {
        return $this->hasMany(CandidateProfessionalDetail::class, 'candidate_id');
    }

    public function candidateProfessionalQualification()
    {
        return $this->hasMany(CandidateProfessionalQualification::class, 'candidate_id');
    }

    public function candidateQualification()
    {
        return $this->hasMany(CandidateQualification::class, 'candidate_id');
    }

    public function candidateSalaryDetail()
    {
        return $this->hasMany(CandidateSalaryDetail::class, 'candidate_id');
    }

    public function candidateSkill()
    {
        return $this->hasMany(CandidateSkill::class, 'candidate_id');
    }

    public function candidateVerification()
    {
        return $this->hasMany(CandidateVerification::class, 'candidate_id');
    }

    public function candidateWorkExperience()
    {
        return $this->hasMany(CandidateWorkExperience::class, 'candidate_id');
    }

    public function countryDetails()
    {
        return $this->belongsTo(Country::class, 'country');
    }
    public function stateDetails()
    {
        return $this->belongsTo(State::class, 'state','state_id');
    }
    public function cityDetails()
    {
        return $this->belongsTo(City::class, 'city');
    }
    public function hrDetails()
    {
        return $this->belongsTo(User::class, 'hr_id');
    }
    public function assignTo()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }
    public function businessDetails()
    {
        return $this->belongsTo(User::class, 'business_id');        
    }
    public function jobRole()
    {
        return $this->belongsTo(JobRole::class, 'job_role');        
    }


}

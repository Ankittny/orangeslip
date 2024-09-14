<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateProfessionalDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        

       'candidate_id',
	'company_name',		
	'job_role',		
	'from_date',	
	'to_date',		
	'current_company',	
	'current_location',		
		'current_salary',	
	'descriptionp'	
	
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}

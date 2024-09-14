<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateEducationDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
       
        	'candidate_id',	'education_type',	'institute_name',	'degree',	'specialization',	'year_of_passing',	'marks',	'percentage',	'doc_file',	'status'	

    ];

    protected $dates = [
        'deleted_at'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
    public function educationType()
    {
        return $this->belongsTo(EducationMaster::class, 'education_type');
    }
    public function course()
    {
        return $this->belongsTo(CourseMaster::class, 'degree');
    }
}

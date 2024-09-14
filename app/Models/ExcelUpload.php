<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ExcelUpload extends Model
{
    // use SoftDeletes;
    protected $table="excel_uploads";

    protected $fillable = ['name','email_id','alternate_number','date_of_birth','mobile_no','functional_area','area_of_specialization','industry','resume_title','key_skills','work_experience','current_employer',
    'previous_employer',
    'current_salary',
    'level',
    'current_location',
    'preferred_location',
    'course_highest_education',
    'specialization_highest_education',
    'institute_highest_education',
    'course_2nd_highest_education',
    'specialization_2nd_highest_education',
    'last_active_date',
    'institute_2nd_highest_education',
    'gender','age','address','hr_id','business_id','uploaded_by','current_joining_date','previous_joining_date'];

   


}

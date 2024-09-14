<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnrollCompany extends Model
{
    use SoftDeletes;
    protected $table='enroll_companies';

    protected $fillable = [
        'business_name','email','owner_first_name','owner_last_name','mobile_no','no_of_employee','verifier_id','creator_id','country','lead_staff_id'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function Verifier(){
        return $this->belongsTo(User::class, 'verifier_id');
    }
    public function Creator(){
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function Agent(){
        return $this->belongsTo(User::class, 'lead_staff_id');
    }
    public function noOfEmp(){
        return $this->belongsTo(noOfEmployeeRange::class, 'no_of_employee');
    }
    public function countryDetails(){
        return $this->belongsTo(Country::class, 'country');
    }
}
<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_name','registration_date','registration_doc','business_address','logo','user_id','contact_persons','no_of_employee','gst','pan'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function noOfEmp(){
        return $this->belongsTo(noOfEmployeeRange::class, 'no_of_employee');
    }
  
}
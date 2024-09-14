<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Otp extends Model
{
    use SoftDeletes;
    Protected $table='otp';

    protected $fillable = [
        'mobile_or_email','otp'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

   
    
      
}

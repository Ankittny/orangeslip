<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'mobile_no', 'user_id','gender','address','city','profile_image','religion','maritial_status',
        'dob','country'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function countryDetails()
    {
        return $this->belongsTo(Country::class, 'country');
    }
    
      
}

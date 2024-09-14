<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Industry extends Model
{
    
    Protected $table='industries';

    protected $fillable = [
        'name'
        
    ];

    

    // public function candidate()
    // {
    //     return $this->hasMany(CandidateDetail::class, 'country');
    // }
     
    // public function state()
    // {
    //     return $this->hasMany(State::class, 'country_id');
    // }
     
    
      
}

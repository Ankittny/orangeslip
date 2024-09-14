<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    Protected $table='countries';

    protected $fillable = [
        'name'
        
    ];

    protected $dates = [
        'deleted_at'
    ];


    public function candidate()
    {
        return $this->hasMany(CandidateDetail::class, 'country');
    }
     
    public function state()
    {
        return $this->hasMany(State::class, 'country_id');
    }
     
    
      
}

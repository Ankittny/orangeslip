<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;
    Protected $table='state';

    protected $fillable = [
        'state_id', 'country_id','state_title'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

     
    
      
}

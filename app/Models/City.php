<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;
    Protected $table='city';

    protected $fillable = [
        'state_id', 'name'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id','state_id');
    }
    
      
}

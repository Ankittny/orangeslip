<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
   // use SoftDeletes;


    protected $fillable = [
        'name', 'title'
        
    ];

    // protected $dates = [
    //     'deleted_at'
    // ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
      
}

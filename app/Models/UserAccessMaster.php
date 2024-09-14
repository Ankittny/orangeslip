<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccessMaster extends Model
{
    use SoftDeletes;
    protected $table ='user_access_master';

    protected $fillable = [
        'name','title','status'


    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
     
    
      
}

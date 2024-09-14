<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndividualUserAccess extends Model
{
    use SoftDeletes;
    protected $table ='individual_user_access';

    protected $fillable = [
        'user_id','access_id','access_status'


    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function accessDetails()
    {
        return $this->belongsTo(UserAccessMaster::class, 'access_id');
    }
    
      
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessReviewDetail extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'user_id','business_id','review', 'comment'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    
    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function businessDetails()
    {
        return $this->belongsTo(User::class, 'business_id');
        
    }


}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerificationStaff extends Model
{
    use SoftDeletes;
    protected $table = 'verification_staffs';


    protected $fillable = [
        'user_id','department'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'user_id');
    }
      
}

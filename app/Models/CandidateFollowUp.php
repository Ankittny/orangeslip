<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateFollowUp extends Model
{
    use SoftDeletes;
    protected $table='candidate_follow_up';

    protected $fillable = [
        'candidate_id','hr_id','date','remarks','next_contact_date'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'hr_id');
    }
}
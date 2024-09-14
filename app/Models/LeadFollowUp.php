<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadFollowUp extends Model
{
    use SoftDeletes;
    protected $table='lead_follow_up';

    protected $fillable = [
        'lead_id','agent_id','date','remarks','next_contact_date'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function leadDetails(){
        return $this->belongsTo(EnrollCompany::class, 'lead_id');
    }
}
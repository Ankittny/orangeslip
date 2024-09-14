<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateVerification extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'candidate_id', 'hr_id', 'company_id', 'payment_confirm', 'assigned_staff', 'verified', 'staff_response', 'supporting_document', 'hr_informed'
        
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CandidateDocument extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="candidate_documents";
    protected $fillable = [
        'candidate_id', 'doc_type','doc_name','doc_file'
        
    ];

    protected $dates = [
        'deleted_at'
    ];
    
    public function candidateDetails()
    {
        return $this->belongsTo(CandidateDetail::class, 'candidate_id');        
    }
    public function docType()
    {
        return $this->belongsTo(DocumentType::class, 'doc_type');        
    }
}

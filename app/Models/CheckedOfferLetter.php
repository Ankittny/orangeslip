<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckedOfferLetter extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="checked_offer_letters";

    protected $fillable = [

        
 
'user_id','offer_letter_id'
        
    ];

    protected $dates = [
        'deleted_at'
    ];


    public function offerLetter()
    {
        return $this->belongsTo(OfferLetter::class, 'offer_letter_id');
    }
    
}

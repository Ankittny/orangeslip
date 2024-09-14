<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
                'user_id','currency_id','reference_no','type','source','description','amount','updated_balance','payment_confirmation_date','status','transaction_id'

    ];

    protected $dates = [
        'deleted_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

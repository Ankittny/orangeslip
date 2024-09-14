<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;
    protected $table = 'deposits';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}

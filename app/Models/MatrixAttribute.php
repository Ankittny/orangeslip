<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrixAttribute extends Model
{
   
    protected $table="matrix_attributes";
    protected $fillable = [
        'name', 'title','min_point','max_point','category'
        
    ];

    
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class noOfEmployeeRange extends Model
{
  
    protected $table='no_of_employee_range';

    protected $fillable = [
        'range_start','range_end'
    ];

     
     
   
}
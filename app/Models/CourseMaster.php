<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseMaster extends Model
{
     
    protected $table ='course_masters';

    protected $fillable = [
        'education_master_id','course_name'
    ];

     
    public function education()
    {
        return $this->belongsToMany(EducationMaster::class);
    }
     
    
      
}
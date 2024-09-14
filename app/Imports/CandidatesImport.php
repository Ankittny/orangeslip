<?php

namespace App\Imports;


use App\Models\State;
use App\Models\City;
use App\Models\JobRole;
use App\Models\CandidateBulkData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class CandidatesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user=Auth::user();
        $hr_id=$user->id;
        $business_id=$user->parent_id;
        //dd($row);
        return new CandidateBulkData([ 

                            "name" => $row['name'],             
                            "email" => $row['email'],
                            "phone" => $row['phone'],
                           // "dob" => $row['dob'],
                            "gender" => strtolower($row['gender']),
                            "state" =>  $row['state'],
                            "city" => $row['city'],
                            "job_role" => $row['job_role'],
                            "total_experience" => $row['experience'],
                            "added_by"=>   $hr_id,         
                            "hr_id"=>   $hr_id,         
                            "business_id"=>   $business_id     
                    ]);
        
        // if(isset($row['name']))
        // {
                        
        //     CandidateBulkData::create([ 

        //                 "name" => $row['name'],             
        //                 "email" => $row['email'],
        //                 "phone" => $row['phone'],
        //                 "gender" => $row['gender'],
        //                 "state" =>  $row['state'],
        //                 "city" => $row['city'],
        //                 "job_role" => $row['job_role'],
        //                 "total_experience" => $row['experience'],
        //                 "added_by"=>   $hr_id,         
        //                 "hr_id"=>   $hr_id,         
        //                 "business_id"=>   $business_id     
        //         ]);
        //         DB::commit();
        // }
    
        
    }

    public function headingRow(): int
    {
        return 1;
    }
}

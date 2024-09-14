<?php

namespace App\Imports;

use App\Models\ExcelUpload;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Auth;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithUpserts;

class UsersImport implements ToModel,WithHeadingRow,WithBatchInserts,WithUpserts
{
    /**
     * @param array $row
     *
     * @return ExcelUpload|null
     */
    
    public function model(array $row)
    {
       
        $user=Auth::user();
        if($user->account_type=='hr'){
            $hr_id=$user->id;
            $business_id=$user->parent_id;
            $uploaded_by=$user->id;
        }
        if($user->account_type=='superadmin'){
            $hr_id=0;
            $business_id=0;
            $uploaded_by=$user->id;
        }
       
        return new ExcelUpload([
          

           'name'     => $row['name'],
           'email_id'    => $row['email_id'],
           'alternate_number'    => $row['alternate_number'],
           'date_of_birth'    => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth'])),
           'mobile_no'    => $row['mobile_no'],
           'functional_area'    => $row['functional_area'],
           'area_of_specialization'    => $row['area_of_specialization'],
           'industry'    => $row['industry'],
           'resume_title'    => $row['resume_title'],
           'key_skills'    => $row['key_skills'],
           'work_experience'    => $row['work_experience'],
           'current_employer'    => $row['current_employer'],
           'current_joining_date'    => isset($row['current_joining_date'])?Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['current_joining_date'])):'',
           'previous_employer'    => $row['previous_employer'],
           'previous_joining_date'    => isset($row['previous_joining_date'])?Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['previous_joining_date'])):'',
           'current_salary'    => $row['current_salary'],
           'level'    => $row['level'],
           'current_location'    => $row['current_location'],
           'preferred_location'    => $row['preferred_location'],
           'course_highest_education'    => $row['course_highest_education'],
           'specialization_highest_education'    => $row['specialization_highest_education'],
           'institute_highest_education'    => $row['institute_highest_education'],
           'course_2nd_highest_education'    => $row['course_2nd_highest_education'],
           'specialization_2nd_highest_education'    => $row['specialization_2nd_highest_education'],
           'institute_2nd_highest_education'    => $row['institute_2nd_highest_education'],           
           'gender'    => $row['gender'],
           'age'    => $row['age'],
           'address'    => $row['address'],
           'hr_id'=>   $hr_id,         
           'business_id'=>   $business_id,
           'uploaded_by'=>   $uploaded_by,
           
           
        ]);
        
    }

    public function batchSize(): int
    {
        return 1005;
    }

    public function uniqueBy()
    {
        return 'email_id';
    }
    // public function headingRow(): int
    // {
    //     return 2;
    // }
}
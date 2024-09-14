<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use App\Models\CandidateDetail;
use App\Models\CandidateEducationDetail;
use App\Models\CandidateProfessionalDetail;
use App\Models\CandidateOtherDetail;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\OfferLetter;
use App\Models\CandidateFollowUp;
use App\Models\IndividualUserAccess;
use App\Models\JobRole;
use App\Models\CandidateBulkData;
use App\Models\ExcelUpload;
use App\Models\CandidateDocument;
use App\Models\DocumentType;
use App\Libs\CommonHelper;
use DB;
use Str;
use Auth;
use Session;
use PDF;
use Form;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferLetterGenerated;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CandidateDetailsImport;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

class CandidateController extends Controller
{
   

   
    public function exportCSV()
    {
        $user = Auth::user();
         

        $csvID=rand(111111,999999);
        //dd($csvID);

        $insert_csv_id=User::where('id',$user->id)->update(['csv'=>$csvID]);
        $data=[
            'url'=>'https://orangeslip.com/candidate_list_csv/'.$csvID,
            'id'=>$csvID
        ];

        return response()->json(['status' => true, 'msg' => 'success', 'data'=>$data]);
        
    }

 

    public function chkUserAccess($hr_id,$access_id)
    {                     
    /**
     * for check HR permission in controller using parameter
     */
        $user_access=IndividualUserAccess::where([['user_id','=',$hr_id],['access_id','=',$access_id],['access_status','=',1]])->first();
        //dd($user_access);
        if($user_access==Null)
        {
            return $status=0; //no permission
        }
        else{
            return $status=$user_access->user_id;
        }
        
    }
    public function chkHrAccess(Request $request)
    {               
        /**
         * for check HR permission from API 
         */      
    //dd($request->access_id);
        $user_access=IndividualUserAccess::where([['user_id','=',$request->hr_id],['access_status','=',1]])->get();
        // dd($user_access);
        
        return response()->json([
            'status'=>true,           
            'data'=>$user_access,
            'msg'=>'success'
        ]);
        
    }

    public function importBulkData()
    {
        /**
         * for candidate bulk data upload
         * input:excel file
         * output: return with success/error
         */
        //    return redirect('bulk_upload')->with('error','Please try after some time.');
        $role=Auth::user()->account_type;
        $status=0;
        
        if($role=='hr')
        {
            $no=1;
            $status=$this->chkUserAccess(Auth::user()->id,3);
            if($status!=0){     
                 
                $dupCount=0;
                $importCount=0;
                
                $allData=ExcelUpload::where('business_id','=',Auth::user()->parent_id)->get()->toArray();
                $allCan=CandidateDetail::where('business_id','=',Auth::user()->parent_id)->pluck('email')->toArray();
                $code='ORG'.Auth::user()->id.Auth::user()->parent_id;
                        
            }
            else{
               
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);

            }
        }
        elseif($role=='superadmin')
        {
            $no=1;
            $status=1;
            if($status!=0){     
                 
                $dupCount=0;
                $importCount=0;
                
                $allData=ExcelUpload::where('uploaded_by','=',Auth::user()->id)->get()->toArray();
                $allCan=CandidateDetail::where('added_by',Auth::user()->id)->pluck('email')->toArray();
                $code='ORG';
            }
            else{
                // return abort(403,"You do not have permission for this");
                // return redirect()->back()->with('error','You do not have permission for this');
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);


            }
        }
        else{
            $status=0;
            // return abort(403,"You do not have permission for this");
            // return redirect()->back()->with('error','You do not have permission for this');
            return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);


        }
            if($status!=0)
            {    
             
                foreach($allData as $data){
                    $canEmail=$data['email_id'];
                    $num_of_row=CandidateDetail::count();
                    $candidate_code=$code.($num_of_row+1);
                    if (in_array($canEmail, $allCan))
                    {
                        //echo "Match found,";
                        $duplicateData=ExcelUpload::where('id','=',$data['id'])->update(['status'=>'Duplicate']);
                        $dupCount++;
                        //return redirect('bulk_upload')->with('error','No Data for import.');
                    }
                    else
                    {
                        
                        
                        DB::transaction(function () use ($candidate_code,$data,$importCount) {

 
                             //Candidate Details 
                            $candidateDetail=CandidateDetail::create(['candidate_code'=>$candidate_code,'name'=>$data['name'],'email'=>$data['email_id'],'phone'=>$data['mobile_no'],'phone2'=>$data['alternate_number'], 'dob'=> date('Y-m-d',strtotime($data['date_of_birth'])),'gender'=>$data['gender'],'added_by'=>$data['uploaded_by'],'business_id'=>$data['business_id'],'hr_id'=>$data['hr_id'],'assign_to'=>$data['hr_id'],'total_experience'=>$data['work_experience'],'present_address'=>$data['address'],      'resume_title'=> $data['resume_title'],'age'=> $data['age']]);
                         
                            //Education Details

                            if($data['course_highest_education']!=''){
                                $course=CourseMaster::where('course_name',$data['course_highest_education'])->first();
                        
                                if($course){
                                    CandidateEducationDetail::create(['candidate_id'=>$candidateDetail->id,'institute_name'=>$data['institute_highest_education'],'education_type'=>$course->education_master_id,'degree'=>$course->id,'specialization'=>$data['specialization_highest_education']]);
                                }
                            }

                            if($data['course_2nd_highest_education']!=''){
                                $course=CourseMaster::where('course_name',$data['course_2nd_highest_education'])->first();
                        
                                if($course){
                                    CandidateEducationDetail::create(['candidate_id'=>$candidateDetail->id,'institute_name'=>$data['institute_2nd_highest_education'],'education_type'=>$course->education_master_id,'degree'=>$course->id,'specialization'=>$data['specialization_2nd_highest_education']]);
                                }
                            }

                            //Professional details
                            if($data['current_employer']!=''){
                                CandidateProfessionalDetail::create(['candidate_id'=>$candidateDetail->id,'company_name'=>$data['current_employer'],'from_date'=>date('Y-m-d',strtotime($data['current_joining_date'])),'current_company'=>'yes','current_salary'=>floatval($data['current_salary']),'current_location'=>$data['current_location']]);
                            }
                            if($data['previous_employer']!=''){
                                CandidateProfessionalDetail::create(['candidate_id'=>$candidateDetail->id,'company_name'=>$data['previous_employer'],'from_date'=>date('Y-m-d',strtotime($data['previous_joining_date'])),'current_company'=>'no','to_date'=>date('Y-m-d',strtotime($data['current_joining_date']))]);
                            }

                            //Other Details
                            if($data['key_skills']!=''){
                                // $skills=explode(array(",",".","/",":"),$data['key_skills']);
                                
                                $skills=explode(",",$data['key_skills']);
                                foreach($skills as $skill)
                                {
                                    $exist=DB::table('skills_masters')->where(['name'=>$skill])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('skills_masters')->insert(['name'=>$skill]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'skill','value'=>$skill]);
                                }
                            }
                            
                            if($data['functional_area']!=''){
                                $all_functional_area=explode(",",$data['functional_area']);
                                foreach($all_functional_area as $functional_area)
                                {
                                    $exist=DB::table('functional_area_masters')->where(['name'=>$functional_area])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('functional_area_masters')->insert(['name'=>$functional_area]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'functional_area','value'=>$functional_area]);
                                }
                            }
                            if($data['industry']!=''){
                                $all_industry=explode(",",$data['industry']);
                                foreach($all_industry as $industry)
                                {
                                    $exist=DB::table('industries')->where(['name'=>$industry])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('industries')->insert(['name'=>$industry]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'industry','value'=>$industry]);
                                }
                            }
                            if($data['area_of_specialization']!=''){
                                $all_area_of_specialization=explode(",",$data['area_of_specialization']);
                                foreach($all_area_of_specialization as $area_of_specialization)
                                {
                                    $exist=DB::table('area_of_specialization_masters')->where(['name'=>$area_of_specialization])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('area_of_specialization_masters')->insert(['name'=>$area_of_specialization]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'area_of_specialization','value'=>$area_of_specialization]);
                                }
                            }
                            if($data['preferred_location']!=''){
                                $all_preferred_location=explode(",",$data['preferred_location']);
                                foreach($all_preferred_location as $preferred_location)
                                {
                                    $exist=DB::table('preferred_location_masters')->where(['name'=>$preferred_location])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('preferred_location_masters')->insert(['name'=>$preferred_location]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'preferred_location','value'=>$preferred_location]);
                                }
                            }
                            if($data['level']!=''){
                                $all_level=explode(",",$data['level']);
                                foreach($all_level as $level)
                                {
                                    $exist=DB::table('level_masters')->where(['name'=>$level])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('level_masters')->insert(['name'=>$level]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'level','value'=>$level]);
                                }
                            }

                                
                            $deleteData=ExcelUpload::find($data['id']);
                            $deleteData->delete();
                           
                            $importCount++;

                        });
                       
                        
                    }

                }
                $msg='';
                if($dupCount > 0){
                    $msg .=$dupCount.' Data found Duplicate and Deleted Successfully';

                }
                if($importCount > 0){
                    $msg .=$importCount.' Data Imported Successfully';
                    
                }

                $deleteDuplicateData=ExcelUpload::where('business_id',$data['business_id'])->delete();               
                
                // return redirect('bulk_upload')->with('success',$msg);
                return response()->json(['status' => true, 'msg' => $msg, 'data'=>1]);

            }else{
               
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);

            }
            
    }

    public function UploadView()
    {
         
         # For Bulkdata Upload Page View
         
        $role=Auth::user()->account_type;
        if($role=='hr')
        {
            $status=$this->chkUserAccess(Auth::user()->id,3);
            if($status!=0){     
                $no=1;
               $allData=ExcelUpload::where('business_id','=',Auth::user()->parent_id)->orderBy('id','DESC')->paginate(10);
               
                // return view('admin.candidate.bulkupload',compact('allData','no'));
                return response()->json(['status' => true, 'msg' => 'Success', 'data'=>$allData]);
            }
            else{
                // return abort(403,"You do not have permission for this");
                // return redirect()->back()->with('error','You do not have permission for this');
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        
        }
       elseif($role=='superadmin')
        {
             
            
                $no=1;
               $allData=ExcelUpload::where('uploaded_by',auth()->user()->id)->orderBy('id','DESC')->paginate(10);
               
                // return view('admin.candidate.bulkupload',compact('allData','no'));
                return response()->json(['status' => true, 'msg' => 'Success', 'data'=>$allData]);
            
        
        }
        else{
            // return abort(403,"You do not have permission for this");
            // return redirect()->back()->with('error','You do not have permission for this');
            return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
        }
         
    }

    public function Upload(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'upload'=>'required|mimes:xlsx'
        ],
        [
            'upload.required'=>'File required',
            'upload.mimes'=>'File type must be in existing xlsx',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        }

         
        try{
            Excel::import(new UsersImport, request()->file('upload'));
            // return redirect('bulk_upload')->with('success','Data uploaded Successfully.');
            return response()->json(['status' => true, 'msg' => 'Data uploaded Successfully.', 'data'=>1]);
        
        } catch (\Exception $e) {
            // return $e->getMessage();
            // return redirect('bulk_upload')->with('error','Something was wrong. Please check data sheet!');
            return response()->json(['status' => false, 'msg' => 'Something was wrong. Please check data sheet!', 'data'=>0]);
        }
    }

    /*
    public function importBulkData_old()
    {
        $role=Auth::user()->account_type;
        if($role=='hr')
        {
            $no=1;
            $status=$this->chkUserAccess(Auth::user()->id,3);
            if($status!=0){     
                 
                $dupCount=0;
                $importCount=0;
                //dd($allCan);
                $allData=CandidateBulkData::where('business_id','=',Auth::user()->parent_id)->get()->toArray();
               
                //$match=CandidateDetail::find($allData);
                //dd($allData);
                foreach($allData as $data){
                    $canEmail=$data['email_id'];
                    //dd($canEmail);
                    $allCan=CandidateDetail::where('business_id','=',Auth::user()->parent_id)->pluck('email')->toArray();
                    if (in_array($canEmail, $allCan))
                    {
                        //echo "Match found,";
                        $duplicateData=ExcelUpload::where('id','=',$data['id'])->update(['status'=>'Duplicate']);
                        $dupCount++;
                        //return redirect('bulk_upload')->with('error','No Data for import.');
                    }
                    else
                    {
                        $num_of_row=CandidateDetail::count();
                        $candidate_code='REC'.$data['hr_id'].$data['business_id'].($num_of_row+1);
                        // $state_id=State::where('state_title','=',$data['state'])->pluck('state_id')->first();

                        // // $city_id=City::whereRaw('LOWER(name)','=',strtolower($data['city']))->pluck('id')->first();
                        // $city_id=DB::table('city')->whereRaw('LOWER(`name`) = ? ',[strtolower($data['city'])])->pluck('id')->first();
         
                        // if(!$city_id){
                        //     $new_city=City::create(['name'=>$data['city'],'state_id'=>$state_id]);
                        //     $city_id=$new_city->id;

                        // }
                        // // $job_role_id=JobRole::where('name','=',$data['job_role'])->pluck('id')->first();
                        // $job_role_id=DB::table('job_roles')->whereRaw('LOWER(`name`) = ? ',[strtolower($data['job_role'])])->pluck('id')->first();

                        // if(!$job_role_id){
                        //     $new_jobRole=JobRole::create(['name'=>$data['job_role']]);
                        //     $job_role_id=$new_jobRole->id;

                        // }
                        DB::transaction(function () use ($candidate_code,$data,$importCount) {

 
                             //Candidate Details 
                            $candidateDetail=CandidateDetail::create(['candidate_code'=>$candidate_code,'name'=>$data['name'],'email'=>$data['email_id'],'phone'=>$data['mobile_no'],'phone2'=>$data['alternate_number'], 'dob'=> date('Y-m-d',strtotime($data['date_of_birth'])),'gender'=>$data['gender'],'added_by'=>$data['uploaded_by'],'business_id'=>$data['business_id'],'hr_id'=>$data['hr_id'],'assign_to'=>$data['hr_id'],'total_experience'=>$data['work_experience'],'present_address'=>$data['address'],      'resume_title'=> $data['resume_title'],'age'=> $data['age']]);
                         
                            //Education Details

                            if($data['course_highest_education']!=''){
                                $course=CourseMaster::where('course_name',$data['course_highest_education'])->first();
                        
                                if($course){
                                    CandidateEducationDetail::create(['candidate_id'=>$candidateDetail->id,'institute_name'=>$data['institute_highest_education'],'education_type'=>$course->education_master_id,'degree'=>$course->id,'specialization'=>$data['specialization_highest_education']]);
                                }
                            }

                            if($data['course_2nd_highest_education']!=''){
                                $course=CourseMaster::where('course_name',$data['course_2nd_highest_education'])->first();
                        
                                if($course){
                                    CandidateEducationDetail::create(['candidate_id'=>$candidateDetail->id,'institute_name'=>$data['institute_2nd_highest_education'],'education_type'=>$course->education_master_id,'degree'=>$course->id,'specialization'=>$data['specialization_2nd_highest_education']]);
                                }
                            }

                            //Professional details
                            if($data['current_employer']!=''){
                                CandidateProfessionalDetail::create(['candidate_id'=>$candidateDetail->id,'company_name'=>$data['current_employer'],'from_date'=>date('Y-m-d',strtotime($data['current_joining_date'])),'current_company'=>'yes','current_salary'=>floatval($data['current_salary']),'current_location'=>$data['current_location']]);
                            }
                            if($data['previous_employer']!=''){
                                CandidateProfessionalDetail::create(['candidate_id'=>$candidateDetail->id,'company_name'=>$data['previous_employer'],'from_date'=>date('Y-m-d',strtotime($data['previous_joining_date'])),'current_company'=>'no','to_date'=>date('Y-m-d',strtotime($data['current_joining_date']))]);
                            }

                            //Other Details
                            if($data['key_skills']!=''){
                                $skills=explode(",",$data['key_skills']);
                                foreach($skills as $skill)
                                {
                                    $exist=DB::table('skills_masters')->where(['name'=>$skill])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('skills_masters')->insert(['name'=>$skill]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'skill','value'=>$skill]);
                                }
                            }
                            
                            if($data['functional_area']!=''){
                                $all_functional_area=explode(",",$data['functional_area']);
                                foreach($all_functional_area as $functional_area)
                                {
                                    $exist=DB::table('functional_area_masters')->where(['name'=>$functional_area])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('functional_area_masters')->insert(['name'=>$functional_area]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'functional_area','value'=>$functional_area]);
                                }
                            }
                            if($data['industry']!=''){
                                $all_industry=explode(",",$data['industry']);
                                foreach($all_industry as $industry)
                                {
                                    $exist=DB::table('industries')->where(['name'=>$industry])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('industries')->insert(['name'=>$industry]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'industry','value'=>$industry]);
                                }
                            }
                            if($data['area_of_specialization']!=''){
                                $all_area_of_specialization=explode(",",$data['area_of_specialization']);
                                foreach($all_area_of_specialization as $area_of_specialization)
                                {
                                    $exist=DB::table('area_of_specialization_masters')->where(['name'=>$area_of_specialization])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('area_of_specialization_masters')->insert(['name'=>$area_of_specialization]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'area_of_specialization','value'=>$area_of_specialization]);
                                }
                            }
                            if($data['preferred_location']!=''){
                                $all_preferred_location=explode(",",$data['preferred_location']);
                                foreach($all_preferred_location as $preferred_location)
                                {
                                    $exist=DB::table('preferred_location_masters')->where(['name'=>$preferred_location])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('preferred_location_masters')->insert(['name'=>$preferred_location]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'preferred_location','value'=>$preferred_location]);
                                }
                            }
                            if($data['level']!=''){
                                $all_level=explode(",",$data['level']);
                                foreach($all_level as $level)
                                {
                                    $exist=DB::table('level_masters')->where(['name'=>$level])->first();
                                    if(!$exist){
                                        $addToMasterTable=DB::table('level_masters')->insert(['name'=>$level]);                                    
                                    }

                                    CandidateOtherDetail::create(['candidate_id'=>$candidateDetail->id,'type'=>'level','value'=>$level]);
                                }
                            }

                                
                            $deleteData=ExcelUpload::find($data['id']);
                            $deleteData->delete();
                           
                            $importCount++;

                        });
                       
                        
                    }

                }
                $msg='No Data Available';
                if($dupCount > 0){
                    $msg .=$dupCount.'Duplicate Data';

                }
                if($importCount > 0){
                    $msg .=$importCount.' Data Imported Successfully';
                    
                }

                $deleteDuplicateData=ExcelUpload::where('business_id',$data['business_id'])->delete();

                return response()->json(['status' => true, 'msg' => $msg, 'data'=>1]);
                //return view('admin.candidate.bulkupload',compact('allData','no'));
            }
            else{
                // return abort(403,"You do not have permission for this");
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        
        }
        else{
            // return abort(403,"You do not have permission for this");
            return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
        }

    }
    
    public function UploadView_old()
    {
         
         # For Bulkdata Upload Page View
          
        $role=Auth::user()->account_type;
        if($role=='hr')
        {
            $status=$this->chkUserAccess(Auth::user()->id,3);
            if($status!=0){     
                $no=1;
                $allData=CandidateBulkData::where('business_id','=',Auth::user()->parent_id)->orderBy('id','DESC')->get();
            //    / dd($allData);
                // return view('admin.candidate.bulkupload',compact('allData','no'));
               
                return response()->json(['status' => true, 'msg' => 'Success', 'data'=>$allData]);
            }
            else{
                // return abort(403,"You do not have permission for this");
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        
        }
        else{
            // return abort(403,"You do not have permission for this");
            return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
        }
         
    }
    
    public function Upload_old(Request $request)
    {
        $this->authorize("access-manage-candidate");
        $role=Auth::user()->account_type;
        if($role=='hr')
        {
            $validator=Validator::make($request->all(),[
                'upload'=>'required|mimes:xlsx'
            ],
            [
                'upload.required'=>'File required',
                'upload.mimes'=>'File type must be in existing xlsx',

            ]);
            // if ($validator->fails()) {
            //     return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            //     }
             try{
                $up_file=Excel::toArray(new CandidateDetailsImport, $request->file('upload'));
                //dd(count($up_file));
                if(!isset($up_file['Candidate'])){
                    return response()->json(['status' => false, 'msg' => 'File is not supported', 'data'=>0]);
                    // return redirect('bulk_upload')->with('error','File not supported.');

                }
                else{
                    Excel::import(new CandidateDetailsImport, $request->file('upload'));
                }
                
             }
             catch(Exception $e)
             {
                // dd($e->getMessage());
                return response()->json(['status' => false, 'msg' => $e->getMessage(), 'data'=>0]);
             }

                
            
        // return redirect('bulk_upload')->with('success','Data upload Successfully.');
            Excel::import(new UsersImport, request()->file('upload'));
            return response()->json(['status' => true, 'msg' => 'Data uploaded Successfully.', 'data'=>1]);
        }
        else{
            return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
        }

        
            
                
         
    }
*/
    // public function getCity(Request $request)
    // {
    //    // dd($request->all());
    //     $all_city=City::where('state_id','=',$request->state_id)->where('status','=',1)->orderBy('name','ASC')->get();
    //     return response()->json([
    //         'status'=>true,           
    //         'data'=> $all_city,
    //         'msg'=>1
    //     ]);
    //     // return $all_city;
    // }

    public function getHr(Request $request)
    {
       // dd($request->all());
       $users=User::where([['account_type','=','hr'],['parent_id','=',$request->business_id]])->pluck('id')->toArray();
       $all_hr=DB::table('individual_user_access AS ua')->whereIn('ua.user_id',$users)->where([['ua.access_id','=',1],['ua.access_status','=',1]])
       ->join('users','users.id','=','ua.user_id')
       ->get();
       return response()->json([
        'status'=>true,           
        'data'=> $all_hr,
        'msg'=>1
        ]);
        // return $all_hr;
    }

    

    public function registrationStore(Request $request)
    {
        $this->authorize("access-manage-candidate");
        //dd($request->job_role);
        $role=Auth::user()->account_type;
        $flag=1;
        if($role=='hr')
        {
            $status=$this->chkUserAccess(Auth::user()->id,1);
            if($status!=0){   
                $flag=1;
               //return view('admin.candidate.registration',compact('states','job_role'));
            }
            else{
                
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        }
        if($flag==1){
        $validator = Validator::make($request->all(),[
            'cname' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'email' => 'required|email|check_mail',
            'gender' => 'required|alpha',
            'job_role' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'phone'=>'required|numeric|digits_between:6,15',
            'total_experience'=>'required',
            'dob'=>'required|date_format:Y-m-d|before:today',

            //'phone2'=>'required|numeric|digits:10',
            //'photo' => 'required|mimes:png,jpg,jpeg|max:2048',
           // 'signature' => 'required|mimes:png,jpg,jpeg|max:2048',
            'cv_scan' => 'nullable|mimes:pdf|max:2000'
        ],
        [
            'check_mail'=>'Invalid Email Id',
            'cname.required'=>'Name Required',
            'cname.min'=>'Name must be minimum 3 letters',
            'cname.regex'=>'Name should be alphabets only.',
            'phone.digits_between'=>'Phone Number should be of 6 to 15 digits',

            'total_experience.required'=>'Total Experience Required',
            'cv_scan.mimes'=>'File type must be in pdf',
            'cv_scan.max'=>'File is too large to upload'
            
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        
        //$role=Auth::user()->account_type;
       
         $num_of_row=CandidateDetail::count();

                   if(($role!='hr') && ($role!='business'))
                   {
                       $added_by=Auth::user()->id;
                       $hr_id=$request->hr_id;
                       $business_id=$request->business_id;
                  
                   }
                   else if($role=='business')
                   {
                    $added_by=Auth::user()->id;
                    $hr_id=$request->hr_id;
                    $business_id=Auth::user()->id;
                   }
                  else if($role=='hr')
                   {
                    $added_by=Auth::user()->id;
                    $hr_id=Auth::user()->id;
                    $business_id=Auth::user()->parent_id;
                   }
                   
                   $candidate_code='REC'.$hr_id.$business_id.($num_of_row+1);
                    
                $helper = new CommonHelper;
                $result1 = $helper->chkEmail(strtolower($request->email),$business_id);
                //dd($candidate_code);
                  //dd($result1); 
                if($result1==0){
                    if($request->cv_scan!=Null){
                        $cv_scan_link = $request->file('cv_scan')
                                        ->store('candidate');
                    }
                    else {
                        $cv_scan_link=NULL;
                    }
                        
                    $data=CandidateDetail::insert(['candidate_code'=>$candidate_code,'name'=>strtolower($request->cname),'email'=>strtolower($request->email),'country'=>$request->country,'phone'=>$request->phone,'gender'=>strtolower($request->gender),'state'=>$request->state,'city'=>$request->city,'cv_scan'=>$cv_scan_link,'added_by'=>$added_by,'business_id'=>$business_id,'hr_id'=>$hr_id,'job_role'=>$request->job_role,'total_experience'=>$request->total_experience,'dob'=>$request->dob,'assign_to'=>$hr_id]);
                    if($data)
                    {
                        return response()->json(['status' => true, 'msg' => 'Candidate Basic Details Saved Successfully.', 'data'=>1]);
                    // return redirect('candidate_list')->with('success','Candidate Basic Details Saved Successfully.');
                    }

                }
                else{
                    $data=CandidateDetail::where('id','=',$result1)->update(['candidate_code'=>$candidate_code,'name'=>$request->cname,'email'=>strtolower($request->email),'country'=>$request->country,'phone'=>$request->phone,'gender'=>strtolower($request->gender),'state'=>$request->state,'city'=>$request->city,'job_role'=>$request->job_role,'total_experience'=>$request->total_experience,'dob'=>$request->dob]);
                    if($data)
                    {
                        return response()->json(['status' => true, 'msg' => 'Candidate Basic Details Updated Successfully.', 'data'=>1]);
                    // return redirect('candidate_list')->with('success','Candidate Basic Details Updated Successfully.');
                    }

                }              
            }   
     
        
        
    }

    public function list(Request $request)
    {
        $this->authorize("access-manage-candidate");
        $states=State::where('status','=',1)->get();

        $query = CandidateDetail::where('user_id','=',0)->orderBy('id','DESC');
        if($request->cname) {		
			if($request->cname!=''){
				$query->where('name','LIKE','%'.$request->cname.'%');
			}
		}

        if($request->email) {		
			if($request->email!=''){
				$query->where('email',strtolower($request->email));
			}
		}

        if($request->phone) {		
			if($request->phone!=''){
				$query->where('phone',$request->phone);
			}
		}
        if($request->state) {		
			if($request->state!=''){
				$query->where('state',$request->state);
			}
		}
        if($request->city) {		
			if($request->city!=''){
				$query->where('city',$request->city);
			}
		}
        if($request->status) {		
			if($request->status!=''){
				$query->where('is_selected',$request->status);
			}
		}
        
        if((Auth::user()->account_type!='hr') && (Auth::user()->account_type!='business'))
        {
        $candidates=$query->get();
        }

        else if(Auth::user()->account_type=='hr')
        {
            $candidates=$query->where('assign_to','=',Auth::user()->id)->get();
          
        }    

        else if(Auth::user()->account_type=='business')
        {            
            $candidates=$query->where('business_id','=',Auth::user()->id)->get();       
        }
        $data=[
            'candidates'=>$candidates,
            'states'=>$states,
        ];
        return response()->json([
            'status'=>true,           
            'data'=> $data,
            'msg'=>1,
    
        ]);
        // return view('admin.candidate.list',compact('candidates','states'));
    }

    public function candidateView(Request $request)
    {
        $id=$request->id;
        $canUser=CandidateDetail::where('id',$id)->pluck('user_id')->first();
        if((Auth::user()->account_type=='candidate') && (Auth::user()->id!=$canUser))
        {
            // return abort(403,"You do not have permission for this");
            return response()->json([
                'status'=>false,           
                'data'=>0,
                'msg'=>'You do not have permission for this'
            ]);
        }
        else{
        // $this->authorize("access-manage-candidate");
        // $candidate=CandidateDetail::where('id','=',$id)->first();
        $candidate=DB::table('candidate_details as cd')
        ->Leftjoin('countries', 'cd.country', '=', 'countries.id')
        ->Leftjoin('state', 'cd.state', '=', 'state.state_id')
        ->Leftjoin('city', 'cd.city', '=', 'city.id')
        ->Leftjoin('job_roles', 'cd.job_role', '=', 'job_roles.id')
        ->Leftjoin('users as hr', 'cd.hr_id', '=', 'hr.id')
        ->Leftjoin('business_details as emp', 'cd.business_id', '=', 'emp.user_id')
        ->Leftjoin('users as assignhr', 'cd.assign_to', '=', 'assignhr.id')
        // ->select('cd.*', 'state.*', 'city.*','countries.*','job_roles.*','emp.*','hr.*')
        ->select('cd.*', 'state.state_title as stateName', 'city.name as cityName','countries.name as countryName','countries.nationality as nationality','countries.calling_code as callingCode','job_roles.name as jobRoleName','emp.business_name as employerName','hr.first_name as hrName','assignhr.first_name as assignToFirst','assignhr.last_name as assignToLast')
        ->where('cd.id','=',$id)
        ->first();
        // dd($candidateUserId);
       
        $education_details=CandidateEducationDetail::where('candidate_id','=',$id)->get();
        $profession_details=CandidateProfessionalDetail::where('candidate_id','=',$id)->get();
        // $skills=CandidateOtherDetail::where([['candidate_id','=',$id],['type','=','skill']])->get();
        // $languages=CandidateOtherDetail::where([['candidate_id','=',$id],['type','=','language']])->get();
        // $hobbies=CandidateOtherDetail::where([['candidate_id','=',$id],['type','=','hobby']])->get();
        $otherDetails=CandidateOtherDetail::where('candidate_id','=',$id)->get();
        $data=[
            'candidate'=>$candidate,
            'education_details'=>$education_details,
            'profession_details'=>$profession_details,
            // 'skills'=>$skills,
            // 'languages'=>$languages,
            // 'hobbies'=>$hobbies,
            'otherDetails'=>$otherDetails
            
        ];
        return response()->json([
            'status'=>true,           
            'data'=>$data,
            'msg'=>1
        ]);
        // return View::make('admin.candidate.candidateview',compact('candidate','education_details','profession_details','skills','languages','hobbies'));
    }
    }

     

    public function BasicDetailsUpdate(Request $request)
    { 
            $this->authorize("access-manage-candidate");
            $id=$request->id;
            $candidate=CandidateDetail::where('id','=',$id)->first();
            if((Auth::user()->account_type!='hr'))
            {
                $flag=1;
    
                if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
                {
                    $flag=0;
                }
                 
            }
            
            else if((Auth::user()->account_type=='hr') && (Auth::user()->parent_id==$candidate->business_id))
            {
                $status=$this->chkUserAccess(Auth::user()->id,2);
                if($status!=0){
                //action 
                $flag=1;
                
                }
                else{
                    return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
                }
            }
            
            if($flag==1)
            {
                $validator = Validator::make($request->all(), [
                    'cname' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
                    // 'email' => 'required|email|unique:candidate_details,email,'.$id,
                    'email' => 'required|email|check_mail',
                    'gender' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'job_role' => 'required',
                    'total_experience' => 'required',
                    'phone'=>'required|numeric|digits_between:6,15',
            
                    'phone2'=>'nullable|numeric|digits_between:6,15',
                    // 'photo' => 'nullable|mimes:jpg,jpeg,png',
                    // 'signature' => 'nullable|mimes:jpg,jpeg,png',
                    'photo' => 'nullable|base64img|is_png_jpg',
                    'signature' => 'nullable|base64img|is_png_jpg',
                    'cv_scan' => 'nullable|mimes:doc,pdf|max:2000',
                    'dob'=>'required|date_format:Y-m-d|before:today',

                    // 'passport_no' => 'nullable',
                    // 'dl_no' => 'nullable',                 
                    // 'passport_exp_date'=>'required_unless:passport_no,null',       
                    // 'dl_exp_date'=>'required_unless:dl_no,null'        
                    
                ],
                [
                    'cname.required'=>'Name Required',
                    'cname.regex'=>'Name should be alphabets only.',
                    'cname.min'=>'Name must be minimum 3 letter',

                    'dob.required'=>'DOB Required',
                    'dob.date_format'=>'DOB Date format must be DD-MM-YYYY',
                    'dob.before'=>'DOB must be before today',

                    'check_mail'=>'Invalid Email Id',        
                    
                    'phone2.numeric' => 'Alternate Phone must be in Numerics',
                    'phone2.digits_between' => 'Alternate Phone must be of 6 to 15 Digits',
                    'cv_scan.mimes' => 'Only (doc,pdf) type support ',
                    'cv_scan.max' => 'File size is too large to upload '
                    
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
                    }
                
                    //dd($request->all());
                    $photo_link=$request->photo_old;
                    $signature_link=$request->signature_old;
                    $cv_scan_link=$request->cv_scan_old;
                

                if (((!empty($request->photo)) && (!empty($request->photo_old))) || ((!empty($request->photo)) && (empty($request->photo_old))))
                {
                    if($request->photo_old!='')            
                    {
                        
                        $imagePath = public_path($request->photo_old);
                        if(File::exists($imagePath)){
                        unlink($imagePath);
                        }
                    }
                    $base64_photo=$request->photo;
                        
                    $photo = preg_replace('/^data:image\/\w+;base64,/', '', $base64_photo);
                    $type = explode(';', $base64_photo)[0];
                    $type = explode('/', $type)[1]; // png or jpg etc

                     $photoName = Str::random(10).'.'.$type;
                    
                    $photo_link='candidate/'.$photoName;
                    Storage::disk('local')->put('candidate/'.$photoName, base64_decode($photo));


                  
                    // $photo_link = $request->file('photo')
                    //         ->store('candidate');
                }
                if (((!empty($request->signature)) && (!empty($request->signature_old))) ||((!empty($request->signature)) && (empty($request->signature_old))))
                {
                    if($request->signature_old!='')            
                    {
                        $imagePath = public_path($request->signature_old);
                        if(File::exists($imagePath)){
                        unlink($imagePath);
                        }
                    }
                
                    //    $signature_link = $request->file('signature')
                    //            ->store('candidate');

                    $base64_signature=$request->signature;
                        
                    $signature = preg_replace('/^data:image\/\w+;base64,/', '', $base64_signature);
                    $type = explode(';', $base64_signature)[0];
                    $type = explode('/', $type)[1]; // png or jpg etc

                    $signatureName = Str::random(10).'.'.$type;
                    
                    $signature_link='candidate/'.$signatureName;
                     Storage::disk('local')->put('candidate/'.$signatureName, base64_decode($signature));
                }
                if (((!empty($request->cv_scan)) && (!empty($request->cv_scan_old))) || ((!empty($request->cv_scan)) && (empty($request->cv_scan_old))))
                {
                    if($request->cv_scan_old!='')            
                    {
                        $imagePath = public_path($request->cv_scan_old);
                        if(File::exists($imagePath)){
                        unlink($imagePath);
                        }
                    }

                    $cv_scan_link = $request->file('cv_scan')
                        ->store('candidate');
                }

                $data=CandidateDetail::where('id','=',$id)->update(['name'=>strtolower($request->cname),'email'=>strtolower($request->email),'phone'=>$request->phone,'phone2'=>$request->phone2,'gender'=>strtolower($request->gender),'country'=>$request->country,'state'=>$request->state,'city'=>$request->city,'dob'=>$request->dob,'job_role'=>$request->job_role,'total_experience'=>$request->total_experience,'religion'=>$request->religion,'fathers_name'=>$request->fname,'mothers_name'=>$request->mname,'spouse_name'=>$request->sname,'present_address'=>$request->present_address,'permanent_address'=>$request->permanent_address,'photo'=>$photo_link,'signature'=>$signature_link,'cv_scan'=>$cv_scan_link,'updated_at'=>date('Y-m-d H:i:s')]);

                $raw_data=$request->all();
                $raw_data['photo']=$photo_link;
                $raw_data['signature']=$signature_link;
                $edited_data=json_encode($raw_data);

                $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);
            
                if($data)
                {
                // return redirect('candidate_list')->with('success','Candidate Basic Details Updated Successfully.');
                return response()->json(['status' => true, 'msg' => 'Candidate Basic Details Updated Successfully.', 'data'=>1]);
                }

                else{
                    return response()->json(['status' => false, 'msg' => 'Details not Updated', 'data'=>0]);
                }
            }
            else{
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
    }

    

    public function EducationDetailsUpdate(Request $request)
    {
      // dd($request->all());
        $this->authorize("access-manage-candidate");       
            
        $id=$request->id;
        $candidate=CandidateDetail::where('id','=',$id)->first();
        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }            
               
        }
        
        else if((Auth::user()->account_type=='hr') && (Auth::user()->parent_id==$candidate->business_id))
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
            //action 
            $flag=1;
            
            }
            else{
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        }

           // dd($value);
           if($flag==1)
           {
           $validator = Validator::make($request->all(),  [
            'institute' => 'required|regex:/^[a-zA-Z. ]+$/u|min:3',
            'degree' => 'required|regex:/^[a-zA-Z. ]+$/u|min:3',
            'year_of_passing'=>'required|numeric|digits:4|gt:1900',
            'marks'=>'required|string',
            'percentage'=>'required|numeric|between:0,99.99|gt:0',            

                // 'institute' => 'required|regex:/^[a-zA-Z. ]+$/u|min:3',
                // 'education_type' => 'required',
                // 'degree' => 'required',
                // 'year_of_passing'=>'nullable|numeric|digits:4|gt:1900',
                // 'marks'=>'nullable|string',
                // 'percentage'=>'nullable|numeric|between:0,99.99|gt:0',
                // 'doc_file'=>'nullable|mimes:jpg,jpeg,png|max:1048'
                
            ],
            [
                'institute.required'=>'Institute Name Required',
                'institute.min'=>'Institute Name should be minimum 3 letters',
                'institute.regex'=>'Institute Name should be in alphabets only.',

                'degree.required'=>'Degree Name Required',
                'degree.min'=>'Degree Name should be minimum 3 letters',
                'degree.regex'=>'Degree Name should be in alphabets only.',

                'year_of_passing.required'=>'Passing Year Required',
                'year_of_passing.digits'=>'Passing Year must be in 4 digits',
                'year_of_passing.numeric'=>'Passing Year must be in digits',
                'year_of_passing.gt'=>'Passing Year must be after 1950',

                'percentage.required'=>'Percentage Required',
                'percentage.between'=>'Percentage must be in 0 to 99.99',
                'percentage.numeric'=>'Percentage must be in digits',
                'percentage.gt'=>'Percentage must be greater then 0',

                'marks.required'=>'Marks Required',
                 
                'marks.string'=>'Marks must be String',
                 
                //'doc_file.required'=>'Marksheet Required',
                // 'doc_file.max'=>'File is too large to upload',
                // 'doc_file.mimes'=>'File must be type of jpg/jpeg',
            ]);
            
             //dd(1);
             if ($validator->fails()) {
                return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
                }
                
               
                $data=CandidateEducationDetail::insert(['candidate_id'=>$id,'institute_name'=>$request->institute,'degree'=>$request->degree,'year_of_passing'=>$request->year_of_passing,'marks'=>$request->marks,'percentage'=>$request->percentage]);
                
         
                if($data)
                {
                    $edited_data=json_encode($request->all());

                    $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);

                    // return redirect()->back()->with('success','Education Details Added Successfully');
                    return response()->json(['status' => true, 'msg' => 'Education Details Added Successfully', 'data'=>1]);
                }
            }
    }

    public function deleteEducation($id)
    {
        $this->authorize("access-manage-candidate");
        $education=CandidateEducationDetail::where('id','=',$id)->first();
               $education->delete();
        // unlink(storage_path('app/'.$education->marksheet_doc));       
        
        return response()->json(['status' => true, 'msg' => 'Education Degree Deleted', 'data'=>1]);
    }

     

    public function ProfessionalDetailsUpdate(Request $request)
    {
        //dd($request->addMoreInputFields);
        $this->authorize("access-manage-candidate");
        $id=$request->id;
        $candidate=CandidateDetail::where('id','=',$id)->first();   
        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }        
             
           
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->parent_id==$candidate->business_id)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
                $flag=1;
            }
            else{
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        }
        if($flag==1)
        {
        $validator = Validator::make($request->all(), [
                'company' => 'required|regex:/^[a-zA-Z0-9. ]+$/u|min:3',
                'job_role' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
                'from_date'=>'required|date_format:Y-m-d|before:today',
                'to_date'=>'nullable|date_format:Y-m-d|after:from_date'         
                
                
                // 'company' => 'required|regex:/^[a-zA-Z0-9. ]+$/u|min:3',
                // 'job_role' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
                // 'from_date'=>'required|date_format:Y-m-d|before:today',
                // 'to_date'=>'nullable|date_format:Y-m-d|after:from_date',            
                // 'cur_salary'=>'required_if:cc,"yes"|between:1,9999999.9999|numeric',            
                // 'cur_location'=>'required_if:cc,"yes"|string'            
              
            ],
            [
                'company.required'=>'Company Name Required',
                'company.min'=>'Company Name must be minimum 3 letters',
                'company.regex'=>'Company Name must be in Alpha Numerics only.',

                'job_role.required'=>'Job Role Required',
                'job_role.min'=>'Job Role must be minimum 3 letters',
                'job_role.regex'=>'Job Role must be in alphabets only.',

                'from_date.date_format'=>'From Date format must be DD-MM-YYYY',
                'from_date.required'=>'From Date Required',
                'from_date.before'=>'From Date must be before today',

                'to_date.date_format'=>'To Date format must be DD-MM-YYYY',
                 
                'to_date.before'=>'To Date must be after From Date'
                
            ]);
            
             if ($validator->fails()) {
                return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
                }
                if(($request->cc=='no' && $request->to_date!=NULL) || ($request->cc=='yes' && $request->to_date==NULL)) 
                {
                    $data=CandidateProfessionalDetail::insert(['candidate_id'=>$id,'company_name'=>$request->company,'job_role'=>$request->job_role,'from_date'=>$request->from_date,'to_date'=>$request->to_date,'description'=>$request->description,'current_company'=>$request->cc]);
            
            
                    if($data)
                    {
                        $edited_data=json_encode($request->all());

                        $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);

                        return response()->json(['status' => true, 'msg' => 'Professional Details Added Successfully', 'data'=>1]);
                        // return redirect()->back()->with('success','Professional Details Added Successfully');
                    }
                }
                else{
                    return response()->json(['status' => false, 'msg' => 'To Date Required (if not Current Company)', 'data'=>0]);
                   
                }
            }
    }

    public function deleteProfession($id)
    {        
        $this->authorize("access-manage-candidate");
        $profession=CandidateProfessionalDetail::where('id','=',$id)->first();
        $profession->delete();
         
        return response()->json(['status' => true, 'msg' => 'Professional details deleted', 'data'=>1]);
    }

     

    public function OthersDetailsUpdate(Request $request)
    {
        $this->authorize("access-manage-candidate");
        $id=$request->id;
        $candidate=CandidateDetail::where('id','=',$id)->first();
        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->parent_id==$candidate->business_id)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
                $flag=1;
            }
            else{
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        }
        if($flag==1)
        {
           // dd($request->all());
        foreach($request->all() as $key => $value){
            if($key=='skill' || $key=='language' || $key=='hobby'){
                foreach($value as $data){
                    if($data['value']!=null){
                        $data=CandidateOtherDetail::insert(['candidate_id'=>$id,'type'=>$key,'value'=>$data['value'],'description'=>$data['description']]);
                        
                    }
                }
            }
        }
        $edited_data=json_encode($request->all());

        $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);
        // return redirect()->back()->with('success','Other Details Added Successfully');
        return response()->json(['status' => true, 'msg' => 'Other Details Added Successfully', 'data'=>1]);
    }
    }
   
    public function deleteOthers($id)
    {
        
        $this->authorize("access-manage-candidate");
        $others=CandidateOtherDetail::where('id','=',$id)->first();
               $others->delete();
        
               return response()->json(['status' => true, 'msg' => 'Other details deleted', 'data'=>1]);
         
    }

    public function isSelected(Request $request)
    {
        $this->authorize("access-manage-candidate");
        $candidate_id=$request->candidate_id;
        $candidate=CandidateDetail::where('id','=',$candidate_id)->first();
        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }
           
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->parent_id==$candidate->business_id)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
                $flag=1;
            }
            else{
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        }
        if($flag==1)
        {
            $old_status=CandidateDetail::where('id','=',$candidate_id)->first();
            //dd($old_status);
            if($old_status->is_selected==1){
                $update_candidate=CandidateDetail::where('id','=',$candidate_id)->update(['is_selected'=>2,'status'=>0]); //for deselect
            }
            else{
                $update_candidate=CandidateDetail::where('id','=',$candidate_id)->update(['is_selected'=>1,'status'=>1]); //for select
            }
            
            if($update_candidate)
            {
                // return $msg=1;
                return response()->json(['status' => true, 'msg' => 'ok', 'data'=>1]);
            }
            else
            {
                // return $msg=2;
                return response()->json(['status' => false, 'msg' => 'Something was wrong!', 'data'=>0]);
            }
        }
        else
            {
                // return $msg=2;
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
       
    }
     

   

    public function reviewDetailsView(Request $request)
    { 
        $this->authorize("access-manage-candidate");
        $id=$request->id;
        $candidate=CandidateDetail::where('id','=',$id)->first();
       // dd($candidate);
       
       if($candidate->hr_id==Auth::user()->id){
        return View::make('admin.candidate.reviewdetails',compact('candidate'));
       }
       else{
        return abort(403,"You do not have permission for this");
       }
    }

    public function reviewDetailsUpdate(Request $request)
    {
        $this->authorize("access-manage-candidate");
        $id=$request->id;
        $candidate_id = $id;   
        $rating = $request->rating;    
        $behaviour = $request->behaviour;   
        $timely_response = $request->timely_response; 
        $communication_skill = $request->communication_skill; 
        $review = $request->review; 

            $data=CandidateDetail::where('id','=',$candidate_id)->update(['rating'=>$rating,'behaviour'=>$behaviour,'timely_response'=>$timely_response,'communication_skill'=>$communication_skill,'review'=>$review]);
        if($data)
        {
            $edited_data=json_encode($request->all());

            $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);
            return redirect()->back()->with('success','Details Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error','Something Was Wrong');
        }
        
    }

   

    

    public function DisputeView(Request $request)
    {
        $id=$request->id;
        $candidate_id=$id;
        $user_id=Auth::user()->id;
        return view('admin.candidate.dispute',compact('candidate_id','user_id'));
    }

    public function DisputeStore(Request $request)
    { 
                $validator = Validator::make($request->all(), [
                'comment' => 'required|string',             
            
            ]);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator);
            }

        $candidate_id=$request->candidate_id;
        $user_id=$request->user_id;
        $comment=$request->comment;
        $data=DB::table('disputes')->insert(['candidate_id'=>$candidate_id,'user_id'=>$user_id,'comment'=>$comment,'posted_by'=>'user']);
        return redirect()->back()->with('success','Dispute Submitted Successfully!');
    }

    public function candidateFollowUpList(Request $request)
    {
    
        $id=$request->id;
          

        $all_fup=CandidateFollowUp::where('candidate_id','=',$id)->orderby('id','DESC')->get();
        $candidate=CandidateDetail::where('id','=',$id)->first();
        $maxlead=CandidateFollowUp::where('candidate_id','=',$id)->max('id');
        //dd($maxlead);
        $maxstatus=CandidateFollowUp::where('id','=',$maxlead)->pluck('status')->first();
         $data=[
            'all_fup'=>$all_fup,
            'id'=>$id,
            'candidate'=>$candidate,
            'maxstatus'=>$maxstatus,
         ];
         return response()->json([
            'status'=>true,           
            'data'=>$data,
            'msg'=>1
        ]);
       // return view('admin.candidate.candidateFollowUp',compact('all_fup','id','candidate'));
    }
    
    public function candidateFollowUpStore(Request $request)
    {
        $id=$request->id;

        $validator=Validator::make($request->all(), [
            'remarks'=>'required|string',
            'next_date'=>'required|date_format:Y-m-d|after_or_equal:today',
            'next_time'=>'required'
        ],
        [
            'next_date.date_format'=>'Date fromat must be DD-MM-YYYY',
            'next_date.after'=>'Next Date must be after today',
            'next_date.required'=>'Next Date Required',
            'next_date.after_or_equal'=>'Next Date not before today',
            'next_time.required'=>'Time Required',
            'next_time.after'=>'Time after Now.'
           
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }

        if(($request->maxstatus==NULL) || ($request->maxstatus==2))
        {
            $data=CandidateFollowUp::insert(['candidate_id'=>$id,'hr_id'=>Auth::user()->id,'date'=>date('Y-m-d H:i:s'),'remarks'=>$request->remarks,'next_contact_date'=>$request->next_date,'next_time'=>$request->next_time,'status'=>1]);
            if($data)
            {
                // return redirect()->back()->with('success','Remarks Submitted');
                return response()->json(['status' => true, 'msg' => 'Remarks Submitted', 'data'=>1]);
            }
            else
            {
                // return redirect()->back()->with('error','Something was wrong.');
                return response()->json(['status' => false, 'msg' => 'Something was wrong.', 'data'=>0]);
            }

        }
        else
            {
                return redirect()->back()->with('error','Last Followup not verified!');
            }
    }

    public function uploadDocumentView(Request $request)
    {
        /**
         * Upload Document Page View
         * input:base64_encode(id)
         * output:candidate_id,documents,allTypes.
         */
        $this->authorize("access-manage-candidate");
        $id=$request->id;
        $candidate=CandidateDetail::where('id','=',$id)->first();
        $allTypes=DocumentType::where('status','=',1)->orderBy('name','DESC')->get();



        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }
            if($flag==1)
            {
              // action
            // $documents=CandidateDocument::where('candidate_id','=',$id)->get();
            $documents=DB::table('candidate_documents as doc')
            ->join('candidate_details as cd', 'doc.candidate_id', '=', 'cd.id')
            ->join('document_types as dt', 'doc.doc_type', '=', 'dt.id')    
            ->select('doc.*', 'cd.name as candidateName', 'dt.name as docTypeName')
            ->where('doc.candidate_id','=',$id)
            ->where('doc.deleted_at','=',NULL)
            ->get();

            // $candidate_id=$id;        
            $data=[
                 
                'documents'=>$documents
                 
            ];
            // return view('admin.candidate.document_upload',compact('candidate_id','documents','allTypes'));
            return response()->json([
                'status'=>true,           
                'data'=>$data,
                'msg'=>1
            ]);
            }
            else{
                // return abort(403,"You do not have permission for this");
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);

            }
           
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->parent_id==$candidate->business_id)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
            //action 
            $documents=DB::table('candidate_documents as doc')
            ->join('candidate_details as cd', 'doc.candidate_id', '=', 'cd.id')
            ->join('document_types as dt', 'doc.doc_type', '=', 'dt.id')    
            ->select('doc.*', 'cd.name as candidateName', 'dt.name as docTypeName')
            ->where('doc.candidate_id','=',$id)
            ->where('doc.deleted_at','=',NULL)
            ->get();   
            $data=[
                
                'documents'=>$documents
                 
            ];
            // return view('admin.candidate.document_upload',compact('candidate_id','documents','allTypes'));
            return response()->json([
                'status'=>true,           
                'data'=>$data,
                'msg'=>1
            ]);
            // return view('admin.candidate.document_upload',compact('candidate_id','documents','allTypes'));
            }
            else{
                // return abort(403,"You do not have permission for this");
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        }
 
    }
    
    public function uploadDocumentStore(Request $request)
    {
        $this->authorize("access-manage-candidate");
        $id=$request->id;
        $candidate=CandidateDetail::where('id','=',$id)->first();
        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }
           
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->parent_id==$candidate->business_id)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
            //action 
            $flag=1;
            }
            else{
                return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            }
        }
        if($flag==1)
        {
            $validator = Validator::make($request->all(), [
                'doc_type'=>'required',
                'doc_name'=>'required|string|max:20',
                'doc_file'=>'required|max:2000|mimes:jpg,jpeg,png,pdf',
            ],
            [
                'doc_type.required'=>'Documnet Type Required',

                'doc_name.required'=>'Documnet Name Required',
                'doc_name.max'=>'Documnet Name limits upto 20 letters',

                'doc_file.required'=>'File required',
                'doc_file.max'=>'File is too large to upload',
                'doc_file.mimes'=>'File type must be in jpg/jpeg/png/pdf',
            ]);
            if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
            
        $doc_file_link = $request->file('doc_file')
                    ->store('candidate');
        $data=CandidateDocument::insert(['candidate_id'=>$request->candidate_id,'doc_type'=>$request->doc_type,'doc_name'=>$request->doc_name,'doc_file'=>$doc_file_link,'uploaded_by'=>Auth::user()->id]);
        if($data)
        {
            // return redirect()->back()->with('success','Document Uploaded Successfully');
            return response()->json(['status' => true, 'msg' => 'Document Uploaded Successfully', 'data'=>1]);
        }
        }
            
    }

    public function deleteDocument($id)
    {
        $this->authorize("access-manage-candidate");
        $doc=CandidateDocument::where('id','=',$id)->first();
                $doc->delete();
        //unlink(storage_path('app/'.$education->marksheet_doc));
        return response()->json(['status' => true, 'msg' => 'Document Deleted', 'data'=>1]);
       
    }

    public function ratingReviewStore(Request $request)
    {
        $this->authorize("access-manage-candidate");
        $data=CandidateDetail::where('id','=',$request->candidate_id)->update(['rating'=>$request->rating,'review'=>$request->review]);
                 
        //unlink(storage_path('app/'.$education->marksheet_doc));
        return response()->json(['status' => true, 'msg' => 'Rating And Review Submitted Successfully', 'data'=>1]);
       
    }

    public function joiningDetailsView(Request $request)
    { 
        $this->authorize("access-manage-candidate");
        $id=$request->id;
       // $candidate=CandidateDetail::where('id','=',$id)->first();
        //  dd($id);
        
        $offer_letter=offerLetter::where([['id','=',$id],['is_accepted','=',3]])->orderBy('id','DESC')->first();
        // dd($offer_letter);
        $reschedule=DB::table('reschedule')->where('offer_letter_id','=',$id)->orderBy('id','DESC')->first();
        // dd($reschedule);
       
        // return View::make('admin.candidate.joiningdetails',compact('candidate','offer_letter','reschedule'));
        $data=[
           'offer_letter'=> $offer_letter,
            'reschedule'=>$reschedule
        ];
        return response()->json(['status' => true, 'msg' => 1, 'data'=>$data]);
         
    }

    public function joiningDetailsUpdate(Request $request)
    {

        $this->authorize("access-manage-candidate");
       
        $offer_id = $request->id;   
            
            // $data=CandidateDetail::where('id','=',$candidate_id)->first();

            $offer_letter=offerLetter::where([['id','=',$offer_id],['is_accepted','=',3]])->orderBy('id','DESC')->first();
            $reschedules=DB::table('reschedule')->where('offer_letter_id','=',$offer_id)->orderBy('id','DESC')->first();

            
            $offer_letter_update=offerLetter::where('id','=',$offer_id)->update(['is_accepted'=>1,'is_rescheduled'=>1,'joining_date'=>$reschedules->new_joining_date,'time_of_joining'=>$reschedules->new_joining_time,'joining_confirmed'=>1]);
            $update_candidate=CandidateDetail::where('id','=',$offer_letter->candidate_id)->update(['joining_confirmed'=>1,'status'=>31]);
           // $reschedule_update=DB::table('reschedule')->where([['candidate_id','=',$candidate_id],['offer_letter_id','=',$offer_letter->id]])->update();
        
        if($offer_letter_update)
        {
            $edited_data=json_encode($request->all());

            $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$offer_letter->candidate_id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);
            // return redirect('offer_letter_list')->with('success','Reschedule Successfully');
            return response()->json(['status' => true, 'msg' => 'Rescheduled Successfully', 'data'=>1]);
        }
        else
        {
            // return redirect()->back()->with('error','Something Was Wrong');
            return response()->json(['status' => false, 'msg' => 'Something Was Wrong', 'data'=>0]);
        }
        
    }


    public function reallotCandidate(Request $request)
    {
        $this->authorize("access-manage-candidate");

        $this->validate($request, [
            'candidate_id'=>'required|numeric',
            'hr_id'=>'required|numeric'            
        ],
        [
            'candidate_id.required'=>'Candidate not found',
            'hr_id.required'=>'Please Select HR'
        ]);
            // dd($request->all());
            $cd=CandidateDetail::where('id','=',$request->candidate_id)->first();
            $data=CandidateDetail::where('id','=',$request->candidate_id)->update(['assign_to'=>$request->hr_id]);
            $createData=DB::table('reallotment_candidate')->insert(['candidate_id'=>$request->candidate_id,'old_hr_id'=>$cd->assign_to,'new_hr_id'=>$request->hr_id,'assign_by'=>Auth::user()->id]);
        if($createData)
       {
        // return redirecct('candidate_list')->with('success','Candidate Re-Allot Successfully!');
        // return $msg=1;
        return response()->json(['status' => true, 'msg' => 'Candidate Re-Allot Successfully!', 'data'=>1]);
       }
       else
       {
        // return redirecct('candidate_list')->with('error','Something was wrong!');
        // return $msg=0;
        return response()->json(['status' => false, 'msg' => 'Something was wrong!', 'data'=>0]);
       }
    }

    
}
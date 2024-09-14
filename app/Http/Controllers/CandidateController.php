<?php

namespace App\Http\Controllers;

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
use App\Models\Profile;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\OfferLetter;
use App\Models\CandidateFollowUp;
use App\Models\IndividualUserAccess;
use App\Models\JobRole;
use App\Models\Industry;
use App\Models\CandidateBulkData;
use App\Models\CandidateDocument;
use App\Models\DocumentType;
use App\Models\EducationMaster;
use App\Models\CourseMaster;
use App\Models\MatrixAttribute;
use App\Libs\CommonHelper;
use DB;
use Auth;
use Session;
use PDF;
use Form;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferLetterGenerated;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CandidateDetailsImport;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use App\Models\ExcelUpload;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{

    public function exportCSV()
    {
        /**
         * for Export CSV file of Candidate List
         * Input:Auth user id
         * Output:Candidate List
         */
        $fileName = 'candidate.csv';
        //$candidates = CandidateDetail::where('user_id','=',0)->get();
        $query = CandidateDetail::where('user_id','=',0)->orderBy('id','DESC');
        if((Auth::user()->account_type!='hr') && (Auth::user()->account_type!='business'))
        {
        $candidates=$query->get();
        }

        else if(Auth::user()->account_type=='hr')
        {
            $candidates=$query->where('business_id','=',Auth::user()->parent_id)->get();
        }    

        else if(Auth::user()->account_type=='business')
        {            
            $candidates=$query->where('business_id','=',Auth::user()->id)->get();       
        }
        //dd($candidates);
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Name', 'Email', 'Phone', 'Gender','State','City','Job Role','Total Experience');

        $callback = function() use($candidates, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($candidates as $task) {
                $row['Name']  = $task->name;
                $row['Email']    = $task->email;
                $row['Phone']    = $task->phone;
                $row['Gender']  = $task->gender;
                $row['State']  = $task->stateDetails->state_title;
                $row['City']  = $task->cityDetails->name;
                $row['Job Role']  = $task->jobRole->name;
                $row['Total Experience']  = $task->total_experience;
                

                fputcsv($file, array($row['Name'], $row['Email'], $row['Phone'], $row['Gender'],$row['State'],$row['City'],$row['Job Role'],$row['Total Experience']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



    public function chkUserAccess($hr_id,$access_id)
    {                     
        /**
         * for Check User permission 
         * input:hr_id, access_id
         * output:status
         * 
         */
        $user_access=IndividualUserAccess::where([['user_id','=',$hr_id],['access_id','=',$access_id],['access_status','=',1]])->first();
        //dd($user_access);
        if($user_access==Null)
        {
            return $status=0;
        }
        else{
            return $status=$user_access->user_id;
        }
        
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
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
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
                $code='REC';
            }
            else{
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        }
        else{
            $status=0;
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
        if($status!=0)
        {    
            try{
                
             
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

                            // dd($data);
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
               
                
                return redirect('bulk_upload')->with('success',$msg);
                //return view('admin.candidate.bulkupload',compact('allData','no'));

            }catch (Exception $e) {
                return redirect('bulk_upload')->with('error',$e->getMessage());
                // Log::Info('Featured Matches Error '.$e->getMessage());
               
            }
        }else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
            
        
        

    }
    public function UploadView()
    {
        /**
         * For Bulkdata Upload Page View
         */
        $role=Auth::user()->account_type;
        if($role=='hr')
        {
            $status=$this->chkUserAccess(Auth::user()->id,3);
            if($status!=0){     
                $no=1;
               $allData=ExcelUpload::where('business_id','=',Auth::user()->parent_id)->orderBy('id','DESC')->paginate(10);
               
                return view('admin.candidate.bulkupload',compact('allData','no'));
            }
            else{
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        
        }
       elseif($role=='superadmin')
        {
             
            
                $no=1;
               $allData=ExcelUpload::where('uploaded_by',auth()->user()->id)->orderBy('id','DESC')->paginate(10);
               
                return view('admin.candidate.bulkupload',compact('allData','no'));
            
        
        }
        else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
         
    }

    public function Upload_old(Request $request)
    {
        /**
         * for store bulkdata
         * input:Excel File
         * output:Redirect with success/error
         */
        $role=Auth::user()->account_type;
        if($role=='hr')
        {
        $this->validate($request,[
            'upload'=>'required|mimes:xlsx'
        ],
        [
            'upload.required'=>'File required',
            'upload.mimes'=>'File type must be xlsx',

        ]);

             try{
                $up_file=Excel::toArray(new CandidateDetailsImport, $request->file('upload'));
                //dd(count($up_file));
                if(!isset($up_file['Candidate'])){
                    return redirect('bulk_upload')->with('error','File not supported.');

                }
                else{
                    Excel::import(new CandidateDetailsImport, $request->file('upload'));
                }
                
             }
             catch(Exception $e)
             {
                $e->getMessage();
             }

                
            
        return redirect('bulk_upload')->with('success','Data uploaded Successfully.');
        }
        else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }

        // $array = file($request->upload);
        // dd($array);
        
        // if($request->upload)
        // {
        //  $data= array_map('str_getcsv',file($request->upload));
            
        //      dd($data);
        //     // for($i=2;$i<10;$i++)
        //     // {
        //     //     for($j=0;$j<8;$j++)
        //     //     {
                    
        //     //          echo $rt=$data[$i][$j]; 
        //     //     }
                
        //     // }
  
        //     $role=Auth::user()->account_type;
        //     $user=User::where('id','=',Auth::user()->id)->first();
            
        //     $hr_id=$user->id;
        //     $business_id=$user->parent_id;
            
        //     foreach($data as $key=>$value)
        //     {
        //         try{
        //             $candidate=CandidateDetail::where([['email','=',$value[1]],['phone','=',$value[2]]])->pluck('id')->first();
                     
        //            if($candidate==Null)
        //            {                    
        //                 $num_of_row=CandidateDetail::count();
        //                 $candidate_code='REC'.$hr_id.$business_id.($num_of_row+1);                       

        //                 $create=CandidateDetail::insert(['candidate_code'=>$candidate_code,'name'=>$value[0],'email'=>$value[1],'phone'=>$value[2],'phone2'=>$value[3],'gender'=>$value[4],'country'=>$value[5],'state'=>$value[6],'city'=>$value[7],'religion'=>$value[8],'fathers_name'=>$value[9],'mothers_name'=>$value[10],'spouse_name'=>$value[11],'present_address'=>$value[12],'permanent_address'=>$value[13]/*,'photo'=>$photo_link,'signature'=>$signature_link,'cv_scan'=>$cv_scan_link*/,'added_by'=>$hr_id,'business_id'=>$business_id,'hr_id'=>$hr_id,'job_role'=>$value[14],'total_experience'=>$value[15]]);
        //            }
        //            else
        //            {
        //                 $create=CandidateDetail::where('id','=',$candidate)->update(['name'=>$value[0],'email'=>$value[1],'phone'=>$value[2],'phone2'=>$value[3],'gender'=>$value[4],'country'=>$value[5],'state'=>$value[6],'city'=>$value[7],'religion'=>$value[8],'fathers_name'=>$value[9],'mothers_name'=>$value[10],'spouse_name'=>$value[11],'present_address'=>$value[12],'permanent_address'=>$value[13]/*,'photo'=>$photo_link,'signature'=>$signature_link,'cv_scan'=>$cv_scan_link*/,'added_by'=>$hr_id,'business_id'=>$business_id,'hr_id'=>$hr_id,'job_role'=>$value[14],'total_experience'=>$value[15]]);
        //            }                  
                   
        //         //$create=DB::table('test')->insert(['f1'=>$value[0],'f2'=>$value[1],'f3'=>$value[2],'f4'=>$value[3],'f5'=>$value[4],'f6'=>$value[5],'f7'=>$value[6],'f8'=>$value[7],'f9'=>$value[8],'f10'=>$value[9]]);
        //         } catch (\Exception $e) {
        //             return $e->getMessage();
        //             continue;
        //         }
        //     }
            
            // return redirect('candidate_list')->with('success','data upload Successfully.');
            
                
         
    }

    public function Upload(Request $request)
    {
       // return redirect('bulk_upload')->with('error','Please Try after some time.');
        $this->validate($request,[
            'upload'=>'required|mimes:xlsx'
        ],
        [
            'upload.required'=>'File required',
            'upload.mimes'=>'File type must be xlsx',

        ]);
        try{
            Excel::import(new UsersImport, request()->file('upload'));
            return redirect('bulk_upload')->with('success','Data uploaded Successfully.');
        
        } catch (\Exception $e) {
            // return $e->getMessage();
            return redirect('bulk_upload')->with('error','Something was wrong. Please check data sheet!');
        }
    }
    public function getCity(Request $request)
    {
        /**
         * for get city list by state id
         * inpt:state_id
         * Output:all_city
         */
       // dd($request->all());
        $all_city=City::where('state_id','=',$request->state_id)->where('status','=',1)->orderBy('name','ASC')->get();
        return $all_city;
    }

    public function getHr(Request $request)
    {
        /**
         * for get hr list by business id
         * inpt:business_id
         * Output:all_hr
         */
       // dd($request->all());
       $users=User::where([['account_type','=','hr'],['parent_id','=',$request->business_id]])->pluck('id')->toArray();
       $all_hr=DB::table('individual_user_access AS ua')->whereIn('ua.user_id',$users)->where([['ua.access_id','=',1],['ua.access_status','=',1]])
       ->join('users','users.id','=','ua.user_id')
       ->get();
        return $all_hr;
    }
    public function getTemplate(Request $request)
    {
        /**
         * for get hr list by business id
         * inpt:business_id
         * Output:all_hr
         */
       // dd($request->all());
       $allTemp=DB::table('offer_letter_templates')->where('business_id',$request->business_id)->get();
     
        return $allTemp;
    }

    public function registration()
    {
        /**
         * for Add Candidate Page View.
         * input:Null
         * Output: all_business,all_hr,states,job_role.
         */

        $this->authorize("access-manage-candidate");
        $role=Auth::user()->account_type;
        $user_id=Auth::user()->id;
        $states=State::where('status','=','Active')->get();
        $job_role=JobRole::where('status','=',1)->orderBy('name','ASC')->get();
        $country=Country::get();
        if(($role!='hr') && ($role!='business'))
        {
            $all_business=User::where('account_type','=','business')->get();
           
            return view('admin.candidate.registration',compact('all_business','states','job_role','country'));
        }
        else if($role=='business')
        {
            
            $users=User::where([['account_type','=','hr'],['parent_id','=',$user_id]])->pluck('id')->toArray();
       $all_hr=IndividualUserAccess::whereIn('user_id',$users)->where([['access_id','=',1],['access_status','=',1]])->get();


            //$all_hr=User::where([['account_type','=','hr'],['parent_id','=',$user_id]])->get();
            return view('admin.candidate.registration',compact('all_hr','states','job_role','country'));
        }
        else if($role=='hr')
        {
            $status=$this->chkUserAccess(Auth::user()->id,1);
            if($status!=0){     
                return view('admin.candidate.registration',compact('states','job_role','country'));
            }
            else{
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        }
               
    }

    public function registrationStore(Request $request)
    {
        /**
         * for Store Candidate Details.
         * input:cname,email,gender,job_role,state,city,phone,total_experience,dob,cv_scan,business_id,hr_id.
         * Output: Redirect With success/error.
         */

        $this->authorize("access-manage-candidate");
        //dd($request->job_role);
        $this->validate($request,[
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
            'cv_scan.max'=>'File is too large to upload',
            
        ]);
        
         
        $role=Auth::user()->account_type;
       
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
                $result1 = $helper->chkEmail($request->email,$business_id);
                  //dd($result1); 
                if($result1==0){
                    if($request->cv_scan!=Null){
                        $cv_scan_link = $request->file('cv_scan')
                                        ->store('candidate');
                    }
                    else {
                        $cv_scan_link=NULL;
                    }
                        
                    $data=CandidateDetail::insert(['candidate_code'=>$candidate_code,'name'=>strtolower($request->cname),'email'=>strtolower($request->email),'country'=>$request->country,'phone'=>$request->phone,'gender'=>$request->gender,'state'=>$request->state,'city'=>$request->city,'cv_scan'=>$cv_scan_link,'added_by'=>$added_by,'business_id'=>$business_id,'hr_id'=>$hr_id,'job_role'=>$request->job_role,'total_experience'=>$request->total_experience,'dob'=>$request->dob,'assign_to'=>$hr_id]);
                    if($data)
                    {
                    return redirect('candidate_list')->with('success','Candidate Basic Details Saved Successfully.');
                    }

                }
                else{
                    $data=CandidateDetail::where('id','=',$result1)->update(['candidate_code'=>$candidate_code,'name'=>strtolower($request->cname),'email'=>strtolower($request->email),'country'=>$request->country,'phone'=>$request->phone,'gender'=>$request->gender,'state'=>$request->state,'city'=>$request->city,'job_role'=>$request->job_role,'total_experience'=>$request->total_experience,'dob'=>$request->dob]);
                    if($data)
                    {
                    return redirect('candidate_list')->with('success','Candidate Basic Details Updated Successfully.');
                    }

                }              
                             
        
    }

    public function list(Request $request)
    {
        /**
         * Candidate List Page View
         * Input:cname,email,phone,state,city,status.
         * Output:candidates,states.
         */

        $this->authorize("access-manage-candidate");
        $allHr=NULL;
        $searchData=$request->all();
        $states=State::where('status','=','Active')->get();
        $physical_joining_attributes = MatrixAttribute::where('category','physical_joining')->get();
        $query = CandidateDetail::where([['added_by','!=',1],['user_id','=',0]])->orderBy('id','DESC');
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
        if($request->assign_to) {		
			if($request->assign_to!=''){
				$query->where('assign_to',$request->assign_to);
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
       
			if($request->status!=''){
				$query->where('is_selected',$request->status);
			}
		 
        
        if((Auth::user()->account_type!='hr') && (Auth::user()->account_type!='business'))
        {
            if($request->export)
            {
                $expData=$query->get();
                return Excel::download(new UsersExport('candidate',$expData), 'CandidateList.xlsx');             
                
            }

            $allHr=User::where('account_type','=','hr')->get();
            $candidates=$query->paginate(5);
            $candidates->appends(request()->query());
        }

        else if(Auth::user()->account_type=='hr')
        {
            if($request->export)
            {
                $expData=$query->where('assign_to','=',Auth::user()->id)->get();
                return Excel::download(new UsersExport('candidate',$expData), 'CandidateList.xlsx');             
                
            }
            // $candidates=$query->where('business_id','=',Auth::user()->parent_id)->paginate(5);
            $candidates=$query->where('assign_to','=',Auth::user()->id)->paginate(5);
            $candidates->appends(request()->query());
        }    

        else if(Auth::user()->account_type=='business')
        {         
            if($request->export)
            {
                $expData=$query->where('business_id','=',Auth::user()->id)->get();
                return Excel::download(new UsersExport('candidate',$expData), 'CandidateList.xlsx');             
                
            }   
            $allHr=User::where([['account_type','=','hr'],['parent_id',Auth::user()->id]])->get();
            $candidates=$query->where('business_id','=',Auth::user()->id)->paginate(5);       
            $candidates->appends(request()->query());
        }
        // dd($candidates);
        return view('admin.candidate.list',compact('candidates','states','searchData','allHr','physical_joining_attributes'));
        
    }

    public function candidateView(Request $request)
    {
        /**
         * Candidate Resume Page View
         * input: base64_encode(id)
         * Output:candidate,education_details,profession_details,skills,languages,hobbies.
         */
        
        $id=base64_decode($request->id);
        
        if (Auth::user()->account_type=='candidate')
        {
            $this->authorize("access-candidate-self-profile");
        }
        else{
            $this->authorize("access-manage-candidate");
        }
        $candidate=CandidateDetail::where('id','=',$id)->first();
        if(!$candidate){
            return redirect()->back()->with('error','Candidate not found');
        }
        if((Auth::user()->account_type=='candidate') && (Auth::user()->id!=$candidate->user_id))
        {
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
        $education_details=CandidateEducationDetail::where('candidate_id','=',$id)->orderBy('education_type','ASC')->get();
        $profession_details=CandidateProfessionalDetail::where('candidate_id','=',$id)->orderBy('from_date','DESC')->get();
        $other_details=CandidateOtherDetail::where('candidate_id','=',$id)->get();
        
        $languages=CandidateOtherDetail::where([['candidate_id','=',$id],['type','=','language']])->get();
        // $hobbies=CandidateOtherDetail::where([['candidate_id','=',$id],['type','=','hobby']])->get();
        return View::make('admin.candidate.candidateview',compact('candidate','education_details','profession_details','other_details','languages'));
    }

    public function BasicDetailsView(Request $request)
    {
       
        /**
         * Candidate Basic Details Page View
         * input:base64_encode(id).
         * output:candidate,states,cities,job_role.
         */
        // $this->authorize("access-manage-candidate");
        //$this->authorize("access-candidate-self-profile");

        if (Auth::user()->account_type=='candidate')
        {
            $this->authorize("access-candidate-self-profile");
        }
        else{
            $this->authorize("access-manage-candidate");
        }

        //dd(auth()->user()->can('access-candidate-self-profile'));

        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();
        $job_role=JobRole::where('status','=',1)->orderBy('name','ASC')->get();
        
        $country=Country::get();
        $states=State::where('status','=','Active')->get();
        $cities=City::where('state_id','=',$candidate->state)->where('status','=',1)->get();
        //dd(State::where('country_id','=',$candidate->country)->where('status1','=','Active')->get());

        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }
            if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
            {
                $flag=0;
            }

            if($flag==1)
            {
               
                // dd($states);
                return view('admin.candidate.basicdetails',compact('candidate','states','cities','job_role','country'));

            }
            else{
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
           
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->id==$candidate->assign_to)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
            
            
            return view('admin.candidate.basicdetails',compact('candidate','states','cities','job_role','country'));
            }
            else{
                // return abort(403,"You do not have access for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        }
        else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
        

    }

    public function BasicDetailsUpdate(Request $request)
    {
        
        /**
         * Candidate Basic Details Update
         * input:base64_encode(id),cname,email,gender,job_role,country,state,city,phone,total_experience,dob,cv_scan,phone2,photo,signature,cv_scan,photo_old,signature_old,cv_scan_old,religion,fname,mname,sname,present_address,permanent_address.
         * Output: Redirect With success/error. 
         */
        if (Auth::user()->account_type=='candidate')
        {
            $this->authorize("access-candidate-self-profile");
        }
        else{
            $this->authorize("access-manage-candidate");
        }
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();        
        if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
        {
            return redirect()->back()->with('error','You do not have permission for this');
        }
        
        $this->validate($request,[
            'cname' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
            // 'email' => 'required|email|unique:candidate_details,email,'.$id,
            'email' => 'required|email|check_mail',
            'gender' => 'required',
            'country' => 'required',
            // 'state' => 'required',
            // 'city' => 'required',
            //'job_role' => 'required',
            //'total_experience' => 'required',
            'phone'=>'required|numeric|digits_between:6,15',
    
            'phone2'=>'nullable|numeric|digits_between:6,15',
            // 'photo' => 'nullable|mimes:jpg,jpeg,png',
            // 'signature' => 'nullable|mimes:jpg,jpeg,png',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:2000',
            'signature' => 'nullable|mimes:jpg,jpeg,png|max:2000',
            'cv_scan' => 'nullable|mimes:doc,pdf|max:2000',
            // 'pan_file' => 'Rule::requiredIf(($request->pan_no!=null) && ($request->pan_old==null))|mimes:jpg,jpeg,png|max:2000',
            
            // // 'passport_file' => 'Rule::requiredIf(("passport_no","!=",null) && ("passport_old",null))|mimes:jpg,jpeg,png|max:2000',
            // 'dl_file' => 'nullable|mimes:jpg,jpeg,png|max:2000',
            'passport_no' => 'nullable',
            'dl_no' => 'nullable',

            'dob'=>'required|date_format:Y-m-d|before:today',        
            'passport_exp_date'=>'required_unless:passport_no,null',       
            'dl_exp_date'=>'required_unless:dl_no,null' ,
            
              
        ],
        [
            'passport_exp_date.required_unless'=>'Expired Date Required',
             
            'dl_exp_date.required_unless'=>'Expired Date Required',
             
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
       
        $request->validate([
            'aadhaar_file' => [
                Rule::requiredIf($request->aadhaar_no!=null &&  $request->aadhaar_old==null),
                'mimes:jpg,jpeg,png','max:2000'
            ],
            'passport_file' => [
                Rule::requiredIf($request->passport_no!=null &&  $request->passport_old==null),
                'mimes:jpg,jpeg,png','max:2000'
            ],
            'pan_file' => [
                Rule::requiredIf($request->pan_no!=null &&  $request->pan_old==null),
                'mimes:jpg,jpeg,png','max:2000'
            ],
            'dl_file' => [
                Rule::requiredIf($request->dl_no!=null &&  $request->dl_old==null),
                'mimes:jpg,jpeg,png','max:2000'
            ],
        ]);
        
            //dd($request->all());
            $photo_link=$request->photo_old;
            $signature_link=$request->signature_old;
            $cv_scan_link=$request->cv_scan_old;
            $pan_link=$request->pan_old;
            $aadhaar_link=$request->aadhaar_old;
            $passport_link=$request->passport_old;
            $dl_link=$request->dl_old;

        //    if(($request->aadhaar_no!=null) && ($request->aadhaar_old==null)){
        //     return redirect()->back()->withInput('error','Aadhaar file is required');
        //    }


            if (((!empty($request->dl_file)) && (!empty($request->dl_old))) || ((!empty($request->dl_file)) && (empty($request->dl_old))))
            {
                if($request->dl_old!='')            
                {
                    $imagePath = public_path($request->dl_old);
                if(File::exists($imagePath)){
                    unlink($imagePath);
                }
                }
                $dl_link = $request->file('dl_file')
                        ->store('candidate');
            }

            if (((!empty($request->passport_file)) && (!empty($request->passport_old))) || ((!empty($request->passport_file)) && (empty($request->passport_old))))
            {
                if($request->passport_old!='')            
                {
                    $imagePath = public_path($request->passport_old);
                if(File::exists($imagePath)){
                unlink($imagePath);
                }
                }
                $passport_link = $request->file('passport_file')
                        ->store('candidate');
            }

            if (((!empty($request->aadhaar_file)) && (!empty($request->aadhaar_old))) || ((!empty($request->aadhaar_file)) && (empty($request->aadhaar_old))))
            {
                if($request->aadhaar_old!='')            
                {
                    $aadhaarPath = public_path($request->aadhaar_old);
                if(File::exists($aadhaarPath)){
                unlink($aadhaarPath);
                }
                }
                $aadhaar_link = $request->file('aadhaar_file')
                        ->store('candidate');
            }

            if (((!empty($request->pan_file)) && (!empty($request->pan_old))) || ((!empty($request->pan_file)) && (empty($request->pan_old))))
            {
                if($request->pan_old!='')            
                {
                    $panPath = public_path($request->pan_old);
                if(File::exists($panPath)){
                unlink($panPath);
                }
                }
                $pan_link = $request->file('pan_file')
                        ->store('candidate');
            }

        if (((!empty($request->photo)) && (!empty($request->photo_old))) || ((!empty($request->photo)) && (empty($request->photo_old))))
        {
            if($request->photo_old!='')            
            {
                $photoPath = public_path($request->photo_old);
            if(File::exists($photoPath)){
            unlink($photoPath);
            }
            }
            $photo_link = $request->file('photo')
                    ->store('candidate');
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
           
           $signature_link = $request->file('signature')
                   ->store('candidate');
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

        $data=CandidateDetail::where('id','=',$id)->update(['name'=>$request->cname,'email'=>strtolower($request->email),'phone'=>$request->phone,'phone2'=>$request->phone2,'gender'=>$request->gender,'country'=>$request->country,'state'=>$request->state,'city'=>$request->city,'dob'=>$request->dob,'total_experience'=>$request->total_experience,'religion'=>$request->religion,'present_address'=>$request->present_address,'resume_title'=>$request->resume_title,'photo'=>$photo_link,'signature'=>$signature_link,'cv_scan'=>$cv_scan_link,'pan_no'=>$request->pan_no,'pan_file'=>$pan_link,'aadhaar_no'=>$request->aadhaar_no,'aadhaar_file'=>$aadhaar_link,'dl_no'=>$request->dl_no,'dl_file'=>$dl_link,'dl_exp_date'=>$request->dl_exp_date,'passport_no'=>$request->passport_no,'passport_file'=>$passport_link,'passport_exp_date'=>$request->passport_exp_date,'updated_at'=>date('Y-m-d H:i:s'),'father_name'=>$request->father_name,'mother_name'=>$request->mother_name,'marital_status'=>$request->marital_status,'spouse_name'=>$request->spouse_name,]);

        $edited_data=json_encode($request->all());

        $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);
     
        if($data)
        {
            // return redirect('edit_candidate/'.$request->id)->with('success','Candidate Basic Details Updated Successfully.');
            return redirect()->back()->with('success','Candidate Basic Details Updated Successfully.');
        }
        
    }

    public function EducationDetailsView(Request $request)
    {
        /**
         * candidate Education Details Update Page View.
         * input:base64_encode(id).
         * Output:candidate_id,educations.
         */
        if (Auth::user()->account_type=='candidate')
        {
            $this->authorize("access-candidate-self-profile");
        }
        else{
            $this->authorize("access-manage-candidate");
        }
        $id=base64_decode($request->id);
        
        $educations=CandidateEducationDetail::where('candidate_id','=',$id)->orderBy('education_type','ASC')->get();
        $candidate_id=$id;
        $candidate=CandidateDetail::where('id','=',$id)->first();
        $educationTypes=EducationMaster::get();
        // dd(Auth::user()->id,$candidate->user_id);
       
        if(!$candidate)
        {
            return redirect()->back()->with('error','Candidate not found');
        }
        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }
            if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
            {
                $flag=0;
            }
            
            if($flag==1)
            {

              // action
              return view('admin.candidate.educationdetails',compact('candidate','educations','educationTypes'));

            }
            else{
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
           
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->id==$candidate->assign_to)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
            //action 
            return view('admin.candidate.educationdetails',compact('candidate','educations','educationTypes'));
            }
            else{
                // return abort(403,"You do not have access for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        }
        else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }

        
    }

    public function EducationDetailsUpdate(Request $request)
    {
         /**
         * candidate Education Details Update .
         * input:base64_encode(id),institute,degree,year_of_passing,marks,percentage.
         * Output:Redirect with success/error.
         */

     
      

         if (Auth::user()->account_type=='candidate')
         {
             $this->authorize("access-candidate-self-profile");
         }
         else{
             $this->authorize("access-manage-candidate");
         }     
           
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();        
        if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
        {
            return redirect()->back()->with('error','You do not have permission for this');
        }
           // dd($value);
            $this->validate($request, [
                'institute' => 'required|regex:/^[a-zA-Z. ]+$/u|min:3',
                'education_type' => 'required',
                'degree' => 'required',
                'year_of_passing'=>'nullable|numeric|digits:4|gt:1900',
                'marks'=>'nullable|string',
                'percentage'=>'nullable|numeric|between:0,99.99|gt:0',
                'doc_file'=>'nullable|mimes:jpg,jpeg,png|max:1048'
                    
                ],
                [
                    'institute.required'=>'Institute Name Required',
                    'institute.min'=>'Institute Name should be minimum 3 letters',
                    'institute.regex'=>'Institute Name should be in alphabets only.',
    
                    'education_type.required'=>'Education Type Required',
                    'degree.required'=>'Degree Name Required',
                    
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
                    'doc_file.max'=>'File is too large to upload',
                    'doc_file.mimes'=>'File must be type of jpg/jpeg',
                ]);
            
              
             $degree=$request->degree;
             $doc_file_link=null;
             if($request->degree==9999){
               $is_course_exist=CourseMaster::where('course_name',$request->addcourse)->first();
       
               if(!$is_course_exist){
                  $addcourse= CourseMaster::create(['course_name'=>$request->addcourse,'education_master_id'=>$request->education_type]);
                  $degree=$addcourse->id;
               }
             }
       
             if ($request->doc_file)
               {
                  
       
                  $doc_file_link = $request->file('doc_file')
                          ->store('candidate');
               }
                
               
                $data=CandidateEducationDetail::create(['candidate_id'=>$id,'institute_name'=>$request->institute,'education_type'=>$request->education_type,'degree'=>$degree,'specialization'=>$request->specialization,'year_of_passing'=>$request->year_of_passing,'marks'=>$request->marks,'percentage'=>$request->percentage,'doc_file'=>$doc_file_link]);
                
         
                if($data)
                {
                    $edited_data=json_encode($request->all());

                    $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);

                    return redirect()->back()->with('success','Education Details Added Successfully');
                }
    }

    public function deleteEducation($id)
    {
        /**
         * Delete Education 
         * input:id
         * Output:Redirect with success/error
         */
        if (Auth::user()->account_type=='candidate')
        {
            $this->authorize("access-candidate-self-profile");
        }
        else{
            $this->authorize("access-manage-candidate");
        }
        $education=CandidateEducationDetail::where('id','=',$id)->first();
        $candidate=CandidateDetail::where('id','=',$education->candidate_id)->first();      
       
        if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
        {
            return redirect()->back()->with('error','You do not have permission for this');
 
        }
         
        $education->delete();
        if(File::exists($education->marksheet_doc)){
            unlink(storage_path('app/'.$education->marksheet_doc));
        }
        return redirect()->back()->with('success','Education Degree Deleted');
    }

    public function ProfessionalDetailsView(Request $request)
    {
        /**
         * Professional Details Update Page View 
         * input:base64_encode(id)
         * Output:candidate_id,profession
         */

         if (Auth::user()->account_type=='candidate')
         {
             $this->authorize("access-candidate-self-profile");
         }
         else{
             $this->authorize("access-manage-candidate");
         }
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();



        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }
            if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
            {
                $flag=0;
            }
            if($flag==1)
            {
              // action
            $profession=CandidateProfessionalDetail::where('candidate_id','=',$id)->orderBy('from_date','DESC')->get();
            $candidate_id=$id;        
       
            return view('admin.candidate.professionaldetails',compact('candidate','profession'));

            }
            else{
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
           
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->id==$candidate->assign_to)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
            //action 
            $profession=CandidateProfessionalDetail::where('candidate_id','=',$id)->orderBy('from_date','DESC')->get();
            $candidate_id=$id;        
       
            return view('admin.candidate.professionaldetails',compact('candidate','profession'));
            }
            else{
                // return abort(403,"You do not have access for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        }
        else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
 
    }

    public function ProfessionalDetailsUpdate(Request $request)
    {
        /**
         * Professional Details Update
         * Input:base64_encode(id),company,job_role,from_date,to_date,description.
         * Output: Redirect with success/error.
         */
        
         if (Auth::user()->account_type=='candidate')
         {
             $this->authorize("access-candidate-self-profile");
         }
         else{
             $this->authorize("access-manage-candidate");
         }
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();        
        if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
        {
            return redirect()->back()->with('error','You do not have permission for this');
        }
                  //dd($request->cc);
            $this->validate($request, [
                'company' => 'required|regex:/^[a-zA-Z0-9. ]+$/u|min:3',
                'job_role' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
                'from_date'=>'required|date_format:Y-m-d|before:today',
                'to_date'=>'nullable|date_format:Y-m-d|after:from_date',            
                'cur_salary'=>'required_if:cc,"yes"|between:1,9999999.9999|numeric',            
                'cur_location'=>'required_if:cc,"yes"|string'            
              
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
            
            if($request->cc=='yes' && $request->to_date==NULL){
                CandidateProfessionalDetail::where([['candidate_id',$id],['current_company','=','yes']])->update(['current_company'=>'no','to_date'=>$request->from_date]);
            }
            if(($request->cc=='no' && $request->to_date!=NULL) || ($request->cc=='yes' && $request->to_date==NULL)) {

                $data=CandidateProfessionalDetail::insert(['candidate_id'=>$id,'company_name'=>$request->company,'job_role'=>$request->job_role,'from_date'=>$request->from_date,'to_date'=>$request->to_date,'current_company'=>$request->cc,'current_salary'=>$request->cur_salary,'current_location'=>$request->cur_location,'description'=>$request->description]);
           
         
                if($data)
                {
                    $edited_data=json_encode($request->all());

                    $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);
                        
                    return redirect()->back()->with('success','Professional Details Added Successfully');
                }
            }
             
            else{
                return redirect()->back()->with('error','To Date Required (if not Current Company)');
            }
    }

    public function deleteProfession($id)
    {        
        /**
         * Delete Profession
         * input:id(profession id)
         * output: Redirect with success/error.
         */
        if (Auth::user()->account_type=='candidate')
        {
            $this->authorize("access-candidate-self-profile");
        }
        else{
            $this->authorize("access-manage-candidate");
        }
        $profession=CandidateProfessionalDetail::where('id','=',$id)->first();
        $candidate=CandidateDetail::where('id','=',$profession->candidate_id)->first();      
       
        if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
        {
            return redirect()->back()->with('error','You do not have permission for this');
 
        }
        $profession->delete();
        return redirect()->back()->with('success','Profession details deleted');
    }

    public function OthersDetailsView(Request $request)
    {
        /**
         * Other Details Update Page View
         * input:base64_encode(id).
         * Output:candidate_id,others.
         */
        if (Auth::user()->account_type=='candidate')
        {
            $this->authorize("access-candidate-self-profile");
        }
        else{
            $this->authorize("access-manage-candidate");
        }
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();
        $industries=Industry::orderBy('name','ASC')->get();

        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }
            if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
            {
                $flag=0;
            }
            if($flag==1)
            {
              // action
              $others =CandidateOtherDetail::where('candidate_id','=',$id)->orderBy('id','DESC')->get();       
              $candidate_id=$id;       
              return view('admin.candidate.otherdetails',compact('candidate','others','industries'));

            }
            else{
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
           
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->id==$candidate->assign_to)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
            //action 
            $others =CandidateOtherDetail::where('candidate_id','=',$id)->orderBy('id','DESC')->get();       
            $candidate_id=$id;       
            return view('admin.candidate.otherdetails',compact('candidate','others','industries'));
            }
            else{
                // return abort(403,"You do not have access for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        }
        else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }


        
    }

    public function OthersDetailsUpdate(Request $request)
    {
         
        /**
         * Other Details Update
         * Input:base64_encode(id),type, key,value, desc.
         * Output:Redirect with success/error
         */
       
       if (Auth::user()->account_type=='candidate')
       {
           $this->authorize("access-candidate-self-profile");
       }
       else{
           $this->authorize("access-manage-candidate");
       }
        $id=base64_decode($request->candidate_id);
        $candidate=CandidateDetail::where('id','=',$id)->first();        
        if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
        {
            return redirect()->back()->with('error','You do not have permission for this');
        }
        if($request->val==''){
            return response()->json(['status' => false,'msg'=>'Required Filed value']);  
        }
        $othersValue=$request->val;

        if($request->val=='Other'){
            if($request->addtomaster==''){
                return response()->json(['status' => false,'msg'=>'Required Filed value']);  
            }
                $exist=DB::table($request->dbname)->where(['name'=>$request->addtomaster])->first();
                if(!$exist){
                    $addToMasterTable=DB::table($request->dbname)->insert(['name'=>$request->addtomaster]);
                   
                }
                $othersValue=$request->addtomaster;
            
        }
        $data=CandidateOtherDetail::insert(['candidate_id'=>$id,'type'=>$request->typ,'value'=>$othersValue]);
        if($data){
            $data=CandidateOtherDetail::where([['candidate_id',$id],['type',$request->typ]])->orderBy('id','DESC')->get();
            return response()->json(['status' => true,'data'=>$data, 'msg'=>$request->typ.' Added Successfully']);
        }
        
        // dd($request->all());
        //  if($request->skill[0]['value']==NULL && $request->language[0]['value']==NULL && $request->hobby[0]['value']==NULL)
        //  {
        //     return redirect()->back()->with('error','No Data Added');
        //  }
        // foreach($request->all() as $key => $value){
        //     if($key=='skill' || $key=='language' || $key=='hobby'){
        //         foreach($value as $data){
        //             if($data['value']!=null){
        //                 $data=CandidateOtherDetail::insert(['candidate_id'=>$id,'type'=>$key,'value'=>$data['value'],'description'=>$data['desc']]);
                        
        //             }
                    
        //         }
        //     }
             
        // }
        // $edited_data=json_encode($request->all());

        // $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);
        // return redirect()->back()->with('success','Other Details Added Successfully');
    }
   
    public function deleteOthers(Request $request)
    {
        /**
         * Delete Other Details
         * Input: id (Other Details).
         * Output:Redirect with success/error.
         */
         
         if (Auth::user()->account_type=='candidate')
         {
             $this->authorize("access-candidate-self-profile");
         }
         else{
             $this->authorize("access-manage-candidate");
         }

        $others=CandidateOtherDetail::where('id','=',$request->id)->first();

        $candidate=CandidateDetail::where('id','=',$others->candidate_id)->first();        
        if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
        {
            return response()->json(['status' => false,'data'=>0, 'msg'=>'You do not have permission for this']);
 
        }
        
      //  dd($others);
               $others->delete();

        $data= CandidateOtherDetail::where([['type',$others->type],['candidate_id',$others->candidate_id]])->get();
               return response()->json(['status' => true,'data'=>$data, 'msg'=>'Deleted Successfully']);
        // return redirect()->back()->with('success','Other details deleted');
    }

    public function isSelected(Request $request)
    {
        /**
         * Select/Deselect Candidate
         * Input:candidate_id
         * Output:msg.
         */
        $this->authorize("access-manage-candidate");
        $candidate_id=$request->candidate_id;
        $old_status=CandidateDetail::where('id','=',$candidate_id)->first();
        if($old_status->is_selected==1){
            $update_candidate=CandidateDetail::where('id','=',$candidate_id)->update(['is_selected'=>2,'status'=>0]); //for deselect
        }
        else{
            $update_candidate=CandidateDetail::where('id','=',$candidate_id)->update(['is_selected'=>1,'status'=>1]); //for select
        }
        
        if($update_candidate)
        {
            return $msg=1;
        }
        else
        {
            return $msg=2;
        }
       
    }

    public function uploadDocumentView(Request $request)
    {
        /**
         * Upload Document Page View
         * input:base64_encode(id)
         * output:candidate_id,documents,allTypes.
         */
        if (Auth::user()->account_type=='candidate')
        {
            $this->authorize("access-candidate-self-profile");
        }
        else{
            $this->authorize("access-manage-candidate");
        }
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();
        $allTypes=DocumentType::where('status','=',1)->orderBy('name','DESC')->get();



        if((Auth::user()->account_type!='hr'))
        {
            $flag=1;

            if((Auth::user()->account_type=='business' && Auth::user()->id!=$candidate->business_id))
            {
                $flag=0;
            }
            if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
            {
                $flag=0;
            }
            if($flag==1)
            {
              // action
            $documents=CandidateDocument::where('candidate_id','=',$id)->get();
            $candidate_id=$id;        
       
            return view('admin.candidate.document_upload',compact('candidate','documents','allTypes'));

            }
            else{
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
           
        }
        
        else if(Auth::user()->account_type=='hr' && Auth::user()->id==$candidate->assign_to)
        {
            $status=$this->chkUserAccess(Auth::user()->id,2);
            if($status!=0){
            //action 
            $documents=CandidateDocument::where('candidate_id','=',$id)->get();
            $candidate_id=$id;        
       
            return view('admin.candidate.document_upload',compact('candidate','documents','allTypes'));
            }
            else{
                // return abort(403,"You do not have access for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        }
        else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
 
    }
    public function uploadDocumentStore(Request $request)
    {
        /**
         * Upload Document Store
         * Input: base64_encode(id),doc_type,doc_name,doc_file.
         * Output:Redirect With Success/error.
         */

         if (Auth::user()->account_type=='candidate')
         {
             $this->authorize("access-candidate-self-profile");
         }
         else{
             $this->authorize("access-manage-candidate");
         }
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();        
        if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
        {
            return redirect()->back()->with('error','You do not have permission for this');
        }
        $this->validate($request,[
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
        $doc_file_link = $request->file('doc_file')
                    ->store('candidate');
        $data=CandidateDocument::insert(['candidate_id'=>$request->candidate_id,'doc_type'=>$request->doc_type,'doc_name'=>$request->doc_name,'doc_file'=>$doc_file_link,'uploaded_by'=>Auth::user()->id]);
        if($data)
        {
            return redirect()->back()->with('success','Document Uploaded Successfully');
        }
            
    }
    public function deleteDocument($id)
    {
         /**
         * Upload Document Delete
         * Input: id(document).
         * Output:Redirect With Success/error.
         */

         if (Auth::user()->account_type=='candidate')
         {
             $this->authorize("access-candidate-self-profile");
         }
         else{
             $this->authorize("access-manage-candidate");
         }
        $doc=CandidateDocument::where('id','=',$id)->first();
        $candidate=CandidateDetail::where('id','=',$doc->candidate_id)->first();        
        if((Auth::user()->account_type=='candidate' && Auth::user()->id!=$candidate->user_id))
        {
           
            return redirect()->back()->with('success','You do not have permission for this');
 
        }
        $doc->delete();
        $imagePath=$doc->doc_file;
        if(File::exists($imagePath)){
        unlink($imagePath);
        }
        //unlink(storage_path('app/'.$education->marksheet_doc));
       
        return redirect()->back()->with('success','Document Deleted');
    }


    public function joiningDetailsView(Request $request)
    { 
        $this->authorize("access-manage-candidate");
        $id=base64_decode($request->id);
        // $candidate=CandidateDetail::where('id','=',$id)->first();
         
        
        $offer_letter=offerLetter::where([['id','=',$id],['is_accepted','=',3]])->orderBy('id','DESC')->first();
        if(!$offer_letter){
            return redirect()->back()->with('error','Offer Letter Not found');
        }
        $reschedule=DB::table('reschedule')->where('offer_letter_id','=',$id)->orderBy('id','DESC')->first();
       // dd($candidate);
       
        return View::make('admin.candidate.joiningdetails',compact('offer_letter','reschedule'));
         
    }

    public function joiningDetailsUpdate(Request $request)
    {

        $this->authorize("access-manage-candidate");
        $id=base64_decode($request->id);

        //  dd($request->all());

        $offer_id = $id;   


        $offer_letter=offerLetter::where('id','=',$offer_id)->orderBy('id','DESC')->first();
        $reschedules=DB::table('reschedule')->where('offer_letter_id','=',$offer_id)->orderBy('id','DESC')->first();
        if($reschedules->hr_response!=0)
        {
            
            return redirect()->back()->with('error','Already Responsed for this Request');
        }
          //dd($offer_letter);

            if(((Auth::user()->account_type=='hr') && (Auth::user()->id!=$offer_letter->hr_id)) || ((Auth::user()->account_type=='business') && (Auth::user()->id!=$offer_letter->business_id)))
            {
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }

           

            if($request->hr_response==1)
            {              
                $reschedule_update=DB::table('reschedule')->where('offer_letter_id','=',$id)->update(['hr_response'=>1]);
                offerLetter::where('id','=',$offer_id)->update(['is_accepted'=>1,'is_rescheduled'=>1,'joining_date'=>$reschedules->new_joining_date,'time_of_joining'=>$reschedules->new_joining_time,'joining_confirmed'=>1]);
                CandidateDetail::where('id','=',$offer_letter->candidate_id)->update(['joining_confirmed'=>1,'status'=>31]);
                return redirect('offer_letter_list')->with('success','Rescheduled Accepted Successfully');
            }           
            else
            {
                
                $reschedule_update=DB::table('reschedule')->where('offer_letter_id','=',$id)->update(['hr_response'=>2,'hr_remark'=>$request->hr_rem]);
                offerLetter::where('id','=',$offer_id)->update(['is_accepted'=>0]);
                return redirect('offer_letter_list')->with('success','Rescheduled Rejected Successfully');
            }

            // $data=CandidateDetail::where('id','=',$candidate_id)->first();

            // dd(1);
           

            
            // $offer_letter_update=offerLetter::where('id','=',$offer_letter->id)->update(['is_accepted'=>1,'is_rescheduled'=>1,'joining_date'=>$reschedules->new_joining_date,'time_of_joining'=>$reschedules->new_joining_time,'joining_confirmed'=>1]);
            // $update_candidate=CandidateDetail::where('id','=',$offer_letter->candidate_id)->update(['joining_confirmed'=>1,'status'=>31]);
           // $reschedule_update=DB::table('reschedule')->where([['candidate_id','=',$candidate_id],['offer_letter_id','=',$offer_letter->id]])->update();
        
        // if($offer_letter_update)
        // {
        //     $edited_data=json_encode($request->all());

        //     $change_log=DB::table('candidate_change_logs')->insert(['candidate_id'=>$offer_letter->candidate_id,'edited_by'=>Auth::user()->id,'edited_data'=>$edited_data]);
        //     return redirect('offer_letter_list')->with('success','Rescheduled Successfully');
        // }
        // else
        // {
        //     return redirect()->back()->with('error','Something Was Wrong');
        // }
        
    }

    public function reviewDetailsView(Request $request)
    { 
        $this->authorize("access-manage-candidate");
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();
       // dd($candidate);
     
       if(($candidate->hr_id==Auth::user()->id) || ($candidate->business_id==Auth::user()->id)){
        return View::make('admin.candidate.reviewdetails',compact('candidate'));
       }
       else{
        // return abort(403,"You do not have permission for this");
        return redirect()->back()->with('error','You do not have permission for this');
       }
    }

    public function reviewDetailsUpdate(Request $request)
    {
        $this->authorize("access-manage-candidate");
        $id=base64_decode($request->id);
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

   

    public function LoginView()
    { 
        return View::make('admin.candidate.login');
    }
    public function LogOut()
    { 
        Session::flush();
        return redirect('candidate/login');
    }
    public function sendOtp(Request $request)
    { 
        $this->validate($request,[
            'email'=>'required|email|check_mail|exists:candidate_details,email',
            //'phone'=>'nullable|numeric|digits_between:6,15'            
        ],
        [
            'email.required'=>'Email Required',
            'email.check_mail'=>'Email format invalid',
            'email.exists'=>'Email not exists',
            'phone.numeric'=>'Phone Must be in numerics',
            'phone.digits_between'=>'Phone must be between 6 to 15 digits',
        ]);
        $email=strtolower($request->email);
        
        $in_user=User::where([['email','=',$email],['account_type','=','candidate']])->pluck('id')->first();
        if($in_user)
        {
            //email is in User Table
            return $msg=33;
            // return redirect('/login')->with('error','Already You Have an account! Please Login with Password');

        }
        else
        {
            $new_otp=rand(111111,999999);
            Session::put('otp', $new_otp);                    
            try{
            Mail::to($email)->queue(new SendOtp($new_otp));
            return $msg=1;
            }
            catch(\Exception $ex){
            $stack_trace = $ex->getTraceAsString();
            $message = $ex->getMessage().$stack_trace;
            //dd($message);
            Log::error($message);
            return $msg=2;
            }
        } 
        
    }

    public function LoginCheck(Request $request)
    { 

         //dd($request->all(),Session::get('otp'));
        $this->validate($request,[
            'email'=>'required|email|check_mail|exists:candidate_details,email',
            //'phone'=>'required|numeric|digits_between:6,15',
            'otpnumber'=>'required|numeric|digits:6'
        ]);
        

            $email=strtolower($request->email);
            $phone=$request->phone;
            $otp=$request->otpnumber;
        //$candidate=CandidateDetail::where([['email','=',$email],['phone','=',$phone]])->first();
        //dd($candidate->phone);
        if(($otp==Session::get('otp')))
        {
            $candidate=CandidateDetail::where('email','=',$email)->first();
             Session::put('candidate', $email);
             return $msg=3;
        }
        else
        {
            return $msg=4;
        }
    }

    public function candidateHome()
    { 
        if(Session::get('candidate'))
        {
                $candidate_email=  Session::get('candidate');
                
          
                $candidates=CandidateDetail::where([['email','=',$candidate_email],['user_id','=',0]])->orderBy('id','DESC')->pluck('id')->toArray();
               
                $offer_letter=OfferLetter::whereIn('candidate_id',$candidates)->get();
                 

                //dd($code);
                $reasons=DB::table('reschedule_reasons')->get();
               // $candidate=CandidateDetail::where('id','=',$candidate_id)->first();
               // $offer_letter=OfferLetter::where('candidate_id','=',$candidate_id)->first();
                //$old_offer_letter=OfferLetter::where('candidate_id','=',$candidate_id)->get();
                // $salary_breakup=json_decode($offer_letter->salary_breakup);
                // $earning=$salary_breakup->earning;
                // $deduction=$salary_breakup->deduction;
                //dd($salary_breakup->earning);
                return view('admin.candidate.setPassword');
        }
        else
        {
            return redirect('candidate/login');
        }
        
    }

    public function setPassword(Request $request)
    {
        $this->validate($request,[
            'password' => 'required|string|min:8|confirmed'             
        ]);     

      
            
            try{
             
                $result = DB::transaction(function () use ($request) {
                    
                $email=Session::get('candidate');
                $candidate=CandidateDetail::where('email','=',$email)->first();
                
                    $user=User::create([
                        'first_name' => $candidate->name,
                        'email' => $candidate->email,
                        'password' => Hash::make($request->password),
                        'verification_token' => Str::random(60),
                        'account_type' => 'candidate',
                        'is_email_verified' => 1
                    ]);
                    
                    $profile = Profile::create([
                        'user_id'           => $user->id,
                        'mobile_no'         => $candidate->phone
                        
                    ]);

                    $candidate=CandidateDetail::create([
                        'candidate_code'=>"REC".$user->id,
                        'user_id'=>$user->id,
                        'name'=>$candidate->name,
                        'email'=>$candidate->email, 
                        'phone'=>$candidate->phone, 
                        'added_by'=>0,
                        'business_id'=>0,
                        'hr_id'=>0
                    ]);
        
                  
        
                    $helper = new CommonHelper;
                    $result = $helper->saveAssignedRole($user->id,$user->account_type);  
        
               }); 
                
             return redirect('/login')->with('success','Your Password set successfully. Please Login');
             
             
            } catch (\Exception $e) {//dd($e->getMessage());
                    // return $status=2;
                    return redirect('/login')->with('error','Something was wrong');
                     
                }

         

        
    }
    

    public function DisputeView(Request $request)
    {
        $id=base64_decode($request->id);
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

    public function candidateFollowUpList(Request $request){
    
        $id=base64_decode($request->id);
          

        $all_fup=CandidateFollowUp::where('candidate_id','=',$id)->orderby('id','DESC')->get();
        $candidate=CandidateDetail::where('id','=',$id)->first();

        $maxlead=CandidateFollowUp::where('candidate_id','=',$id)->max('id');
        //dd($maxlead);
        $maxstatus=CandidateFollowUp::where('id','=',$maxlead)->pluck('status')->first();
         
        return view('admin.candidate.candidateFollowUp',compact('all_fup','id','candidate','maxstatus'));
    }
    
    public function candidateFollowUpStore(Request $request)
    {
        $id=base64_decode($request->id);

        $this->validate($request, [
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

        if(($request->maxstatus==NULL) || ($request->maxstatus==2))
        {

                $data=CandidateFollowUp::insert(['candidate_id'=>$id,'hr_id'=>Auth::user()->id,'date'=>date('Y-m-d H:i:s'),'remarks'=>$request->remarks,'next_contact_date'=>$request->next_date,'next_time'=>$request->next_time,'status'=>1]);
                if($data)
                {
                    return redirect()->back()->with('success','Remarks Submitted');
                }
                else
                {
                    return redirect()->back()->with('error','Something was wrong.');
                }
        }
        else
            {
                return redirect()->back()->with('error','Last Followup not verified!');
            }

    }

    public function candidateFollowUpStatusUpdate($id)
    {
        $data=CandidateFollowUp::where('id',$id)->update(['status'=>2]);
        if($data)
        {
            return redirect()->back()->with('success','Verified');
        }
        else
        {
            return redirect()->back()->with('error','Something was wrong.');
        }
    }


    public function ApiExportCsv(Request $request)
    {
        /**
         * for Export CSV file of Candidate List for APP
         * Input:csvid
         * Output:Candidate List
         */

         $csvid=$request->id;
        $fileName = 'candidate.csv';
        $business_id=User::where([['csv','=',$csvid],['account_type','=','hr']])->pluck('parent_id')->first();

        //$candidates = CandidateDetail::where('user_id','=',0)->get();
        $candidates = CandidateDetail::where([['user_id','=',0],['business_id','=',$business_id]])->orderBy('id','DESC')->get();
        

         
           
        
        
        //dd($candidates);
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Name', 'Email', 'Phone', 'Gender','State','City','Job Role','Total Experience');

        $callback = function() use($candidates, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($candidates as $task) {
                $row['Name']  = $task->name;
                $row['Email']    = $task->email;
                $row['Phone']    = $task->phone;
                $row['Gender']  = $task->gender;
                $row['State']  = $task->stateDetails->state_title;
                $row['City']  = $task->cityDetails->name;
                $row['Job Role']  = $task->jobRole->name;
                $row['Total Experience']  = $task->total_experience;
                

                fputcsv($file, array($row['Name'], $row['Email'], $row['Phone'], $row['Gender'],$row['State'],$row['City'],$row['Job Role'],$row['Total Experience']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function editCandidate(Request $request){
        $id=base64_decode($request->id);
        $this->authorize("access-manage-candidate");   
        
         
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();
        return view('admin.candidate.editCandidate',compact('candidate'));
    }

    public function ratingReviewStore(Request $request)
    {
        $this->authorize("access-manage-candidate");

        $this->validate($request, [
            'reting'=>'required|numeric',
            'review'=>'required|string'            
        ]);
        // dd(1);
        $data=CandidateDetail::where('id','=',$request->candidate_id)->update(['rating'=>$request->rating,'review'=>$request->review]);
                 
        //unlink(storage_path('app/'.$education->marksheet_doc));
        return $msg=1;
       
    }
    public function reallotCandidate(Request $request)
    {
        $this->authorize("access-manage-candidate");
//  dd($request->candidate_id);
        $this->validate($request, [
            'candidate_id'=>'required',
            'hr_id'=>'required|numeric'            
        ],
        [
            'candidate_id.required'=>'Candidate not found',
            'hr_id.required'=>'Please Select HR'
        ]);
        
            // dd($request->all());
            //dd(count($request->candidate_id));
            foreach($request->candidate_id as $cid){
                //dd($cid);
                $cd=CandidateDetail::where('id','=',$cid)->first();
            $data=CandidateDetail::where('id','=',$cid)->update(['assign_to'=>$request->hr_id]);
            $createData=DB::table('reallotment_candidate')->insert(['candidate_id'=>$cid,'old_hr_id'=>$cd->assign_to,'new_hr_id'=>$request->hr_id,'assign_by'=>Auth::user()->id]);
            }
            return $msg=1;
    //     if($createData)
    //    {
    //     // return redirecct('candidate_list')->with('success','Candidate Re-Allot Successfully!');
    //     return $msg=1;
    //    }
    //    else
    //    {
    //     // return redirecct('candidate_list')->with('error','Something was wrong!');
    //     return $msg=0;
    //    }
    }
    public function adminCandidateList(Request $request)
    {
        // dd(25);
        /**
         * Candidate List Page View
         * Input:cname,email,phone,state,city,status.
         * Output:candidates,states.
         */

        if(Auth::user()->account_type=='superadmin')
        {
        
            $searchData=$request->all();

            $query = CandidateDetail::where('added_by',Auth::user()->id)->orWhere('user_id','!=',null)->orderBy('id','DESC');
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

            if($request->export)
            {
                $expData=$query->get();
                return Excel::download(new UsersExport('candidate',$expData), 'CandidateList.xlsx');             
                
            }

            
            $candidates=$query->paginate(5);
            $candidates->appends(request()->query());
            return view('admin.admin-candidate-list',compact('candidates','searchData'));
        
        }
        else{
            return redirect()->back()->with('error','You have no permission!');
        }

    }
    public function professionalFeedback(Request $request)
    { 
        $this->authorize("access-manage-candidate");
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();
        $attributes=MatrixAttribute::where('category','=','professional_feedback')->get();
        
        $professional_feedback = DB::table('professional_feedback')->where('candidate_id',$candidate->id)->select('attribute','point')->get()->toArray();
      
     
       if(($candidate->hr_id==Auth::user()->id) || ($candidate->business_id==Auth::user()->id)){
        return View::make('admin.candidate.professional_feedback',compact('candidate','attributes','professional_feedback'));
       }
       else{
        // return abort(403,"You do not have permission for this");
        return redirect()->back()->with('error','You do not have permission for this');
       }
    }
    public function professionalFeedbackStore(Request $request)
    { 
        // dd($request->all());
        
        $this->authorize("access-manage-candidate");
        $id=base64_decode($request->id);
        $candidate=CandidateDetail::where('id','=',$id)->first();
        
     
       if(($candidate->hr_id==Auth::user()->id) || ($candidate->business_id==Auth::user()->id)){

            $all_req=$request->attribute;
            // dd($all_req);
            foreach($all_req as $key=>$req){                        
                $professional_feedback = DB::table('professional_feedback')->updateOrInsert([
                    'candidate_id'=>$candidate->id,'attribute'=>$key],[                    
                    'point'=>$req,
                    'business_id'=>$candidate->business_id,
                    'added_by'=>Auth::user()->id,
                ]);
            }
            return redirect()->back()->with('success','Professional feedback added successfully!');
       }
       else{
        // return abort(403,"You do not have permission for this");
        return redirect()->back()->with('error','You do not have permission for this');
       }
    }


    public function physical_joining_point_store(Request $request)
    {
        $this->authorize("access-manage-candidate");
 
        $this->validate($request, [
            'candidate_id'=>'required|numeric',
            'rating'=>'required|numeric',
                        
        ]);
        // dd(1);
        $data=CandidateDetail::where('id','=',$request->candidate_id)->update(['physical_joinig_point'=>$request->rating]);
                 
        //unlink(storage_path('app/'.$education->marksheet_doc));
        return $msg=1;
       
    }

    
}
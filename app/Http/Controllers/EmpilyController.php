<?php

namespace App\Http\Controllers;

use App\Libs\CommonHelper;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\BusinessDetail;
use App\Models\LeadFollowUp;
use App\Models\EnrollCompany;
use App\Models\Country;
use App\Models\IndividualUserAccess;
use App\Models\UserAccessMaster;
use App\Models\noOfEmployeeRange;
use App\Models\MatrixAttribute;
use App\Models\CandidateDetail;
use App\Models\CandidateEducationDetail;
use App\Models\CandidateProfessionalDetail;
use App\Models\CandidateOtherDetail;
use App\Models\CandidateDocument;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendBusinessLoginInfo;
use App\Mail\SendEmployerEnrolled;
use Silber\Bouncer\Database\Role;
use Session;
use Auth;
use Bouncer;
use Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require base_path("vendor/PHPMailer/PHPMailer/src/Exception.php");
require base_path("vendor/PHPMailer/PHPMailer/src/PHPMailer.php");
require base_path("vendor/PHPMailer/PHPMailer/src/SMTP.php");
require base_path("vendor/autoload.php");

class EmpilyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */   

    public function __construct()
    {
        //$this->middleware(['auth','is_business']);
    }

    public function matrixAttribute(Request $request)
    {
         
        $this->authorize("access-manage-lead-head");
        if($request->att_id){
            if($request->att_id!=''){
                $attributes=MatrixAttribute::where('id',$request->att_id)->first();
                return $attributes;
            }
        }
        $attributes=MatrixAttribute::paginate(10);
        
        return view('admin.EMPILY.matrix_attributes',compact('attributes'));
    }

    public function matrixAttributeSave(Request $request)
    { 

       /**
        * for Store Agent Detail
        * Input:first_name,last_name,email,mobile_no,gender,role,password.
        * Output: Redirect with success/error
        */
        
        $this->authorize("access-manage-lead-head");       
        $this->validate($request, [
            'att_name' => 'required|max:255|min:3',
            'att_title' => 'required|max:255|min:3',
            'min_point' => 'required|numeric|max:100',          
            'max_point' => 'required|numeric|max:100'          
            
        ]);
        $chk_exist=MatrixAttribute::where('title',$request->att_title)->first();
        if($chk_exist){
            return redirect('/matrix-attributes')->with('error','Attribute already exist!');
        }
        if($request->att_id){
            if($request->att_id!=''){
                $update = MatrixAttribute::where('id',$request->att_id)->update([
                    'name' => $request->att_name,
                    'title' => $request->att_title,
                    'min_point' => $request->min_point,          
                    'max_point' => $request->max_point       
                ]);
                if($update){
                   
                    return $msg="ok";
                }
                else{
                return $msg="Something was wrong!!";
                }
                
            }
        }
        else{
            $new = MatrixAttribute::create([
                'name' => $request->att_name,
                'title' => $request->att_title,
                'min_point' => $request->min_point,          
                'max_point' => $request->max_point       
            ]);
            if($new){
                return redirect('/matrix-attributes')->with('success','Attribute added successfully!');
            }
            else{
                return redirect('/matrix-attributes')->with('error','Something was wrong!');

            }
        }
        
    }

    public function empily(Request $request)
    {
        
        $id=base64_decode($request->id);
        $candidate_id=$id;

        $points_detail=[];

        $personal_info=$pan_details1=$aadhaar_details=$passport_details=$driving_licence_details=$resume=$photo=$signature=
        $education_info=$education_info_document=$professional_info=$professional_info_document=$other_info=$languages=0;
     
        
        $candidate = CandidateDetail::where('id',$candidate_id)->first();

        $education_detail = CandidateEducationDetail::where('candidate_id',$candidate->id)->get();
        $candidate_document_education = CandidateDocument::where('candidate_id',$candidate->id)->where('doc_type',2)->get();

        $professional_detail = CandidateProfessionalDetail::where('candidate_id',$candidate->id)->get();
        $candidate_document_professional = CandidateDocument::where('candidate_id',$candidate->id)->where('doc_type',3)->get();

        $other_detail = CandidateOtherDetail::where('candidate_id',$candidate->id)->where('type','!=','language')->get();
        $languages_detail = CandidateOtherDetail::where('candidate_id',$candidate->id)->where('type','language')->get();

      
        $personal_info=MatrixAttribute::where('title','personal_info')->pluck('max_point')->first();   
                
        if(($candidate->pan_no!=null) && ($candidate->pan_file!=null)){
            $pan_details=MatrixAttribute::where('title','pan_details')->pluck('max_point')->first();            
        }
        
        if($candidate->aadhaar_no!=null && $candidate->aadhaar_file!=null){
            $aadhaar_details=MatrixAttribute::where('title','aadhaar_details')->pluck('max_point')->first();           
        }
        if($candidate->passport_no!=null && $candidate->passport_file!=null){
            $passport_details=MatrixAttribute::where('title','passport_details')->pluck('max_point')->first();            
        }
        if($candidate->dl_no!=null && $candidate->dl_file!=null){
            $driving_licence_details=MatrixAttribute::where('title','driving_licence_details')->pluck('max_point')->first();            
        }
        if($candidate->cv_scan!=null ){
            $resume=MatrixAttribute::where('title','resume')->pluck('max_point')->first();           
        }
        if($candidate->photo!=null ){
            $photo=MatrixAttribute::where('title','photo')->pluck('max_point')->first();            
        }
        if($candidate->signature!=null ){
            $signature=MatrixAttribute::where('title','signature')->pluck('max_point')->first();            
        }
       
       //Educational Info
        if(count($education_detail) > 0){
            $education_info=MatrixAttribute::where('title','education_info')->pluck('max_point')->first();            
            if(count($education_detail) > 1){
                $education_info = ($education_info * count($education_detail))/count($education_detail);                
            }
        }
        

        if(count($candidate_document_education) > 0){
            $education_info_document=MatrixAttribute::where('title','education_info_document')->pluck('max_point')->first();            
            if(count($candidate_document_education) > 1){
                $education_info_document = ($education_info_document * count($candidate_document_education))/count($candidate_document_education);                
            }
        }

       //professional Info
        if(count($professional_detail) > 0){
            $professional_info=MatrixAttribute::where('title','professional_info')->pluck('max_point')->first();            
            if(count($professional_detail) > 1){
                $professional_info = ($professional_info * count($professional_detail))/count($professional_detail);                
            }
        }
        

        if(count($candidate_document_professional) > 0){
            $professional_info_document=MatrixAttribute::where('title','professional_info_document')->pluck('max_point')->first();            
            if(count($candidate_document_professional) > 1){
                $professional_info_document = ($professional_info_document * count($candidate_document_professional))/count($candidate_document_professional);                
            }
        }
                
                //other Info
        if(count($other_detail) > 0){
            $other_info=MatrixAttribute::where('title','other_info')->pluck('max_point')->first();           
            if(count($other_detail) > 1){
                $other_info = ($other_info * count($other_detail))/count($other_detail);               
            }
        }
                        //languages_detail Info
        if(count($languages_detail) > 0){
            $languages=MatrixAttribute::where('title','languages')->pluck('max_point')->first();                        
            if(count($languages_detail) > 1){
                $languages = ($languages * count($languages_detail))/count($languages_detail);                
            }
        }

       
            
        $offerLetters=DB::table('offer_letters as ol')     
            ->leftJoin('candidate_details as cd', 'ol.candidate_id', '=', 'cd.id')        
            // /->leftJoin('request_log as rl', 'rl.offer_letter_id', '=', 'ol.id')     
            // ->select( 'ol.*','rl.id as log_id','rl.offer_letter_id','rl.route','rl.method','rl.response','rl.date_time','cd.id as candidate_id')
            ->select( 'ol.*','cd.id as candidate_id')
            ->where('ol.is_modify',0)
            ->where('cd.email',$candidate->email)
            // ->where('ol.id',55)
            ->get();
             
        $total_offer_letter = count($offerLetters);
        
        
        $email_offer_response_point=0;
        $joining_response_point=0;

        foreach($offerLetters as $offer_letter)
        {
            //email response
            $email_response = DB::table('request_log')->where('method','GET')->where('offer_letter_id',$offer_letter->id)->orderBy('id','ASC')->first();
            $email_offer_response=MatrixAttribute::where('title','email_offer_no_resppnse')->pluck('max_point')->first();
            if($email_response)
            {
                 
                $start  = new Carbon($offer_letter->created_at);
                $email_response_time    = new Carbon($email_response->date_time);            
                $diffInHours_email_response=$start->diffInHours($email_response_time);
                // dd($diffInHours_email_response);
            
                if($diffInHours_email_response < 24 ){               
                             
                    $email_offer_response=MatrixAttribute::where('title','email_offer_response_within_24_hrs')->pluck('max_point')->first();         
                
                }
                elseif(($diffInHours_email_response > 24) && ($diffInHours_email_response < 48)){
                    $email_offer_response=MatrixAttribute::where('title','email_offer_response_within_24-48_hrs')->pluck('max_point')->first();                
                }
                elseif(($diffInHours_email_response > 48) && ($diffInHours_email_response < 72)){
                    $email_offer_response=MatrixAttribute::where('title','email_offer_response_within_48-72_hrs')->pluck('max_point')->first();                
                }
                elseif(($diffInHours_email_response > 72) && ($diffInHours_email_response < 96)){
                    $email_offer_response=MatrixAttribute::where('title','email_offer_response_within_72-96_hrs')->pluck('max_point')->first();                
                }
                elseif(($diffInHours_email_response > 96) && ($diffInHours_email_response < 120)){
                    $email_offer_response=MatrixAttribute::where('title','email_offer_response_within_96-120_hrs')->pluck('max_point')->first();                
                }else{
                    $email_offer_response=0;
                }        

            }
            
            $email_offer_response_point  += $email_offer_response;

              //joining confirm response

            $joining_response_log = DB::table('request_log')->where('method','POST')->where('offer_letter_id',$offer_letter->id)->orderBy('id','ASC')->first();
            $joining_response=MatrixAttribute::where('title','joining_confirmation_no_resppnse')->pluck('max_point')->first();
            // dd($joining_response_log);
            if($joining_response_log)
            {
                 
                $start  = new Carbon($offer_letter->created_at);
                $joining_response_time    = new Carbon($joining_response_log->date_time);            
                $diffInHours_joining_response=$start->diffInHours($joining_response_time);
           
                if($diffInHours_joining_response < 24 )
                {       
                    if($joining_response_log->response==1)  //for accept
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_accept_within_24_hrs')->pluck('max_point')->first();         
                    } 
                    elseif($joining_response_log->response==2) //for reject
                    { 
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reject_within_24_hrs')->pluck('max_point')->first();         
                    }elseif($joining_response_log->response==3) //for reschedule
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reschedule_within_24_hrs')->pluck('max_point')->first(); 
                    }      
                
                }
                elseif(($diffInHours_joining_response > 24) && ($diffInHours_joining_response < 48)){
                       
                    if($joining_response_log->response==1)  //for accept
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_accept_within_24-48_hrs')->pluck('max_point')->first();         
                    } 
                    elseif($joining_response_log->response==2) //for reject
                    { 
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reject_within_24-48_hrs')->pluck('max_point')->first();         
                    }elseif($joining_response_log->response==3) //for reschedule
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reschedule_within_24-48_hrs')->pluck('max_point')->first(); 
                    }                 
                }
                elseif(($diffInHours_joining_response > 48) && ($diffInHours_joining_response < 72)){
                    if($joining_response_log->response==1)  //for accept
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_accept_within_48-72_hrs')->pluck('max_point')->first();         
                    } 
                    elseif($joining_response_log->response==2) //for reject
                    { 
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reject_within_48-72_hrs')->pluck('max_point')->first();         
                    }elseif($joining_response_log->response==3) //for reschedule
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reschedule_within_48-72_hrs')->pluck('max_point')->first(); 
                    }                      
                }
                elseif(($diffInHours_joining_response > 72) && ($diffInHours_joining_response < 96)){
                    if($joining_response_log->response==1)  //for accept
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_accept_within_72-96_hrs')->pluck('max_point')->first();         
                    } 
                    elseif($joining_response_log->response==2) //for reject
                    { 
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reject_within_72-96_hrs')->pluck('max_point')->first();         
                    }elseif($joining_response_log->response==3) //for reschedule
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reschedule_within_72-96_hrs')->pluck('max_point')->first(); 
                    }                     
                }
                elseif(($diffInHours_joining_response > 96) && ($diffInHours_joining_response < 120)){
                    if($joining_response_log->response==1)  //for accept
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_accept_within_96-120_hrs')->pluck('max_point')->first();         
                    } 
                    elseif($joining_response_log->response==2) //for reject
                    { 
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reject_within_96-120_hrs')->pluck('max_point')->first();         
                    }elseif($joining_response_log->response==3) //for reschedule
                    {
                        $joining_response=MatrixAttribute::where('title','joining_confirmation_reschedule_within_96-120_hrs')->pluck('max_point')->first(); 
                    }                      
                }else{
                    $joining_response=0;
                }
            }
            $joining_response_point  += $joining_response;
        }

       
        if($total_offer_letter > 1){
            $email_offer_response_point = $email_offer_response_point/$total_offer_letter;
            $joining_response_point = $joining_response_point/$total_offer_letter;
        }


        $professional_feedback=DB::table('professional_feedback as pf')     
            ->leftJoin('candidate_details as cd', 'pf.candidate_id', '=', 'cd.id')                    
            ->select( 'pf.attribute as attribute','pf.point as point','cd.id as candidate_id')            
            ->where('cd.email',$candidate->email)            
            ->get()
            ->groupBy('attribute');
             //dd($professional_feedback);
            $all_professional_feedback=[];
            if(count($professional_feedback)>0){
                foreach($professional_feedback as $key=>$pf)
                {
                //    dd($key);
                //     dd($pf->sum('point'));
                //     dd(count($pf));            
                $all_professional_feedback[$key]=($pf->sum('point')/count($pf));
                }
            }
        
            $physical_joinings = CandidateDetail::where('email',$candidate->email)->where('physical_joinig_point','!=',null)->get();
            $physical_joining_point=0;
            if(count($physical_joinings) > 0){
                $physical_joining_point = $physical_joinings->sum('physical_joinig_point')/count($physical_joinings);
            }      

        $points_detail = [
            'personal_info'=>$personal_info,
            'pan_details'=>isset($pan_details)?$pan_details:0,
            'aadhaar_details'=>$aadhaar_details,
            'passport_details'=>$passport_details,
            'driving_licence_details'=>$driving_licence_details,
            'resume'=>$resume,
            'photo'=>$photo,
            'signature'=>$signature,
            'education_info'=>$education_info,
            'education_info_document'=>$education_info_document,
            'professional_info'=>$professional_info,
            'professional_info_document'=>$professional_info_document,
            'other_info'=>$other_info,
            'languages'=>$languages,
            'email_offer_response_point'=>$email_offer_response_point,
            'joining_response_point'=>$joining_response_point,
            'physical_joining_point'=>$physical_joining_point,
        ];
        $points_detail= array_merge($points_detail,$all_professional_feedback);

        $total_point=array_sum($points_detail);
        $max_total_point = MatrixAttribute::sum('max_point');
        // dd($max_total_point);
        // dd($personal_info_points,array_sum($personal_info_points));

        return view('admin.EMPILY.empily_score',compact('points_detail','total_point','candidate','max_total_point'));
           
    }

}
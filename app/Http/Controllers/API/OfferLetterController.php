<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DB;
use App\Libs\CommonHelper;
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
use App\Models\BusinessDetail;
use App\Models\JobRole;
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


class OfferLetterController extends Controller
{
    
    public function offerLetterResponse(Request $request)
    { 
        DB::table('request_log')->insert([
            'user_id' => Auth::user()->id,
            'offer_letter_id' => $request->id,
            'host' => $request->server('HTTP_HOST'),
            'route' => $request->server('REQUEST_URI'),
            'method' => $request->server('REQUEST_METHOD'),
            'response' => $request->code,
            'date_time' => date( 'Y/m/d H:i:s', $_SERVER[ 'REQUEST_TIME'])
        ]);

        //dd($request->id);
       
        $offer_letter_id=  $request->id;
        $reason=  $request->reason;
        
        $offer_letter=OfferLetter::where('id','=',$offer_letter_id)->first();
        if($request->code==1)//if yes
        {
            //Approve
            CandidateDetail::where('id','=',$offer_letter->candidate_id)->update(['joining_confirmed'=>1,'status'=>31]);
            OfferLetter::where('id','=',$offer_letter_id)->update(['is_accepted'=>1,'joining_confirmed'=>1]);
            // return $msg=1;
            return response()->json(['status' => true, 'data' =>1, 'msg'=>'Offer Accepted Successfully']);
        }
        else if($request->code==2){
            //Reject
            CandidateDetail::where('id','=',$offer_letter->candidate_id)->update(['joining_confirmed'=>2,'status'=>32]);
            OfferLetter::where('id','=',$offer_letter_id)->update(['is_accepted'=>2,'joining_confirmed'=>2,'rejected_reason'=>$reason]);
            // return $msg=2;
            return response()->json(['status' => true, 'data' =>2, 'msg'=>'Offer Rejected Successfully']);
        }
        else if($request->code==3)
        {
            //Reschedule
            if($request->new_date!=Null && $request->new_time!=Null && $request->res_reason!=Null){
            CandidateDetail::where('id','=',$offer_letter->candidate_id)->update(['status'=>4]);
            OfferLetter::where('id','=',$offer_letter_id)->update(['is_accepted'=>3]);
            $reschedules=DB::table('reschedule')->insert(['candidate_id'=>$offer_letter->candidate_id,'offer_letter_id'=>$offer_letter_id,'old_joining_date'=>$offer_letter->joining_date,'new_joining_date'=>$request->new_date,'old_joining_time'=>$offer_letter->time_of_joining,'new_joining_time'=>$request->new_time,'reason'=>$request->res_reason]);
            // return $msg=3;
            return response()->json(['status' => true, 'data' =>3, 'msg'=>'Reschedule Request Submitted']);
            }
            else{
                return response()->json(['status' => false, 'data' =>0, 'msg'=>'Please Fill All the field']);
                // return $msg="Please Fill All the field";
            }
        }
        else{
            return response()->json(['status' => false, 'data' =>0, 'msg'=>'Something Was Wrong!']);
        }
        
    }

    public function offerLetterList(Request $request)
    {
        $this->authorize("access-manage-candidate");
        // $query = OfferLetter::orderBy('id','DESC');
        $query=DB::table('offer_letters as ofl')
        ->join('candidate_details as cd', 'ofl.candidate_id', '=', 'cd.id')
        
        ->join('job_roles', 'ofl.post', '=', 'job_roles.id')
        ->join('users as hr', 'ofl.hr_id', '=', 'hr.id')
        ->join('users as emp', 'ofl.business_id', '=', 'emp.id')
        ->join('business_details as bd', 'emp.id', '=', 'bd.user_id')
       
        ->select('ofl.*','cd.name as candidateName','cd.candidate_code as candidateCode','cd.photo as candidatePhoto','job_roles.name as jobRoleName','bd.business_name as employerName','hr.first_name as hrName')
       
        ->orderBy('id','DESC');

        if($request->cname) {		
			if($request->cname!=''){
                $field = CandidateDetail::where('name','=',$request->cname)->pluck('id')->toArray();
                $query->whereIn('ofl.candidate_id',$field);
			}
		}
       
        if($request->email) {		
			if($request->email!=''){				
                // $field = CandidateDetail::where('email','=',$request->email)->pluck('id')->first();
                $field = CandidateDetail::where('email','=',strtolower($request->email))->pluck('id')->toArray();                
                $query->whereIn('ofl.candidate_id',$field);
			}
		}

        if($request->phone) {		
			if($request->phone!=''){				 
                $field = CandidateDetail::where('phone','=',$request->phone)->pluck('id')->toArray();
                $query->whereIn('ofl.candidate_id',$field);
			}
		}
         
        if($request->from &&  $request->to){		
			if($request->from!='' && $request->to!=''){
				$query->whereBetween('ofl.joining_date', [$request->from, $request->to]);
			}
		}
        if($request->place) {		
			if($request->place!=''){
				$query->where('ofl.place_of_joining','like','%'.$request->place.'%');
			}
		}
        	
        if($request->status=='all_res'){  
            
            $query->where('ofl.is_accepted','>',0);
        }
        else if($request->status!=''){  
            $query->where('ofl.is_accepted',$request->status);
        }
        // else{
        //     $query->where('is_accepted','>',0);
        // }
		 

        if(Auth::user()->account_type=='hr')
        {
            $offerletters=$query->where('ofl.business_id','=',Auth::user()->parent_id) ->get();
        }
        else if(Auth::user()->account_type!='hr' &&  Auth::user()->account_type!='business')
        {
            $offerletters=$query->get();
        }
        else if(Auth::user()->account_type=='business')
        {            
            $offerletters=$query->where('ofl.business_id','=',Auth::user()->id) ->get();       
        }
        
        return response()->json([
            'status'=>true,           
            'data'=> $offerletters,
            'msg'=>1
        ]);
        // return view('admin.candidate.offerLetterList')->with('offerletters',$offerletters);
    }

    public function offerLetter($id)
    { 
        
        $ol_id=$id;
        //dd($code);
        
        // $offer_letter=OfferLetter::where('id','=',$ol_id)->first();
        $offer_letter=DB::table('offer_letters as ol')
        ->join('candidate_details as cd','ol.candidate_id','=','cd.id')
        ->join('job_roles as jr','ol.post','=','jr.id')
        ->join('users as emp','ol.business_id','=','emp.id')
        ->join('users as hr','ol.hr_id','=','hr.id')
        ->join('profiles as pro','hr.id','=','pro.user_id')
        ->join('business_details as bd','emp.id','=','bd.user_id')
        ->select('ol.*','cd.name as candidateName','jr.name as jobRoleName','hr.first_name as hrFirstName','hr.last_name as hrLastName','hr.email as hrEmail','pro.mobile_no as hrMobile','bd.logo as companyLogo','bd.business_name as companyName')
        ->where('ol.id','=',$ol_id)
        ->first();

        $earning[]=null;
        $deduction[]=null;
        if($offer_letter->salary_breakup!=null){
            $salary_breakup=json_decode($offer_letter->salary_breakup);
            // dd( $salary_breakup);
             $earning=$salary_breakup->earning;
             $deduction=$salary_breakup->deduction;
        }
       
        
        //dd($salary_breakup->earning);
         //return view('admin.candidate.offerLetter',compact('candidate','offer_letter','earning','deduction'));
        //  return view('admin.candidate.offerLetterOne',compact('offer_letter','earning','deduction'));
        $data=[
            'offer_letter'=>$offer_letter,
            'earning'=>$earning,
            'deduction'=>$deduction
            
        ];
        return response()->json([
            'status'=>true,           
            'data'=>$data,
            'msg'=>1
        ]);
    }

    

    public function CreateofferLetterStore(Request $request)
    { 
        $this->authorize("access-manage-candidate");
        $role=Auth::user()->account_type;
        if($role!='hr')
        {
            $flag=1;
        }
        else if($role=='hr')
        {

            $candidate_controller = new CandidateController;             
            $status=$candidate_controller->chkUserAccess(Auth::user()->id,4);

            //$status=CandidateController::chkUserAccess(Auth::user()->id,"create_offer_letter");
            
            if($status!=0){     
                $flag=1;
                // return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions','job_role'));
            }
            else{
                $flag=0;
                // return abort(403,"You do not have permission for this");
            }
        }
        if($flag==1){
        //dd($chk);
        $validator = Validator::make($request->all(),[
            'cname' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'email' => 'required|email|check_mail',
            'gender' => 'required|string',            
            'state' => 'required|numeric',
            'city' => 'required|numeric',
            'country' => 'nullable',
            'phone'=>'required|numeric|digits_between:6,15',                         
            'job_role' => 'required|string',
            'place_of_joining' => 'required|string|min:3',
            'time_of_joining' => 'required|date_format:H:i',
            'joining_date' => 'required|date_format:Y-m-d',        
            'annual_ctc'=>'required|numeric|gt:0',    
            'total_experience'=>'required',
            'dob'=>'required|date_format:Y-m-d|before:today',
            'cv_scan' => 'nullable|mimes:doc,docx,pdf|max:2000'
        ],
        [
            'check_mail'=>'Invalid Email Id',

            'cname.required'=>'Name Required',
            'cname.min'=>'Name must be minimum 3 letter',
            'cname.regex'=>'Name should be in alphabets only.',

            'annual_ctc.required'=>'Annual CTC Required',
            'annual_ctc.gt'=>'Annual CTC must be greater then 0',
            'annual_ctc.numeric'=>'Annual CTC must be in digits only',

            'dob.required'=>'DOB Required',
            'dob.date_format'=>'DOB Date format must be DD-MM-YYYY',
            'dob.before'=>'DOB must be before today',

            'place_of_joining.required'=>'Place of Joining Required',
            'place_of_joining.min'=>'Place of Joining must be minimum 3 letter',

            'time_of_joining.required'=>'Reporting Time is Required',
            'time_of_joining.date_format'=>'Reporting Time format must be H:i',

            'joining_date.required'=>'Joining Date Required',
            'joining_date.date_format'=>'Joining Date format must be DD-MM-YYYY',
            //'joining_date.after'=>'Joining Date  must be after tomorrow',

            'check_mail'=>'Invalid Email Id',  
            'cv_scan.mimes' => 'Only (doc,pdf) type support',
            'cv_scan.max' => 'File size too large to upload',     
                
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        //try {

            //$result = DB::transaction(function () use ($request) {

                 

                $role=Auth::user()->account_type;
                if(($role!='business') && ($role!='hr'))
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

                $helper = new CommonHelper;
                $result1 = $helper->chkEmail(strtolower($request->email),$business_id);

    $pack_details= DB::table('subscriptions')->where([['business_id','=',$business_id],['status','=',1],['remain_qty','>',0],['expire_date','>',Carbon::now()->toDateTimeString()]])->first();

                
                if(!$pack_details){
                    return response()->json(['status' => false, 'msg' => 'No Package. Please Check Package...', 'data'=>0]);

                }

            // $chk=1;
            $chk_offer=offerLetter::where([['candidate_id','=',$result1],['is_accepted','=',0]])->orderby('id','DESC')->first();
            //dd($chk_offer->candidateDetails->email);
            $busines=BusinessDetail::where('user_id',$business_id)->first();
            $hrDetail=User::where('id',$hr_id)->first();
            $jobRole=JobRole::where('id',$request->job_role)->pluck('name')->first();
            $data=[
                'candidateName'=>$request->cname,
                'businessName'=>$busines->business_name,
                'logo'=>($busines->logo ? $busines->logo : null),
                'jobRole'=>$jobRole
            ];
                if($result1==0)
                {
                    //dd(1);
                    //$chk=0;
                    $num_of_row=CandidateDetail::count();
                    $candidate_code='REC'.$hr_id.$business_id.($num_of_row+1);
                    if($request->cv_scan!=Null)
                    {
                        $cv_scan_link = $request->file('cv_scan')
                        ->store('candidate');
                    }
                    else{
                        $cv_scan_link=Null;
                    }
                    
                    $candidate=CandidateDetail::Create(['candidate_code'=>$candidate_code,'name'=>strtolower($request->cname),'email'=>strtolower($request->email),'country'=>$request->country,'phone'=>$request->phone,'gender'=>$request->gender,'state'=>$request->state,'city'=>$request->city,'permanent_address'=>$request->permanent_address,'cv_scan'=>$cv_scan_link,'added_by'=>$added_by,'business_id'=>$business_id,'hr_id'=>$hr_id,'assign_to'=>$hr_id,'is_selected'=>1,'offer_letter_generated'=>1,'status'=>2,'job_role'=>$request->job_role,'total_experience'=>$request->total_experience,'dob'=>$request->dob]);

                    $salary_breakup=$request->salary;

                    $offer_letter=OfferLetter::Create(['candidate_id'=>$candidate->id,'hr_id'=>$candidate->hr_id,'business_id'=>$candidate->business_id,'post'=>$request->job_role,'joining_date'=>$request->joining_date,'place_of_joining'=>$request->place_of_joining,'time_of_joining'=>$request->time_of_joining,'annual_ctc'=>$request->annual_ctc,'salary_breakup'=>$salary_breakup]);
                    $updatePack=DB::table('subscriptions')->where([['business_id','=',$business_id],['id','=',$pack_details->id]])->update(['used_qty'=>$pack_details->used_qty + 1, 'remain_qty'=>$pack_details->remain_qty - 1]);

                    
                            // try{
                            //     $data=[
                            //         'candidateName'=>$request->cname,
                            //         'businessName'=>$busines->business_name,
                            //         'jobRole'=>$request->job_role
                            //     ];
                            // Mail::to($request->email)->queue(new OfferLetterGenerated($data));
                        
                            // }
                            // catch(\Exception $ex){
                            // $stack_trace = $ex->getTraceAsString();
                            // $message = $ex->getMessage().$stack_trace;
                            // Log::error($message);
                            // }

                            try{
                                
                             Mail::to(strtolower($request->email))->cc($hrDetail->email)->queue(new OfferLetterGenerated($data));
                            
                        
                            }
                            catch(\Exception $ex){
                            $stack_trace = $ex->getTraceAsString();
                            $message = $ex->getMessage().$stack_trace;
                            Log::error($message);
                            return response()->json([
                                'status'=>false,           
                                'data'=>0,
                                'msg'=>$message
                            ]);
                            }
                        return response()->json(['status' => true, 'msg' => 'Offer Letter Generated Successfully', 'data'=>1]);
                        // return redirect()->route('offerLetterList')->with('success','Offer Letter Generated Successfully');
                        
                }
                else if($chk_offer==Null)
                    {
                        //dd(2);
                        $salary_breakup=$request->salary;

                        $offer_letter=OfferLetter::insert(['candidate_id'=>$result1,'hr_id'=>$hr_id,'business_id'=>$business_id,'post'=>$request->job_role,'joining_date'=>$request->joining_date,'place_of_joining'=>$request->place_of_joining,'time_of_joining'=>$request->time_of_joining,'annual_ctc'=>$request->annual_ctc,'salary_breakup'=>$salary_breakup]);

                        CandidateDetail::where('id',$result1)->update(['is_selected'=>1,'offer_letter_generated'=>1,'status'=>2]);
                        
                        $updatePack=DB::table('subscriptions')->where([['business_id','=',$business_id],['id','=',$pack_details->id]])->update(['used_qty'=>$pack_details->used_qty + 1, 'remain_qty'=>$pack_details->remain_qty - 1]);

                            // try{
                            //     $data=[
                            //         'candidateName'=>$request->cname,
                            //         'businessName'=>$busines->business_name,
                            //         'jobRole'=>$request->job_role
                            //     ];
                            // Mail::to($request->email)->queue(new OfferLetterGenerated($data));
                        
                            // }
                            // catch(\Exception $ex){
                            // $stack_trace = $ex->getTraceAsString();
                            // $message = $ex->getMessage().$stack_trace;
                            // Log::error($message);
                            // }
                            try{

                                 
                             Mail::to(strtolower($request->email))->cc($hrDetail->email)->queue(new OfferLetterGenerated($data));
                            //  return response()->json([
                            //     'status'=>true,           
                            //     'data'=>1,
                            //     'msg'=>'Mail Send Successfully'
                            // ]);
                        
                            }
                            catch(\Exception $ex){
                            $stack_trace = $ex->getTraceAsString();
                            $message = $ex->getMessage().$stack_trace;
                            Log::error($message);
                            // return response()->json([
                            //     'status'=>false,           
                            //     'data'=>0,
                            //     'msg'=>$message
                            // ]);
                            }

                        return response()->json(['status' => true, 'msg' => 'Offer Letter Generated Successfully', 'data'=>1]);
                        // return redirect()->route('offerLetterList')->with('success','Offer Letter Generated Successfully');
                    }
                else{
                    return response()->json(['status' => false, 'msg' => 'Offer Letter Frequently Generated!', 'data'=>0]);
                    //candidateDetails
                     //dd(3);
                       // return 1;
                        //return redirect()->route('CreateofferLetter');//->with('error','Offer Letter Frequently Generated!');
                        //      return redirect()->back()->with('error','Offer Letter Frequently Generated!');
                        //    // return redirect('offer_letter_list?email={{$chk_offer->candidateDetails->email}}')->with('error','Offer Letter Frequently Generated!');
                        //    return redirect()->route('offerLetterList')->with('error','Offer Letter Frequently Generated!');
                
                }
                             
                     
                 
           // });
           
        // } catch (\Exception $e) {dd($e->getMessage());
        //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        // }
        }
        else{
            return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
             
        }
    }


    public function candidateOfferLetter(Request $request,$id)
    { 
        DB::table('request_log')->insert([
            'user_id' => Auth::user()->id,
            'offer_letter_id' => base64_decode($id),
            'host' => $request->server('HTTP_HOST'),
            'route' => $request->server('REQUEST_URI'),
            'method' => $request->server('REQUEST_METHOD'),
            'date_time' => date( 'Y/m/d H:i:s', $_SERVER[ 'REQUEST_TIME'])
        ]);
       

       

        $ol_id=$id;       
        
        // $candidates=CandidateDetail::where([['email','=',$candidate_email],['user_id','=',0]])->orderBy('id','DESC')->pluck('id')->toArray();
               
        // $offer_letter=OfferLetter::whereIn('candidate_id',$candidates)->get();
         

        //dd($code);
        $reasons=DB::table('reschedule_reasons')->get();

       $offer_letter=OfferLetter::where('id','=',$ol_id)->first();
        $salary_breakup=json_decode($offer_letter->salary_breakup);
        $earning=$salary_breakup->earning;
        $deduction=$salary_breakup->deduction;
         
        //dd($salary_breakup->earning);
        //return view('admin.candidate.offerLetter',compact('candidate','offer_letter','earning','deduction'));
        //  return view('candidate.candidateOfferLetter',compact('offer_letter','earning','deduction','reasons'));
        $data=[
            'offer_letter'=>$offer_letter,
            'earning'=>$earning,
            'deduction'=>$deduction,
            'reasons'=>$reasons
        ];
        

        return response()->json(['status' => true, 'msg' => 'Success', 'data'=>$data]);
    }

    public function resendMail(Request $request)
    {
        $of_let_id=$request->id;

        $query=DB::table('offer_letters as ofl')
        ->join('candidate_details as cd', 'ofl.candidate_id', '=', 'cd.id')
        
        ->join('job_roles', 'ofl.post', '=', 'job_roles.id')
        ->join('users as hr', 'ofl.hr_id', '=', 'hr.id')
        ->join('users as emp', 'ofl.business_id', '=', 'emp.id')
        ->join('business_details as bd', 'emp.id', '=', 'bd.user_id')
       
        ->select('ofl.*','cd.name as candidateName','cd.email as candidateEmail','cd.candidate_code as candidateCode','cd.photo as candidatePhoto','job_roles.name as jobRoleName','bd.business_name as employerName','hr.first_name as hrName','bd.logo as busLogo')
       ->where('ofl.id','=',$of_let_id)
        ->first();
        //dd($query->candidateName);
        
            try{
               

                $data=[
                    'candidateName'=>$query->candidateName,
                    'businessName'=>$query->employerName,
                    'logo'=>($query->busLogo ? $query->busLogo : null),
                    'jobRole'=>$query->jobRoleName
                ];
             Mail::to($query->candidateEmail)->queue(new OfferLetterGenerated($data));
             return response()->json([
                'status'=>true,           
                'data'=>1,
                'msg'=>'Mail Send Successfully'
            ]);
        
            }
            catch(\Exception $ex){
            $stack_trace = $ex->getTraceAsString();
            $message = $ex->getMessage().$stack_trace;
            Log::error($message);
            return response()->json([
                'status'=>false,           
                'data'=>0,
                'msg'=>$message
            ]);
            }

    }

    public function duplicateOfferLetter(Request $request)
    { 
        try{
            //  $this->authorize("access-manage-candidate");

        $ofl=offerLetter::where('id',$request->id)->first();
        if($ofl->is_modify==0)
        {
        
            
            $validator = Validator::make($request->all(),[
                'cname' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
                'email' => 'required|email|check_mail',
                'gender' => 'required|string',            
                'state' => 'required|numeric',
                'city' => 'required|numeric',
                'country' => 'nullable',
                'phone'=>'required|numeric|digits_between:6,15',                         
                'job_role' => 'required|string',
                'place_of_joining' => 'required|string|min:3',
                'time_of_joining' => 'required|date_format:H:i',
                'joining_date' => 'required|date_format:Y-m-d',        
                'annual_ctc'=>'required|numeric|gt:0',    
                'total_experience'=>'required',
                'dob'=>'required|date_format:Y-m-d|before:today',
                'cv_scan' => 'nullable|mimes:doc,docx,pdf|max:2000'
            ],
            [
                'check_mail'=>'Invalid Email Id',
                'cname.required'=>'Name Required',
                'cname.min'=>'Name must be minimum 3 letter',
                'cname.regex'=>'Name should be in alphabets only.',
                'annual_ctc.required'=>'Annual CTC Required',
                'annual_ctc.gt'=>'Annual CTC must be greater then 0',
                'annual_ctc.numeric'=>'Annual CTC must be in digits only',
                'dob.required'=>'DOB Required',
                'dob.date_format'=>'DOB Date format must be DD-MM-YYYY',
                'dob.before'=>'DOB must be before today',
                'place_of_joining.required'=>'Place of Joining Required',
                'place_of_joining.min'=>'Place of Joining must be minimum 3 letter',
                'time_of_joining.required'=>'Reporting Time is Required',
                'time_of_joining.date_format'=>'Reporting Time format must be H:i',
                'joining_date.required'=>'Joining Date Required',
                'joining_date.date_format'=>'Joining Date format must be DD-MM-YYYY',
                //'joining_date.after'=>'Joining Date  must be after tomorrow',
                'check_mail'=>'Invalid Email Id',  
                'cv_scan.mimes' => 'Only (doc,pdf) type support',
                'cv_scan.max' => 'File size too large to upload'                    
            ]);
            if ($validator->fails()) 
            {
                return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }

            
            if($request->cv_scan!=Null)
            {
                $cv_scan_link = $request->file('cv_scan')
                ->store('candidate');
            }
            else{
                $cv_scan_link=Null;
            }
            
            $candidate=CandidateDetail::where('id',$ofl->candidate_id)->update(['name'=>strtolower($request->cname),'email'=>strtolower($request->email),'country'=>$request->country,'phone'=>$request->phone,'gender'=>$request->gender,'state'=>$request->state,'city'=>$request->city,'permanent_address'=>$request->permanent_address,'cv_scan'=>$cv_scan_link,'job_role'=>$request->job_role,'total_experience'=>$request->total_experience,'dob'=>$request->dob]);

            $salary_breakup=$request->salary;

            $dup_offer_letter=OfferLetter::insert(['candidate_id'=>$ofl->candidate_id,'hr_id'=>$ofl->hr_id,'business_id'=>$ofl->business_id,'post'=>$request->job_role,'joining_date'=>$request->joining_date,'place_of_joining'=>$request->place_of_joining,'time_of_joining'=>$request->time_of_joining,'annual_ctc'=>$request->annual_ctc,'salary_breakup'=>$salary_breakup,'old_offer_id'=>$request->id]);

            $old_offer_letter=OfferLetter::where('id',$request->id)->update(['is_modify'=>1]);

            return response()->json([
                'status'=>true,           
                'data'=>1,
                'msg'=>'Offer Letter Re-Generated Successfully!'
            ]);
         
        }
        else{
            return response()->json([
                'status'=>true,           
                'data'=>0,
                'msg'=>'Offer Letter Already Modified!'
            ]);
           
        }
    }
    catch(Exception $e){
        return response()->json([
            'status'=>false,           
            'data'=>0,
            'msg'=>$e
        ]);

    }
        
           
          
    }
}

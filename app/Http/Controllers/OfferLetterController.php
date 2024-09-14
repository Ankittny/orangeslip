<?php

namespace App\Http\Controllers;

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
use App\Models\JobRole;
use App\Models\BusinessDetail;
use DB;
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
use App\Exports\UsersExport;


class OfferLetterController extends Controller
{
    
    // public function GenerateOfferLetterView(Request $request)
    // { 
    //     /**
    //      * 
    //      */
    //     $this->authorize("access-manage-candidate");
    //     $id=base64_decode($request->id);
    //     $candidate=CandidateDetail::where('id','=',$id)->first();   
    //     $earnings=DB::table('salary_components')->where('status','=',1)->where('category','=','Earning')->get();
    //     $deductions=DB::table('salary_components')->where('status','=',1)->where('category','=','Deduction')->get();
       
    //     $job_role=JobRole::where('status','=',1)->orderBy('name','ASC')->get();
    //     return View::make('admin.candidate.generateofferletter',compact('candidate','earnings','deductions','job_role'));
    // }

    // public function GenerateOfferLetterStore(Request $request)
    // { 
        
    //     $this->authorize("access-manage-candidate");
    //     $id=base64_decode($request->id);
    //     $candidate=CandidateDetail::where('id','=',$id)->first();
       
    //     $this->validate($request, [
    //         'post' => 'required',
    //         'place_of_joining' => 'required|string|min:5',
    //         'time_of_joining' => 'required|date_format:H:i',
    //         'joining_date' => 'required|date_format:Y-m-d|after:today',        
    //         'annual_ctc'=>'required|numeric|gt:0'        
                   
    //         ],
    //         [
                 

    //             'annual_ctc.required'=>'Annual CTC Required',
    //             'annual_ctc.gt'=>'Annual CTC must be greater then 0',
    //             'annual_ctc.numeric'=>'Annual CTC must be digits only',

    //             'place_of_joining.required'=>'Place of Joining Required',
    //             'place_of_joining.min'=>'Place of Joining must be minimum 3 letter',
    
    //             'time_of_joining.required'=>'Time of Joining Required',
    //             'time_of_joining.date_format'=>'Time of Joining time format must be H:i',
    
    //             'joining_date.required'=>'Joining Date Required',
    //             'joining_date.date_format'=>'Joining Date format must be DD-MM-YYYY',
    //             'joining_date.after'=>'Joining Date  must be after tomorrow',
    
                    
    
                
    //         ]);
    //                 // $hr_id=Auth::user()->id;
    //                 // $business_id=Auth::user()->parent_id;                  
           
    //         $salary_breakup=json_encode($request->salary);
    //         $data=CandidateDetail::where('id','=',$id)->update(['offer_letter_generated'=>1,'status'=>2]);        								
 

    public function offerLetterResponse(Request $request)
    { 
        
       // 'user_id' => ($this->getToken($request))->tokenable_id,
       DB::table('request_log')->insert([
        'user_id' => Auth::user()->id,
        'offer_letter_id' => $request->lid,
        'host' => $request->server('HTTP_HOST'),
        'route' => $request->server('REQUEST_URI'),
        'method' => $request->server('REQUEST_METHOD'),
        'response' => $request->code,
        'date_time' => date( 'Y/m/d H:i:s', $_SERVER[ 'REQUEST_TIME'])
        ]);
        /**
         * Offer Letter Response
         * Input:candidate_id,id,reason.
         * Output:msg
         */
       // $candidate_id=  Session::get('candidate_id');
        $offer_letter_id=  $request->lid;
        $reason=  $request->reason;
        
        $offer_letter=OfferLetter::where('id','=',$offer_letter_id)->first();
        //dd($offer_letter);
        if($request->code==1)//if yes
        {
            CandidateDetail::where('id','=',$offer_letter->candidate_id)->update(['joining_confirmed'=>1,'status'=>31]);
            OfferLetter::where('id','=',$offer_letter_id)->update(['is_accepted'=>1,'joining_confirmed'=>1]);
            return $msg=1;
        }
        else if($request->code==2){
            CandidateDetail::where('id','=',$offer_letter->candidate_id)->update(['joining_confirmed'=>2,'status'=>32]);
            OfferLetter::where('id','=',$offer_letter_id)->update(['is_accepted'=>2,'joining_confirmed'=>2,'rejected_reason'=>$reason]);
            return $msg=2;
        }
        else if($request->code==3){
            if($request->new_date!=Null && $request->new_time!=Null && $request->reason!=Null)
            {
                if(Carbon::now()->toDateTimeString() > $request->new_date){
                    return $msg="New Date Must be after today";
                 }
            CandidateDetail::where('id','=',$offer_letter->candidate_id)->update(['status'=>4]);
             
            OfferLetter::where('id','=',$offer_letter_id)->update(['is_accepted'=>3]);
             
            $reschedules=DB::table('reschedule')->insert(['candidate_id'=>$offer_letter->candidate_id,'offer_letter_id'=>$offer_letter_id,'old_joining_date'=>$offer_letter->joining_date,'new_joining_date'=>$request->new_date,'old_joining_time'=>$offer_letter->time_of_joining,'new_joining_time'=>$request->new_time,'reason'=>$request->reason]);
             
            return $msg=3;
            }
            else{
                return $msg="Please Fill All the field";
            }
        }
        else{
            return $msg=0;
        }
        
    }

    public function offerLetterList(Request $request)
    {
       
        /**
         * Offer Letter List Page View
         * Input:cname,email,phone,from,to,status - for search
         * output:offerletters
         */
        $this->authorize("access-manage-candidate");
        $searchData=$request->all();
        $query = OfferLetter::orderBy('id','DESC');
        if($request->cname) {		
			if($request->cname!=''){
                $field = CandidateDetail::where('name','LIKE','%'.$request->cname.'%')->pluck('id')->toArray();
                $query->whereIn('candidate_id',$field);
			}
		}

        if($request->email) {		
			if($request->email!=''){				
                // $field = CandidateDetail::where('email','=',$request->email)->pluck('id')->first();
                $field = CandidateDetail::where('email','LIKE','%'.strtolower($request->email).'%')->pluck('id')->toArray();
                ;
                $query->whereIn('candidate_id',$field);
			}
		}

        if($request->phone) {		
			if($request->phone!=''){				 
                $field = CandidateDetail::where('phone','LIKE','%'.$request->phone.'%')->pluck('id')->toArray();
                $query->whereIn('candidate_id',$field);
			}
		}
         
        if($request->from &&  $request->to){		
			if($request->from!='' && $request->to!=''){
				$query->whereBetween('joining_date', [$request->from, $request->to]);
			}
		}
        if($request->place) {		
			if($request->place!=''){
				$query->where('place_of_joining','LIKE','%'.$request->place.'%');
			}
		}
         	
			// if($request->status!='')
            // {
            //     $query->where('is_accepted',$request->status);
			// }
        if($request->status=='all_res'){  
        
            $query->where('is_accepted','>',0);
        }
        else if($request->status!=''){  
            $query->where('is_accepted',$request->status);
        }

        if(Auth::user()->account_type=='hr')
        {
            if($request->export)
            {
                $expData=$query->where('business_id','=',Auth::user()->parent_id)->get();
                return Excel::download(new UsersExport('offerletter',$expData), 'OfferLetterList.xlsx');             
                
            }

            $offerletters=$query->where('business_id','=',Auth::user()->parent_id) ->paginate(5);
            $offerletters->appends(request()->query());
        }
        else if(Auth::user()->account_type!='hr' &&  Auth::user()->account_type!='business')
        {
            if($request->export)
            {
                $expData=$query->get();
                return Excel::download(new UsersExport('offerletter',$expData), 'OfferLetterList.xlsx');             
                
            }

            $offerletters=$query->paginate(5);
            $offerletters->appends(request()->query());
        }
        else if(Auth::user()->account_type=='business')
        {          
            if($request->export)
            {
                $expData=$query->where('business_id','=',Auth::user()->id)->get();
                return Excel::download(new UsersExport('offerletter',$expData), 'OfferLetterList.xlsx');             
                
            }  
            $offerletters=$query->where('business_id','=',Auth::user()->id) ->paginate(5);   
            $offerletters->appends(request()->query());    
        }
        return view('admin.candidate.offerLetterList',compact('offerletters','searchData'));
    }

    public function offerLetter($id)
    { 
        /**
         * offer letter page view
         * input:id(offerletter)
         * output:offer_letter,earning,deduction.
         */
        $ol_id=base64_decode($id);       
        
       $offer_letter=OfferLetter::where('id','=',$ol_id)->first();
        // $salary_breakup=json_decode($offer_letter->salary_breakup);
        // $earning=$salary_breakup->earning;
        // $deduction=$salary_breakup->deduction;
        //dd($salary_breakup->earning);
        //return view('admin.candidate.offerLetter',compact('candidate','offer_letter','earning','deduction'));
        //  return view('admin.candidate.offerLetterOne',compact('offer_letter','earning','deduction'));
         return view('admin.candidate.candidateOfferLetterOne',compact('offer_letter'));
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
       
        
        $ol_id=base64_decode($id);       
        
        
        // $candidates=CandidateDetail::where([['email','=',$candidate_email],['user_id','=',0]])->orderBy('id','DESC')->pluck('id')->toArray();
        
        // $offer_letter=OfferLetter::whereIn('candidate_id',$candidates)->get();
        
        
        //dd($code);
        $reasons=DB::table('reschedule_reasons')->get();
        
        $offer_letter=OfferLetter::where('id','=',$ol_id)->first();
         
        // $salary_breakup=json_decode($offer_letter->salary_breakup);
        // $earning=$salary_breakup->earning;
        // $deduction=$salary_breakup->deduction;
         
        //dd($salary_breakup->earning);
        //return view('admin.candidate.offerLetter',compact('candidate','offer_letter','earning','deduction'));
        //  return view('candidate.candidateOfferLetter',compact('offer_letter','earning','deduction','reasons'));  25042023
        // dd($offer_letter,$reasons); 
        return view("admin.candidate.candidateOfferLetterOne",compact('offer_letter','reasons'));
    }


    public function CreateofferLetter(Request $request)
    { 
        /**
         * Create Offer Letter Page View
         * input:base64_encode(id)
         * output:states,earnings,deductions,all_business,job_role,candidate
         * 
         */
        $id=base64_decode($request->id);
        
        $role=Auth::user()->account_type;
        $states=State::where('status','=','Active')->get();
        $earnings=DB::table('salary_components')->where('category','=','Earning')->get();
        $deductions=DB::table('salary_components')->where('category','=','Deduction')->get();
        $job_role=JobRole::where('status','=',1)->orderBy('name','ASC')->get();
        $country=Country::get();      
        $allTemp=null;      
        
        
        if(($role!='hr') && ($role!='business'))
        {
            $all_business=User::where('account_type','=','business')->get();
            if(isset($id))
            {
                $candidate=CandidateDetail::where('id',$id)->first();
                return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions','all_business','job_role','candidate','country'));
            }
    
            return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions','all_business','job_role','country'));
        }
        else if($role=='business')
        {
            $pack_details= DB::table('subscriptions')->where([['business_id','=',Auth::user()->id],['status','=',1],['remain_qty','>',0],['expire_date','>',Carbon::now()->toDateTimeString()]])->first();

                if(!$pack_details){
                    return redirect()->back()->with('error','No Package. Please Check Package...');
                }
                $allTemp=DB::table('offer_letter_templates')->where('business_id',Auth::user()->id)->get();
            if(isset($id))
            {
                $all_hr=User::where([['account_type','=','hr'],['parent_id','=',Auth::user()->id]])->get();
                $candidate=CandidateDetail::where('id',$id)->first();
                return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions','all_hr','job_role','candidate','country','allTemp'));
            }
          
            $all_hr=User::where([['account_type','=','hr'],['parent_id','=',Auth::user()->id]])->get();
            return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions','all_hr','job_role','country','allTemp'));
        }
        else if($role=='hr')
        {
            $pack_details= DB::table('subscriptions')->where([['business_id','=',Auth::user()->parent_id],['status','=',1],['remain_qty','>',0],['expire_date','>',Carbon::now()->toDateTimeString()]])->first();
            
                if(!$pack_details){
                    return redirect()->back()->with('error','No Package. Please Check Package...');
                }

            $candidate_controller = new CandidateController;             
            $status=$candidate_controller->chkUserAccess(Auth::user()->id,4);

            //$status=CandidateController::chkUserAccess(Auth::user()->id,"create_offer_letter");
            
            if($status!=0){     
                $allTemp=DB::table('offer_letter_templates')->where('business_id',Auth::user()->parent_id)->get();
                if(isset($id))
            {
                $candidate=CandidateDetail::where('id',$id)->first();
                return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions','job_role','candidate','country','allTemp'));
            }
                return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions','job_role','country','allTemp'));
            }
            else{
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        }




        // if($role=='superadmin')
        // {
        //     $all_business=User::where('account_type','=','business')->get();
        //     return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions','all_business'));
        // }
        // else if($role=='business')
        // {
        //     $all_hr=User::where([['account_type','=','hr'],['parent_id','=',Auth::user()->id]])->get();
        //     return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions','all_hr'));
        // }
        // else if($role=='hr')
        // {
        // return view('admin.candidate.create_offer_letter',compact('states','earnings','deductions'));
        // }

    }

    public function CreateofferLetterStore(Request $request)
    { 
        /**
         * Store Offer Letter 
         * input:cname,email,gender,state,city,phone,job_role,place_of_joining,time_of_joining,joining_date,annual_ctc,total_experience,dob,cv_scan,
         * salary,permanent_address,business_id,hr_id.
         * output: Redirect with success/error.
         */
        
        
        $this->validate($request,[
            'cname' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'email' => 'required|email|check_mail',
            'gender' => 'required|string',            
            'state' => 'required|numeric',
            'city' => 'required|numeric',
            'country' => 'required',
            'phone'=>'required|numeric|digits_between:6,15',                        
            'job_role' => 'required|string',
            'place_of_joining' => 'required|string|min:3',
            'time_of_joining' => 'required|date_format:H:i',
            'joining_date' => 'required|date_format:Y-m-d',        
            'annual_ctc'=>'required|numeric|gt:0',    
            'total_experience'=>'required',
            'dob'=>'required|date_format:Y-m-d|before:today',
            'cv_scan' => 'mimes:doc,docx,pdf|max:2048'
        ],
        [
            'check_mail'=>'Invalid Email Id',

            'cname.required'=>'Name Required',
            'cname.min'=>'Name must be minimum 3 letters',
            'cname.regex'=>'Name must be in alphabets only.',

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
           // 'joining_date.after'=>'Joining Date  must be after tomorrow',

            'check_mail'=>'Invalid Email Id',  
            'cv_scan.mimes' => 'Only (doc,pdf) type support',
            'cv_scan.max' => 'File size too large to upload',     
                
        ]);
            
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
                $result1 = $helper->chkEmail($request->email,$business_id);


                $pack_details= DB::table('subscriptions')->where([['business_id','=',$business_id],['status','=',1],['remain_qty','>',0],['expire_date','>',Carbon::now()->toDateTimeString()]])->first();

                
                if(!$pack_details){
                    // return response()->json(['status' => false, 'msg' => 'No Package. Please Check Package...', 'data'=>0]);
                    return redirect()->back()->with('error','No Package. Please Check Package...');

                }


                $jobRole=JobRole::where('id',$request->job_role)->pluck('name')->first();
            // $chk=1;
            $chk_offer=offerLetter::where([['candidate_id','=',$result1],['is_accepted','=',0]])->orderby('id','DESC')->first();
            //dd($chk_offer->candidateDetails->email);

            $busines=BusinessDetail::where('user_id',$business_id)->first();
            $hrDetail=User::where('id',$hr_id)->first();
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
                    
                    $candidate=CandidateDetail::Create(['candidate_code'=>$candidate_code,'name'=>$request->cname,'email'=>strtolower($request->email),'country'=>$request->country,'phone'=>$request->phone,'gender'=>$request->gender,'state'=>$request->state,'city'=>$request->city,'permanent_address'=>$request->permanent_address,'cv_scan'=>$cv_scan_link,'added_by'=>$added_by,'business_id'=>$business_id,'hr_id'=>$hr_id,'assign_to'=>$hr_id,'is_selected'=>1,'offer_letter_generated'=>1,'status'=>2,'job_role'=>$request->job_role,'total_experience'=>$request->total_experience,'dob'=>$request->dob]);

                    $salary_breakup=json_encode($request->salary);

                    $offer_letter=OfferLetter::Create(['candidate_id'=>$candidate->id,'hr_id'=>$candidate->hr_id,'business_id'=>$candidate->business_id,'post'=>$request->job_role,'joining_date'=>$request->joining_date,'place_of_joining'=>$request->place_of_joining,'time_of_joining'=>$request->time_of_joining,'annual_ctc'=>$request->annual_ctc,'salary_breakup'=>$salary_breakup,'offer_letter'=>$request->temp_id]);
                    $updatePack=DB::table('subscriptions')->where([['business_id','=',$business_id],['id','=',$pack_details->id]])->update(['used_qty'=>$pack_details->used_qty + 1, 'remain_qty'=>$pack_details->remain_qty - 1]);
                    


                    try{
                      
                        $data=[
                            'candidateName'=>$request->cname,
                            'businessName'=>$busines->business_name,
                            'logo'=>($busines->logo ? $busines->logo : null),
                            'jobRole'=>$jobRole
                        ];
                        Mail::to($request->email)->cc($hrDetail->email)->queue(new OfferLetterGenerated($data));
                        
                        }
                        catch(\Exception $ex){
                        $stack_trace = $ex->getTraceAsString();
                        $message = $ex->getMessage().$stack_trace;
                        Log::error($message);
                        }
                        return redirect()->route('offerLetterList')->with('success','Offer Letter Generated Successfully');
                        
                }
                else if($chk_offer==Null)
                    {
                        //dd(2);
                        $salary_breakup=json_encode($request->salary);

                        $offer_letter=OfferLetter::Create(['candidate_id'=>$result1,'hr_id'=>$hr_id,'business_id'=>$business_id,'post'=>$request->job_role,'joining_date'=>$request->joining_date,'place_of_joining'=>$request->place_of_joining,'time_of_joining'=>$request->time_of_joining,'annual_ctc'=>$request->annual_ctc,'salary_breakup'=>$salary_breakup,'offer_letter'=>$request->temp_id]);
                        
                        $updatePack=DB::table('subscriptions')->where([['business_id','=',$business_id],['id','=',$pack_details->id]])->update(['used_qty'=>$pack_details->used_qty + 1, 'remain_qty'=>$pack_details->remain_qty - 1]);

                        CandidateDetail::where('id',$result1)->update(['is_selected'=>1,'offer_letter_generated'=>1,'status'=>2]);



                        try{
                            $data=[
                                'candidateName'=>$request->cname,
                                'businessName'=>$busines->business_name,
                                'logo'=>($busines->logo ? $busines->logo : null),
                                'jobRole'=>$jobRole
                            ];
                        Mail::to($request->email)->cc($hrDetail->email)->queue(new OfferLetterGenerated($data));
                       
                        }
                        catch(\Exception $ex){
                        $stack_trace = $ex->getTraceAsString();
                        $message = $ex->getMessage().$stack_trace;
                        Log::error($message);
                        }

                        return redirect()->route('offerLetterList')->with('success','Offer Letter Generated Successfully');
                    }
                else{
                    //candidateDetails
                     //dd(3);
                       // return 1;
                        //return redirect()->route('CreateofferLetter');//->with('error','Offer Letter Frequently Generated!');
                            // return redirect()->back()->with('error','Offer Letter Frequently Generated!');
                           // return redirect('offer_letter_list?email={{$chk_offer->candidateDetails->email}}')->with('error','Offer Letter Frequently Generated!');
                           return redirect()->route('offerLetterList')->with('error','Offer Letter Frequently Generated!');
                
                }
                             
                     
                 
           // });
           
        // } catch (\Exception $e) {dd($e->getMessage());
        //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        // }

    }
    public function resendMail(Request $request)
    {
        $of_let_id=base64_decode($request->id);

        $query=DB::table('offer_letters as ofl')
        ->join('candidate_details as cd', 'ofl.candidate_id', '=', 'cd.id')
        
        ->join('job_roles', 'ofl.post', '=', 'job_roles.id')
        ->join('users as hr', 'ofl.hr_id', '=', 'hr.id')
        ->join('users as emp', 'ofl.business_id', '=', 'emp.id')
        ->join('business_details as bd', 'emp.id', '=', 'bd.user_id')
       
        ->select('ofl.*','cd.name as candidateName','cd.email as candidateEmail','cd.candidate_code as candidateCode','cd.photo as candidatePhoto','job_roles.name as jobRoleName','bd.business_name as employerName','bd.logo as logo','hr.first_name as hrName')
       ->where('ofl.id','=',$of_let_id)
        ->first();
        //dd($query->candidateName);
        
            try{
                $data=[
                    'candidateName'=>$query->candidateName,
                    'businessName'=>$query->employerName,
                    'jobRole'=>$query->jobRoleName,
                    'logo'=>($query->logo ? $query->logo : null)
                ];

                 



               // dd($query->candidateEmail);
             Mail::to($query->candidateEmail)->queue(new OfferLetterGenerated($data));
            //  return response()->json([
            //     'status'=>true,           
            //     'data'=>1,
            //     'msg'=>'Mail Send Successfully'
            // ]);
            return redirect()->route('offerLetterList')->with('success','Mail Send Successfully');
        
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
            return redirect()->route('offerLetterList')->with('success','Something was wrong');
            }

    }


    public function editOfferLetter(Request $request)
    { 
        // dd($request->id);
      //  $this->authorize("access-manage-candidate");
        $off_letter_id=base64_decode($request->id);
        $offer_letter=offerLetter::where('id',$off_letter_id)->first();
        
        $role=Auth::user()->account_type;
        $states=State::where('status','=','Active')->get();
        $earnings=DB::table('salary_components')->where('category','=','Earning')->get();
        $deductions=DB::table('salary_components')->where('category','=','Deduction')->get();
        $job_role=JobRole::where('status','=',1)->orderBy('name','ASC')->get();
        $country=Country::get();
        

       if(($role=='superadmin') || (($role=='business') && ($offer_letter->business_id==Auth::user()->id)) || (($role=='hr') && ($offer_letter->hr_id==Auth::user()->id)))
       {

       

            if($offer_letter->is_modify==0 && $offer_letter->is_accepted==0)
            {
            
                // $salary_breakup=json_decode($offer_letter->salary_breakup);
                // $old_earning=$salary_breakup->earning;
                // $old_deduction=$salary_breakup->deduction;

                $old_earning=null;
                $old_deduction=null;

                
                
                $candidate=CandidateDetail::where('id',$offer_letter->candidate_id)->first();

                $city=City::where('state_id','=', $candidate->state)->get();
                
                return view('admin.candidate.editOfferLetter',compact('country','states','earnings','deductions','job_role','candidate','offer_letter','old_earning','old_deduction','city'));
            
            
            }
            else{
                return redirect()->route('offerLetterList')->with('error','Offer Letter Already Modified or Responsed');
            
            
            }

        }
        else{
            return abort(300,'You have no permission for access this');
        }
    }



   
    public function duplicateOfferLetter(Request $request)
    { 
        // dd(1);
       
      //  $this->authorize("access-manage-candidate");
        $off_letter_id=base64_decode($request->id);
        $ofl=offerLetter::where('id',$off_letter_id)->first();
        if($ofl->is_modify==0)
        {
        
            
            $this->validate($request,[
                'cname' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
                'email' => 'required|email|check_mail',
                'gender' => 'required|string',            
                'state' => 'required|numeric',
                'city' => 'required|numeric',
                'country' => 'nullable',
                'phone'=>'required|numeric|digits_between:6,15',                         
                'job_role' => 'required|string',
                'place_of_joining' => 'required|string|min:3',
                'time_of_joining' => 'required|date_format:H:i:s',
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
               // 'joining_date.after'=>'Joining Date  must be after tomorrow',
                'check_mail'=>'Invalid Email Id',  
                'cv_scan.mimes' => 'Only (doc,docx,pdf) type support',
                'cv_scan.max' => 'File size too large to upload'                    
            ]);
            

            
            if($request->cv_scan!=Null)
            {
                $cv_scan_link = $request->file('cv_scan')
                ->store('candidate');
            }
            else{
                $cv_scan_link=Null;
            }

            $jobRole=JobRole::where('id',$request->job_role)->pluck('name')->first();
            $busines=BusinessDetail::where('user_id',$ofl->business_id)->first();
            $hrDetail=User::where('id',$ofl->hr_id)->first(); 

             $candidate=CandidateDetail::where('id',$ofl->candidate_id)->update(['name'=>strtolower($request->cname),'email'=>strtolower($request->email),'country'=>$request->country,'phone'=>$request->phone,'gender'=>$request->gender,'state'=>$request->state,'city'=>$request->city,'permanent_address'=>$request->permanent_address,'cv_scan'=>$cv_scan_link,'job_role'=>$request->job_role,'total_experience'=>$request->total_experience,'dob'=>$request->dob]);

            $salary_breakup=json_encode($request->salary);

              $dup_offer_letter=OfferLetter::insert(['candidate_id'=>$ofl->candidate_id,'hr_id'=>$ofl->hr_id,'business_id'=>$ofl->business_id,'post'=>$request->job_role,'joining_date'=>$request->joining_date,'place_of_joining'=>$request->place_of_joining,'time_of_joining'=>$request->time_of_joining,'annual_ctc'=>$request->annual_ctc,'salary_breakup'=>$salary_breakup,'old_offer_id'=>$off_letter_id,'offer_letter'=>$ofl->offer_letter]);

             $old_offer_letter=OfferLetter::where('id',$off_letter_id)->update(['is_modify'=>1]);
            // return response()->json([
            //     'status'=>true,           
            //     'data'=>1,
            //     'msg'=>'Offer Letter Re-Generated Successfully!'
            // ]);
            try{
                      
                $data=[
                    'candidateName'=>$request->cname,
                    'businessName'=>$busines->business_name,
                    'logo'=>($busines->logo ? $busines->logo : null),
                    'jobRole'=>$jobRole
                ];
                Mail::to($request->email)->cc($hrDetail->email)->queue(new OfferLetterGenerated($data));
                
                }
                catch(\Exception $ex){
                $stack_trace = $ex->getTraceAsString();
                $message = $ex->getMessage().$stack_trace;
                Log::error($message);
                }
            return redirect()->route('offerLetterList')->with('success','Offer Letter Re-Generated Successfully!');
         
        }
        else{
            return redirect()->route('offerLetterList')->with('success','Offer Letter Already Modified!');
            // return response()->json([
            //     'status'=>true,           
            //     'data'=>0,
            //     'msg'=>'Offer Letter Already Modified!'
            // ]);
           
        }
        
         
        
           
          
    }

    
    

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
Use App\Models\CandidateDetail;
Use App\Models\BusinessDetail;
Use App\Models\User;
Use App\Models\Verification;
use App\Models\VerificationStaff;
use App\Models\Deposit;
use App\Models\OfferLetter;
use App\Models\EnrollCompany;
use App\Models\CandidateEducationDetail;
use App\Models\CandidateProfessionalDetail;
use App\Models\CandidateOtherDetail;
use App\Models\CheckedOfferLetter;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\Support;



require base_path("vendor/PHPMailer/PHPMailer/src/Exception.php");
require base_path("vendor/PHPMailer/PHPMailer/src/PHPMailer.php");
require base_path("vendor/PHPMailer/PHPMailer/src/SMTP.php");

// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';

 
use DB;
Use Auth;


class HomeController extends Controller
{





    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $j=DB::select("SELECT `value`,count('id') as can_count FROM `candidate_other_details` where `type`='industry' group by `value` order by `value`");
    
        return view('home');
    }

    public function dashboard() 
    {
    
        /* For Display Dashboard Data */
       //dd(Auth::user());
        $role=Auth::user()->account_type;
        $user_id=Auth::user()->id;
        $logUser=Auth::user();
        $balance=User::where('id','=',$user_id)->pluck('balance')->first();
       if($role=='superadmin')
       {
         
        $no_of_business=User::where('account_type','=','business')->count();
        $no_of_hr=User::where('account_type','=','hr')->count();
        $no_of_candidate=CandidateDetail::count();
        $no_of_lead_head=User::where('account_type','=','lead head')->count();
        $no_of_lead_staff=User::where('account_type','=','lead staff')->count();
        $no_of_verification_head=User::where('account_type','=','verification head')->count();
        $no_of_verification_staff=User::where('account_type','=','verification staff')->count();
        $no_of_lead=EnrollCompany::count();

        $pending_lead=EnrollCompany::where('status',1)->count();
        $verified_lead=EnrollCompany::where('status',2)->count();
        $created_lead=EnrollCompany::where('status',3)->count();
        $rejected_lead=EnrollCompany::where('status',4)->count();
        $assigned_lead=EnrollCompany::where('status',5)->count();



        $no_of_added_cv=CandidateDetail::where('user_id','=',0)->count();
        $no_of_selected_candidate=CandidateDetail::where('status','=',1)->count();
        $no_of_offerletter_generated=OfferLetter::count();       
        $no_of_pending_offer=OfferLetter::where('is_accepted','=',0)->count();
        $no_of_confirmed_joining=OfferLetter::where('joining_confirmed','=',1)->count();
        $no_of_reschedule_joining=OfferLetter::where('is_accepted','=',3)->count();
        $no_of_rejected_offer_letter=OfferLetter::where('is_accepted','=',2)->count();
        $no_of_kyc_pending=Verification::where('status','=',1)->count();
        $no_of_kyc_assigned=Verification::where('status','=',2)->count();
        $no_of_kyc_completed=Verification::where('status','=',3)->count();
        $no_of_deposit_request=Deposit::where('status','=',1)->count();
        $no_of_deposit_approved=Deposit::where('status','=',2)->count();
        $no_of_deposit_rejected=Deposit::where('status','=',3)->count();

        return view('admin.dashboard',compact('no_of_deposit_request','no_of_deposit_approved','no_of_deposit_rejected','balance','no_of_business','no_of_hr','no_of_verification_head','no_of_verification_staff','no_of_added_cv','no_of_selected_candidate','no_of_offerletter_generated','no_of_pending_offer','no_of_confirmed_joining','no_of_reschedule_joining','no_of_rejected_offer_letter','no_of_kyc_pending','no_of_kyc_assigned','no_of_kyc_completed','no_of_candidate','no_of_lead_head','no_of_lead_staff','no_of_lead','pending_lead','verified_lead','created_lead','rejected_lead','assigned_lead'));
       }
       else if($role=='lead head')
       {
        $allStaff=User::where([['account_type','=','lead staff'],['parent_id',Auth::user()->id]])->pluck('id')->toArray();
        //  dd($allStaff);
        $no_of_business=User::where([['account_type','=','business'],['parent_id',Auth::user()->id]])->count(); 
        $no_of_lead_staff=User::where([['account_type','=','lead staff'],['parent_id','=',Auth::user()->id]])->count();
        
        $pending_lead=EnrollCompany::where('status',1)->count();
        $no_of_lead=EnrollCompany::whereIn('lead_staff_id',$allStaff)->count();
        // $verified_lead=EnrollCompany::where('status',2)->whereIn('verifier_id',$allStaff)->count();
        $verified_lead=EnrollCompany::where(function($q) use ($allStaff) {      
            $q->where('status','=',2);
            $q->whereIn('verifier_id',$allStaff);
            $q->orWhere('verifier_id',Auth::user()->id);
            })->count();
        // dd($verified_lead);
        // $created_lead=EnrollCompany::where('status',3)->whereIn('creator_id',$allStaff)->count();
        $created_lead=EnrollCompany::where(function($q) use ($allStaff) {
        $q->where('status','=',3);
        $q->whereIn('verifier_id',$allStaff);
        $q->orWhere('verifier_id',Auth::user()->id);
        })->count();
        $rejected_lead=EnrollCompany::where('status',4)->count();
        $assigned_lead=EnrollCompany::where('status',5)->count();
       
        return view('admin.dashboard',compact('no_of_business','no_of_lead_staff','no_of_lead','pending_lead','verified_lead','created_lead','rejected_lead','assigned_lead'));
       }
       else if($role=='lead staff')
       {
        
        //$no_of_lead=EnrollCompany::count();
        $assigned_lead=EnrollCompany::where([['status','=',5],['lead_staff_id','=',Auth::user()->id]])->count();
        $verified_lead=EnrollCompany::where([['status','=',2],['verifier_id','=',Auth::user()->id]])->count();
        $created_lead=EnrollCompany::where([['status','=',3],['creator_id','=',Auth::user()->id]])->count();
       
       
        return view('admin.dashboard',compact('assigned_lead','verified_lead','created_lead'));
       }
       else if($role=='hr')
       {
                
        $no_of_offerletter_generated=OfferLetter::where('business_id','=',$logUser->parent_id)->count();        
        $no_of_confirmed_joining=OfferLetter::where([['business_id','=',$logUser->parent_id],['is_accepted','=',1]])->count();
        $no_of_reschedule_joining=OfferLetter::where([['business_id','=',$logUser->parent_id],['is_accepted','=',3]])->count();
        $no_of_rejected_offer_letter=OfferLetter::where([['business_id','=',$logUser->parent_id],['is_accepted','=',2]])->count();        
        $no_of_response=OfferLetter::where([['hr_id','=',Auth::user()->id],['is_accepted','>',0]])->count(); //No Of Response       
        $no_of_kyc_request=Verification::where('hr_id',Auth::user()->id)->count();         
        $no_of_kyc_completed=Verification::where('hr_id',Auth::user()->id)->where('status','=',3)->orWhere('status','=',4)->count();
        $no_of_kyc_pending=Verification::where('hr_id',Auth::user()->id)->where('status','=',1)->count();

        return view('admin.dashboard',compact('balance','no_of_response','no_of_offerletter_generated','no_of_confirmed_joining','no_of_reschedule_joining','no_of_rejected_offer_letter','no_of_kyc_request','no_of_kyc_completed','no_of_kyc_pending'));

        // return view('admin.dashboard',compact('balance','no_of_added_cv','no_of_selected_candidate','no_of_offerletter_generated','no_of_pending_offer','no_of_confirmed_joining','no_of_reschedule_joining','no_of_rejected_offer_letter'));
       }
       else if($role=='business')
       {
               

        $no_of_hr=User::where([['account_type','=','hr'],['parent_id','=',$user_id]])->count();
        $no_of_response=OfferLetter::where([['business_id','=',$user_id],['is_accepted','>',0]])->count(); //No Of Response
        $no_of_offerletter_generated=OfferLetter::where('business_id','=',$user_id)->count();   //No Of Offer Letter
        $no_of_confirmed_joining=OfferLetter::where([['business_id','=',$user_id],['is_accepted','=',1]])->count();//No Of Offer Letter Accepted
        $no_of_reschedule_joining=OfferLetter::where([['business_id','=',$user_id],['is_accepted','=',3]])->count();//No Of Offer Letter Reschedule 
        $no_of_rejected_offer_letter=OfferLetter::where([['business_id','=',$user_id],['is_accepted','=',2]])->count();//No Of Offer Letter Reject
        $no_of_kyc_request=Verification::where('business_id',Auth::user()->id)->count();         
        $no_of_kyc_completed=Verification::where('business_id',Auth::user()->id)->where('status','=',3)->count();
        $no_of_kyc_pending=Verification::where('business_id',Auth::user()->id)->where('status','=',1)->count(); 

        $packDetails= DB::table('subscriptions')
        ->join('packages as pack','subscriptions.pack_id','=','pack.id')
        ->where('business_id','=',Auth::user()->id)->where('subscriptions.status',1)->first();

        return view('admin.dashboard',compact('balance','no_of_hr','no_of_response','no_of_offerletter_generated','no_of_confirmed_joining','no_of_reschedule_joining','no_of_rejected_offer_letter','no_of_kyc_request','no_of_kyc_completed','no_of_kyc_pending','packDetails'));
       }
       else if($role=='verification head')
       {
        $department=VerificationStaff::where('user_id','=',Auth::user()->id)->pluck('department')->first();
        // $no_of_verification_staff=VerificationStaff::where('department','LIKE','%'.$department.'%')->count();
        $no_of_verification_staff=User::where([['account_type','=','verification staff'],['parent_id','=',Auth::user()->id]])->count();
        $no_of_kyc_pending=Verification::where([['verification_type','LIKE','%'.$department.'%'],['status','=',1]])->count();
        $no_of_kyc_assigned=Verification::where([['verification_type','LIKE','%'.$department.'%'],['status','=',2]])->count();
        $no_of_kyc_completed=Verification::where([['verification_type','LIKE','%'.$department.'%'],['status','=',3]])->count();
        
        return view('admin.dashboard',compact('no_of_verification_staff','no_of_kyc_pending','no_of_kyc_assigned','no_of_kyc_completed'));
       }
       else if($role=='verification staff')
       {
        
        $data=Verification::where('staff_id','=',$user_id)->get();        
        $no_of_kyc_assigned=$data->where('status','=',2)->count();       
        $no_of_kyc_completed=$data->where('status','=',3)->count();      
        
        return view('admin.dashboard',compact('no_of_kyc_assigned','no_of_kyc_completed'));
       }
       else if($role=='candidate')
       {
        $candidate=CandidateDetail::where('user_id','=',$user_id)->first();//13
        $candidate_id=$candidate->id;
        // $no_of_offerletter_generated=OfferLetter::where('candidate_id','=',$candidate_id)->count();
        //$allofferletter=CheckedOfferLetter::where('user_id','=',Auth::user()->id)->count();
        
        $gender=$candidate->gender;//3
        $city=$candidate->city;//3
        $job_role=$candidate->job_role;//3
        $total_experience=$candidate->total_experience;//3
        $photo=$candidate->photo;//10
        $cv_scan=$candidate->cv_scan;//10


         
        $education_details=CandidateEducationDetail::where('candidate_id','=',$candidate_id)->get(); //25

        $profession_details=CandidateProfessionalDetail::where('candidate_id','=',$candidate_id)->get(); //25

        $skills=CandidateOtherDetail::where([['candidate_id','=',$candidate_id],['type','=','skill']])->get(); //2
        $languages=CandidateOtherDetail::where([['candidate_id','=',$candidate_id],['type','=','language']])->get(); //2
        $hobbies=CandidateOtherDetail::where([['candidate_id','=',$candidate_id],['type','=','hobby']])->get(); //1


        $lvl1=10;//13 for candidate Details
        if($gender!=Null){$lvl2=3;}else{$lvl2=0;}//3
        if($city!=Null){$lvl3=3;}else{$lvl3=0;}//3
        if($job_role!=Null){$lvl4=3;}else{$lvl4=0;}//3
        if($total_experience!=Null){$lvl5=3;}else{$lvl5=0;}//3
        if($photo!=Null){$lvl6=10;}else{$lvl6=0;}//10
        if($cv_scan!=Null){$lvl7=10;}else{$lvl7=0;}//10
        if($education_details->isNotEmpty()){$lvl8=25;}else{$lvl8=0;}//25
        if($profession_details->isNotEmpty()){$lvl9=25;}else{$lvl9=0;}//25
        if($skills->isNotEmpty()){$lvl10=2;}else{$lvl10=0;}//2
        if($languages->isNotEmpty()){$lvl11=2;}else{$lvl11=0;}//2
        if($hobbies->isNotEmpty()){$lvl12=1;}else{$lvl12=0;}//1
        
        $progress_lvl= $lvl1+ $lvl2+ $lvl3+ $lvl4+ $lvl5+ $lvl6+ $lvl7+ $lvl8+ $lvl9+ $lvl10+ $lvl11+ $lvl12;
       // dd($progress_lvl);

        //for uncheck offer Letter
        $email=Auth::user()->email;             
        $candidates=CandidateDetail::where([['email','=',$email],['user_id','=',0]])->orderBy('id','DESC')->pluck('id')->toArray();        
        // $offerletters=OfferLetter::whereIn('candidate_id',$candidates)->count();
        $offerletters=OfferLetter::whereIn('candidate_id',$candidates)->where('is_checked','=',0)->count();

        return view('admin.dashboard',compact('progress_lvl','offerletters'));
       }
        
    }

    public function changePassword()
    {
        return view('change_password');
    }

    public function updatePassword(Request $request)
    {
        /*
         For Update Password of User from users Table
         Input: old_password, password.
        */
        $this->validate($request, [
            'old_password'=>'required|string|min:8',
            'password'=>'required|string|min:8|confirmed'
        ],
        [
            'old_password.required'=>'Old Password Required',
            'old_password.min'=>'Old Password must be minimum 8 character',

            'password.required'=>'New Password Required',
            'password.min'=>'New Password must be minimum 8 character',
            'password.confirmed'=>'Password and Confirm Password mismatch'
        ]);
         
        $uPass=Auth::user()->password;
        $oldPass=$request->old_password;
        $newPass=Hash::make($request->password);
       
        if(Hash::check($oldPass, $uPass)) {
            
            $upPass=User::where('id','=',Auth::user()->id)->update(['password'=>$newPass,'change_password'=>1]);
            if($upPass)
            {
                return $msg=1;
            }
            else{
                return $msg="Something Was Wrong!!";
            }
        }
        else{
           
            return $msg="Old password did not match!!";
        }
        
        //dd($request->all());
    }

    public function contactUs(Request $request)
    {
        /* For store contact page contact us form data 
        input:contact_us_name,contact_us_email,contact_us_message.
        */
        $this->validate($request, [
            'contact_us_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'contact_us_email' => 'required|email|check_mail',
            'contact_us_message'=>'required|string|min:10|max:200'           
        ],
        [
            'contact_us_name.required'=>'Name Required',
            'contact_us_name.regex'=>'Invalid Format! Only Alphabets Allowed',
            'contact_us_name.min'=>'Name should be minimum 3 character',

            'contact_us_email.required'=>'Email Required',
            'contact_us_email.check_mail'=>'Invalid Email',

            'contact_us_message.required'=>'Message Required',
            'contact_us_message.min'=>'Message should be minimum 10 character'
          
        ]);               
        
            
            $data=DB::table('contact_us')->insert(['name'=>$request->contact_us_name, 'email'=>$request->contact_us_email, 'message'=>$request->contact_us_message]);
            if($data)
            {
                return redirect('contact')->with('success','Thank You! we will contact you soon.');
            }
            else{
                return redirect('contact')->with('error','Something Was Wrong!!');
            }                          
    }
    

    public function termsConditions()
    {
        return view('page.terms-conditions');
    }
    public function cookiepolicy()
    {
        return view('page.cookie-policy');
    }
    public function privacyPolicy()
    {
        return view('page.privacy-policy');
    }
    public function faq()
    {
        return view('page.faq');
    }

    public function about()
    {
        return view('page.about');
    }

    public function pricing()
    {
        $allPack=DB::table('packages')->orderBy('id','ASC')->get();
        $allKyc=DB::table('verification_types')->orderBy('id','ASC')->get();
        return view('page.pricing',compact('allPack','allKyc'));
    }

    public function contact()
    {
        
        return view('page.contact');
    }

    public function onbording()
    {
        return view('page.onbording');
    }
    public function KYCverification()
    {
        return view('page.KYC-verification');
    }
    public function resumebuilder()
    {
        return view('page.resume-builder');
    }
    public function EMPILYscore()
    {
        return view('page.EMPILY-score');
    }
    public function blockchaindevelopment()
    {
        return view('page.blockchain-development');
    }
    public function blog()
    {
       
        return view('page.blog');
    }

    public function blogDetails()
    {
        return view('page.blog_details');
    }


    public function checkPackageSubscription()
    {
        /**
         * for check packege expire or qty. is 0
         * then update status 2 for inactive.
         * set cron job for this checking
         * 08062023
         */
        // $request=null;
        $query= DB::table('subscriptions')
            // ->orderBy('id','DESC')
            ->where('status','=',1)
            ->where(function($q)  {  
            $q->where('remain_qty','<=',0);            
            $q->orWhere('expire_date','<=',Carbon::now()->toDateTimeString());      
            })
            ->update(['status'=>2]);
            // dd($query);
            // if($query){
                 
            //     return response()->json(['status'=>true,'msg'=>$query. 'success']);
            // }
            // else{
            //     return response()->json(['status'=>false,'msg'=>'no data']);
            // }
        // $packs=$query->get();
        // dd($packs);
    }
    public function support(Request $request){

         
        $validator = Validator::make($request->all(),[
            'company_name' => 'required|string|min:3',
            'full_name' => 'required|string|min:3',
            'position' => 'required|string|min:3',
            'email' => 'required|email',
            'mobile' => 'required|numeric|digits_between:10,12',
            'address' => 'required|string|max:100',
        ]);
        // $this->validate($request, [
        //     'email' => 'required|email'
        // ]);
        if($validator->fails()){
        
        return response()->json(['status'=>false,'errors'=> $validator->errors()]);
        }
        $sendData=[
            'company_name' => $request->company_name,
            'full_name' => $request->full_name,
            'position' => $request->position,
            'email' => strtolower($request->email),
            'mobile' =>$request->mobile,                                
            'address' =>$request->address,                                
        ];
        try{
            Mail::to('info@orangeslip.com')->queue(new Support($sendData));
           // return $msg=1;
            }
            catch(\Exception $ex){
            $stack_trace = $ex->getTraceAsString();
            $message = $ex->getMessage().$stack_trace;
           // dd($message);
            Log::error($message);
            //return $msg=2;
            }
        return response()->json(['status'=>true,'msg'=>'ok']);
        
    }




   
    

}

<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        return view('home');
    }

    public function Userdashboard()
    {
        // $ip= $_SERVER['SERVER_ADDR'];
        // $mytime = Carbon::now();
        //  $mytime->toDateTimeString();
        // DB::table('login_info')->insert(['user_id'=>Auth::user()->id,'ip_address'=>$ip]);
        // User::where('id',Auth::user()->id)->update(['last_login'=>$mytime]);
        
       // dd(session()->all());
        $role=Auth::user()->account_type;
        //dd($role);
        $logUser=Auth::user();
        $user_id=Auth::user()->id;
        $balance=User::where('id','=',$user_id)->pluck('balance')->first();


         
          $no_of_hr=0;
          $no_of_response=0;
          $no_of_offerletter_generated=0;
          $no_of_confirmed_joining=0;
          $no_of_reschedule_joining=0;
          $no_of_rejected_offer_letter=0;
          $no_of_kyc_request=0;
          $no_of_kyc_completed=0;
          $no_of_kyc_pending=0;
           
            
 



       if($role=='superadmin')
       {
        
        $no_of_business=User::where('account_type','=','business')->count();
        $no_of_hr=User::where('account_type','=','hr')->count();
        $no_of_verification_head=User::where('account_type','=','verification head')->count();
        $no_of_verification_staff=User::where('account_type','=','verification staff')->count();
        $no_of_added_cv=CandidateDetail::count();
        $no_of_selected_candidate=CandidateDetail::where('status','=',1)->count();
        $no_of_offerletter_generated=OfferLetter::count();       
        $no_of_pending_offer=OfferLetter::where('is_accepted','=',0)->count();
        $no_of_confirmed_joining=OfferLetter::where('joining_confirmed','=',1)->count();
        $no_of_reschedule_joining=OfferLetter::where('is_accepted','=',3)->count();
        $no_of_rejected_offer_letter=OfferLetter::where('is_accepted','=',2)->count();
        $no_of_new_request=Verification::where('status','=',NULL)->count();
        $no_of_assigned_request=Verification::where('status','=',1)->count();
        $no_of_completed_verification=Verification::where('status','=',2)->count();
        $no_of_deposit_request=Deposit::where('status','=',1)->count();
        $no_of_deposit_approved=Deposit::where('status','=',2)->count();
        $no_of_deposit_rejected=Deposit::where('status','=',3)->count();

        $dash_data=[                                
            'no_of_deposit_request'=>$no_of_deposit_request,
            'no_of_deposit_approved'=>$no_of_deposit_approved,
            'no_of_deposit_rejected'=>$no_of_deposit_rejected,
            'balance'=>$balance,
            'no_of_business'=>$no_of_business,
            'no_of_hr'=>$no_of_hr,
            'no_of_verification_head'=>$no_of_verification_head,
            'no_of_verification_staff'=>$no_of_verification_staff,
            'no_of_added_cv'=>$no_of_added_cv,
            'no_of_selected_candidate'=>$no_of_selected_candidate,
            'no_of_offerletter_generated'=>$no_of_offerletter_generated,
            'no_of_pending_offer'=>$no_of_pending_offer,
            'no_of_confirmed_joining'=>$no_of_confirmed_joining,
            'no_of_reschedule_joining'=>$no_of_reschedule_joining,
            'no_of_rejected_offer_letter'=>$no_of_rejected_offer_letter,
            'no_of_new_request'=>$no_of_new_request,
            'no_of_assigned_request'=>$no_of_assigned_request,
            'no_of_completed_verification'=>$no_of_completed_verification
        ];
        return response()->json([
            'status'=>true,           
            'data'=> $dash_data,
            'msg'=>1
        ]);
        // return view('admin.dashboard',compact('no_of_deposit_request','no_of_deposit_approved','no_of_deposit_rejected','balance','no_of_business','no_of_hr','no_of_verification_head','no_of_verification_staff','no_of_added_cv','no_of_selected_candidate','no_of_offerletter_generated','no_of_pending_offer','no_of_confirmed_joining','no_of_reschedule_joining','no_of_rejected_offer_letter','no_of_new_request','no_of_assigned_request','no_of_completed_verification'));
       }
       else if($role=='lead head')
       {
        
        $no_of_business=User::where('account_type','=','business')->count(); 
        $no_of_lead_staff=User::where([['account_type','=','lead staff'],['parent_id','=',Auth::user()->id]])->count();
        $no_of_lead=EnrollCompany::count();
        $pending_lead=EnrollCompany::where('status',1)->count();
        $verified_lead=EnrollCompany::where('status',2)->count();
        $created_lead=EnrollCompany::where('status',3)->count();
        $rejected_lead=EnrollCompany::where('status',4)->count();
        $assigned_lead=EnrollCompany::where('status',5)->count();
       
        $dash_data=[                                
            
            'no_of_business'=>$no_of_business,
            'no_of_lead_staff'=>$no_of_lead_staff,
            'no_of_lead'=>$no_of_lead,
            'pending_lead'=>$pending_lead,
            'verified_lead'=>$verified_lead,
            'created_lead'=>$created_lead,
            'rejected_lead'=>$rejected_lead,
            'assigned_lead'=>$assigned_lead
           
        ];
        return response()->json([
            'status'=>true,           
            'data'=> $dash_data,
            'msg'=>1
        ]);
       }
       else if($role=='hr')
       {
        
        
        $no_of_offerletter_generated=0;         
        $no_of_confirmed_joining=0;
        $no_of_reschedule_joining=0;
        $no_of_rejected_offer_letter=0;        
        $no_of_response=0;        
        $no_of_kyc_request=0;         
        $no_of_kyc_completed=0;
        $no_of_kyc_pending=0;        


        // $no_of_added_cv=CandidateDetail::where('business_id','=',$logUser->parent_id)->count();
        // $no_of_selected_candidate=CandidateDetail::where([['business_id','=',$logUser->parent_id],['is_selected','=',1]])->count();
        $no_of_offerletter_generated=OfferLetter::where('business_id','=',$logUser->parent_id)->count();
        // $no_of_pending_offer=OfferLetter::where([['business_id','=',$logUser->parent_id],['is_accepted','=',0]])->count();
        $no_of_confirmed_joining=OfferLetter::where([['business_id','=',$logUser->parent_id],['is_accepted','=',1]])->count();
        $no_of_reschedule_joining=OfferLetter::where([['business_id','=',$logUser->parent_id],['is_accepted','=',3]])->count();
        $no_of_rejected_offer_letter=OfferLetter::where([['business_id','=',$logUser->parent_id],['is_accepted','=',2]])->count();        
        $no_of_response=OfferLetter::where([['hr_id','=',Auth::user()->id],['is_accepted','>',0]])->count(); //No Of Response       
        $no_of_kyc_request=Verification::where('hr_id',Auth::user()->id)->count();         
        $no_of_kyc_completed=Verification::where('hr_id',Auth::user()->id)->where('status','=',3)->orWhere('status','=',4)->count();
        $no_of_kyc_pending=Verification::where('hr_id',Auth::user()->id)->where('status','=',1)->count();

         


        
        $dash_data=[                                
                        
            'balance'=>$balance,                      
            // 'no_of_added_cv'=>$no_of_added_cv,
            // 'no_of_selected_candidate'=>$no_of_selected_candidate,
            'no_of_offerletter_generated'=>$no_of_offerletter_generated,
            // 'no_of_pending_offer'=>$no_of_pending_offer,
            'no_of_confirmed_joining'=>$no_of_confirmed_joining,
            'no_of_reschedule_joining'=>$no_of_reschedule_joining,
            'no_of_rejected_offer_letter'=>$no_of_rejected_offer_letter,
            'no_of_response'=>$no_of_response,
            'no_of_kyc_request'=>$no_of_kyc_request,
            'no_of_kyc_completed'=>$no_of_kyc_completed,
            'no_of_kyc_pending'=>$no_of_kyc_pending,

          
        ];
        return response()->json([
            'status'=>true,           
            'data'=> $dash_data,
            'msg'=>1
        ]);
        // return view('admin.dashboard',compact('balance','no_of_added_cv','no_of_selected_candidate','no_of_offerletter_generated','no_of_pending_offer','no_of_confirmed_joining','no_of_reschedule_joining','no_of_rejected_offer_letter'));
       }
       else if($role=='business')
       {
        //Offer Letter Response-> is_accepted ? 1-Accept, 2- Reject, 3- Reschedule Request

        $no_of_hr=User::where([['account_type','=','hr'],['parent_id','=',$user_id]])->count();
        //$no_of_added_cv=CandidateDetail::where('business_id','=',$user_id)->count();
       // $no_of_selected_candidate=CandidateDetail::where([['business_id','=',$user_id],['is_selected','=',1]])->count();

        $no_of_response=OfferLetter::where([['business_id','=',$user_id],['is_accepted','>',0]])->count(); //No Of Response
        $no_of_offerletter_generated=OfferLetter::where('business_id','=',$user_id)->count();   //No Of Offer Letter
       // $no_of_pending_offer=OfferLetter::where([['business_id','=',$user_id],['is_accepted','=',0]])->count();
        $no_of_confirmed_joining=OfferLetter::where([['business_id','=',$user_id],['is_accepted','=',1]])->count();//No Of Offer Letter Accepted
        $no_of_reschedule_joining=OfferLetter::where([['business_id','=',$user_id],['is_accepted','=',3]])->count();//No Of Offer Letter Reschedule 
        $no_of_rejected_offer_letter=OfferLetter::where([['business_id','=',$user_id],['is_accepted','=',2]])->count();//No Of Offer Letter Reject
        
        $no_of_kyc_request=Verification::where('business_id',Auth::user()->id)->count();
         
        $no_of_kyc_completed=Verification::where('business_id',Auth::user()->id)->where('status','=',3)->count();
        $no_of_kyc_pending=Verification::where('business_id',Auth::user()->id)->where('status','=',1)->count();

         


        
        $packDetails= DB::table('subscriptions')
        ->join('packages as pack','subscriptions.pack_id','=','pack.id')
        ->where('business_id','=',Auth::user()->id)->first();
        
        //dd($packDetails->pack_id);
        //   $current_plan_name=$packDetails->pack_name;
        //   $current_plan_id=$packDetails->pack_id;
       
        //     $current_plan_remaining_date=Carbon::now() - $packDetails->expire_date;
        //     $current_plan_remaining_offers=$packDetails->remain_qty;

        // current plan id

        // current plan remaining date

        // current plan remaining offers


        $dash_data=[  
            'balance'=>$balance,
            'no_of_hr'=>$no_of_hr,
            'no_of_response'=>$no_of_response,
        //'no_of_added_cv'=>$no_of_added_cv,
            //'no_of_selected_candidate'=>$no_of_selected_candidate,
            'no_of_offerletter_generated'=>$no_of_offerletter_generated,
        //'no_of_pending_offer'=>$no_of_pending_offer,
            'no_of_confirmed_joining'=>$no_of_confirmed_joining,
            'no_of_reschedule_joining'=>$no_of_reschedule_joining,
            'no_of_rejected_offer_letter'=>$no_of_rejected_offer_letter,
            'no_of_kyc_request'=>$no_of_kyc_request,
            'no_of_kyc_completed'=>$no_of_kyc_completed,
            'no_of_kyc_pending'=>$no_of_kyc_pending,
            'packDetails'=>$packDetails,
            // 'current_plan_name'=>$current_plan_name,
            // 'current_plan_id'=>$current_plan_id,
            // 'current_plan_remaining_date'=>$current_plan_remaining_date,
            // 'current_plan_remaining_offers'=>$current_plan_remaining_offers,

            
        ];
        return response()->json([
            'status'=>true,           
            'data'=> $dash_data,
            'msg'=>1
        ]);
        // return view('admin.dashboard',compact('balance','no_of_hr','no_of_added_cv','no_of_selected_candidate','no_of_offerletter_generated','no_of_pending_offer','no_of_confirmed_joining','no_of_reschedule_joining','no_of_rejected_offer_letter' ));
       }
       else if($role=='verification head')
       {
        $department=VerificationStaff::where('user_id','=',Auth::user()->id)->pluck('department')->first();
        $no_of_verification_staff=VerificationStaff::where('department','LIKE','%'.$department.'%')->count();
        $no_of_new_request=Verification::where([['verification_type','LIKE','%'.$department.'%'],['status','=',NULL]])->count();
        $no_of_assigned_request=Verification::where([['verification_type','LIKE','%'.$department.'%'],['status','=',1]])->count();
        $no_of_completed_verification=Verification::where([['verification_type','LIKE','%'.$department.'%'],['status','=',2]])->count();
        $dash_data=[                                
            'balance'=>$balance,
            'no_of_verification_staff'=>$no_of_verification_staff,
            'no_of_new_request'=>$no_of_new_request,
            'no_of_assigned_request'=>$no_of_assigned_request,
            'no_of_completed_verification'=>$no_of_completed_verification
        ];
        return response()->json([
            'status'=>true,           
            'data'=> $dash_data,
            'msg'=>1
        ]);
        // return view('admin.dashboard',compact('balance','no_of_verification_staff','no_of_new_request','no_of_assigned_request','no_of_completed_verification'));
       }
       else if($role=='verification staff')
       {
        
        $data=Verification::where('staff_id','=',$user_id)->get();        
        $no_of_new_request=$data->where('status','=',1)->count();       
        $no_of_completed_verification=$data->where('status','=',2)->count();      
        $dash_data=[                                
            
            'balance'=>$balance,
            

            'no_of_new_request'=>$no_of_new_request,
            
            'no_of_completed_verification'=>$no_of_completed_verification
        ];
        return response()->json([
            'status'=>true,           
            'data'=> $dash_data,
            'msg'=>1
        ]);
        // return view('admin.dashboard',compact('balance','no_of_new_request','no_of_completed_verification'));
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


        $lvl1=13;//13 for candidate Details
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
        //for Uncheck Offer

        $dash_data=[                                
            

            // 'no_of_offerletter_generated'=>$allofferletter,
            'progress_lvl'=>$progress_lvl,
            'offerletters'=>$offerletters,

            
        ];
        return response()->json([
            'status'=>true,           
            'data'=> $dash_data,
            'msg'=>1
        ]);

        // return view('admin.dashboard',compact('no_of_offerletter_generated','progress_lvl','offerletters'));
       }
        
    }

     

    public function updatePassword(Request $request)
    {
        //dd(1);
        $validator = Validator::make($request->all(),[
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
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        $uPass=Auth::user()->password;
        $oldPass=$request->old_password;
        $newPass=Hash::make($request->password);
       
        if(Hash::check($oldPass, $uPass)) {
            
            $upPass=User::where('id','=',Auth::user()->id)->update(['password'=>$newPass,'change_password'=>1]);
            if($upPass)
            {
                $token=$request->user()->token();
                $token->revoke();
            
                return response()->json([
                    'status' => true,
                    'msg' => 'Password Update Successfully and Successfully logged out',
                    'data' => 1
                ]);
                // return $msg=1;
                // return response()->json(['status' => true, 'success' => 'Password Update Successfully', 'data'=>1]);
            }
            else{
                // return $msg="Something Was Wrong!!";
                return response()->json(['status' => false, 'msg' => 'Something Was Wrong!!', 'data'=>0]);
            }
        }
        else{
           
            // return $msg="Old password did not match!!";
            return response()->json(['status' => false, 'msg' => 'Old password did not match!!', 'data'=>0]);
        }
        
        //dd($request->all());
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
        return view('page.pricing');
    }

    public function contact()
    {
        return view('page.contact');
    }

    public function blog()
    {
        return view('page.blog');
    }

    public function blogDetails()
    {
        return view('page.blog_details');
    }

    public function enrollStaff(Request $request)
    { 

       /**
        * for Store Enroll Staff (Lead/Verification)
        * Input:first_name,last_name,email,mobile_no,gender,role,password.
        * Output: Redirect with success/error
        */
       
        $validator = Validator::make($request->all(),[
            'name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',            
            'email' => 'required|email|max:255|check_mail|unique:users',
            'country'=>'required',
            'mobile' => 'required|numeric|digits_between:6,15',           
            'gender' => 'required|string' 
        ],
        [
            'check_mail'=>'Invalid Email Id',
            'name.regex'=>'Enter alphabets only.', 
            'mobile_no.required'=>'Mobile No Required',
            'mobile_no.numeric'=>'Mobile No must be digits',
            'mobile_no.digits_between'=>'Mobile No should be 6 to 15 digits',
             
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        }  

        $user = DB::table('staff_enroll')->insert([
                    'name' => $request->name,                            
                    'email' => strtolower($request->email),
                    'country'=>$request->country,
                    'mobile'=>$request->mobile,
                    'gender'=>$request->gender,
                    'staff_type' => $request->staff_type
                ]);
                      
        return response()->json([
            'status'=>true,           
            'data'=> 1,
            'msg'=>$request->staff_type.' Staff Enrolled Successfully On Orangeslip!'
        ]);
        
    }
   
    

}

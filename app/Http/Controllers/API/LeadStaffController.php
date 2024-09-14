<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require base_path("vendor/PHPMailer/PHPMailer/src/Exception.php");
require base_path("vendor/PHPMailer/PHPMailer/src/PHPMailer.php");
require base_path("vendor/PHPMailer/PHPMailer/src/SMTP.php");
require base_path("vendor/autoload.php");

class LeadStaffController extends Controller
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

    public function addLeadHead()
    {
        /**
         * For Create Agent Page View
         * Output:role
         */
        $this->authorize("access-manage-lead-head");        
        $country=Country::get();
        return view('leadStaff.addLeadHead',compact('country'));
    }

    public function saveLeadHead(Request $request)
    { 

       /**
        * for Store Agent Detail
        * Input:first_name,last_name,email,mobile_no,gender,role,password.
        * Output: Redirect with success/error
        */
        $this->authorize("access-manage-lead-head");       
       $validator = Validator::make($request->all(),[
            'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'email' => 'required|email|max:255|check_mail|unique:users',
            'mobile_no' => 'required|numeric|digits_between:6,15',           
            'gender' => 'required|string',                        
            'password' => 'required|string|min:8|confirmed'
        ],
        [
            'check_mail'=>'Invalid Email Id',
            'first_name.regex'=>'Enter alphabets only.',
            'last_name.regex'=>'Enter alphabets only.',

            'mobile_no.required'=>'Mobile No Required',
            'mobile_no.numeric'=>'Mobile No must be digits',
            'mobile_no.digits_between'=>'Mobile No should be 6 to 15 digits',
             
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        //dd(1);
        try {
            $result = DB::transaction(function () use ($request) {
                $gen_pwd=Str::random(8);
               //dd($gen_pwd);
               $user_code='REC'.rand(1111111,9999999);
                
                $user = User::create([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email' => strtolower($request->email),
                            'password' => Hash::make($request->password),
                            'account_type' => 'lead head',
                            'user_code' => $user_code,
                            'parent_id'=>Auth::user()->id
                        ]);
                        
                $profile = Profile::create([
                    'user_id'           => $user->id,
                    'mobile_no'         => $request->mobile_no,
                    'gender'            => $request->gender,
                    'country'           => $request->country
                   
                ]);
                
                $helper = new CommonHelper;
                $result = $helper->saveAssignedRole($user->id,$user->account_type);

            /*
                $sendData=[
                    'business_name' => $request->business_name,
                    'first_name' => $request->owner_first_name,
                    'last_name' => $request->owner_last_name,
                    'email' => strtolower($request->email),
                    'password' =>$gen_pwd
                    
                ];

                try{
                    Mail::to(strtolower($request->email))->queue(new SendBusinessLoginInfo($sendData));
                   // return $msg=1;
                    }
                    catch(\Exception $ex){
                    $stack_trace = $ex->getTraceAsString();
                    $message = $ex->getMessage().$stack_trace;
                    Log::error($message);                    
                    }
                    */
            });
            return redirect()->route('leadHeadList')->with('success','Lead Head Added Successfully');
        } catch (\Exception $e) {//dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function leadHeadList(Request $request)
    {
        // dd(1);
        $this->authorize("access-manage-lead-head");       
        $searchData=$request->all();
        $query = User::orderBy('users.id','DESC')        
        ->leftJoin('profiles as ps','users.id','=','ps.user_id');    
                
        if($request->keyword) {		
			if($request->keyword!=''){
				// $query->where('users.first_name','LIKE','%'.$request->keyword.'%')
                // ->orWhere('users.last_name','LIKE','%'.$request->keyword.'%')               
                // ->orWhere('ps.mobile_no','LIKE','%'.$request->keyword.'%')
                // ->orWhere('users.email','LIKE','%'.$request->keyword.'%');
                // $word=$request->keyword;
                $query->where(function($q) use ($request) {      
				$q->where('users.first_name','LIKE','%'.$request->keyword.'%');
                $q->orWhere('users.last_name','LIKE','%'.$request->keyword.'%');            
                $q->orWhere('ps.mobile_no','LIKE','%'.$request->keyword.'%');
                $q->orWhere('users.email','LIKE','%'.$request->keyword.'%');
                });
			}
		}

        if($request->email) {		
			if($request->email!=''){
				$query->where('users.email','LIKE','%'.$request->email.'%');
			}
		}

        if($request->mobile_no) {		
			if($request->mobile_no!=''){                
                $query->where('ps.mobile_no','LIKE','%'.$request->mobile_no.'%'); 
			}
		}
        	
        if($request->status!=''){                
            $query->where('users.status','=',$request->status); 
        }
    
        
        
        if($request->from_date && $request->to_date) {		
			if($request->from_date!='' && $request->to_date){
				$fd=$request->from_date;
                $td=$request->to_date;
                $query->whereBetween('users.created_at', [$fd." 00:00:00", $td." 23:59:59"]);
			}
		}

        $allHead=$query->where('users.account_type','=','lead head')->paginate(15);
        //$allHead->appends(request()->query());
        //dd($allHead);
        return view('leadStaff.leadHeadList',compact('allHead','searchData'));
    }

    public function editLeadHead(Request $request)
    {
        /**
         * For Edit HR/User Data page View
         * Input:id
         * Output:user,all_business,all_access,user_access.
         */
        $this->authorize("access-manage-lead-head");       
        $user=User::where('id','=',$request->id)->first();
        $country=Country::get();               
        return view('leadStaff.editLeadHead',compact('country','user'));
       
        
    }

    public function updateLeadHead(Request $request)
    {
        /**
         * For Update HR/User Data
         * Input:id,first_name,last_name,email,mobile_no,gender.
         * Output:Redirect with success/error.
         */
        $this->authorize("access-manage-lead-head");       
       $validator = Validator::make($request->all(),[
            'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'email' => 'required|email|check_mail|max:255|unique:users,email,'.$request->id,
            'mobile_no' => 'required|digits_between:6,15|numeric',          
            'country' => 'required',         
            'password' => 'nullable|string|min:8', 
            'gender' => 'required|string'            
        ],
        [
            'check_mail'=>'Invalid Email Id',
            'first_name.regex'=>'Enter alphabets only.',
            'last_name.regex'=>'Enter alphabets only.',

            'mobile_no.required'=>'Mobile No Required',
            'mobile_no.numeric'=>'Mobile No must be digits',
            'mobile_no.digits_between'=>'Mobile No should be 6 to 15 digits'
            
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        // dd($request->per);
        try {
            $result = DB::transaction(function () use ($request) {
              
                $user = User::where('id','=',$request->id)->Update(['first_name' => $request->first_name, 'last_name' => $request->last_name,'email' => strtolower($request->email) ]);

                $profile = Profile::where('user_id','=',$request->id)->Update(['mobile_no' => $request->mobile_no,'gender'=>$request->gender,'country'=>$request->country]);

                if($request->password!=Null){
                    $user = User::where('id','=',$request->id)->Update(['password' => Hash::make($request->password) ]);
                }
                               
             
            });
            return redirect()->route('leadHeadList')->with('success','Lead Head Updated Successfully');
        } catch (\Exception $e) {//dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

   

    public function saveLeadStaff(Request $request)
    { 

       /**
        * for Store Agent Detail
        * Input:first_name,last_name,email,mobile_no,gender,role,password.
        * Output: Redirect with success/error
        */
        $this->authorize("access-manage-lead-staff");  
       $validator = Validator::make($request->all(),[
            'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'email' => 'required|email|max:255|check_mail|unique:users',
            'mobile_no' => 'required|numeric|digits_between:6,15',           
            'gender' => 'required|string',                        
            'password' => 'required|string|min:8|confirmed'
        ],
        [
            'check_mail'=>'Invalid Email Id',
            'first_name.regex'=>'Enter alphabets only.',
            'last_name.regex'=>'Enter alphabets only.',

            'mobile_no.required'=>'Mobile No Required',
            'mobile_no.numeric'=>'Mobile No must be digits',
            'mobile_no.digits_between'=>'Mobile No should be 6 to 15 digits',
             
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        //dd(1);
        try {
            $result = DB::transaction(function () use ($request) {
                if(Auth::user()->account_type=='superadmin')
                {
                    $pid=$request->head;
                }
                else
                {
                    $pid=Auth::user()->id;
                }
                $gen_pwd=Str::random(8);
               //dd($gen_pwd);
               $user_code='REC'.rand(1111111,9999999);
                
                $user = User::create([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email' => strtolower($request->email),
                            'password' => Hash::make($request->password),
                            'account_type' => 'lead staff',
                            'user_code' => $user_code,
                            'parent_id'=>$pid
                        ]);
                        
                $profile = Profile::create([
                    'user_id'           => $user->id,
                    'mobile_no'         => $request->mobile_no,
                    'gender'            => $request->gender,
                    'country'           => $request->country
                   
                ]);
                
                $helper = new CommonHelper;
                $result = $helper->saveAssignedRole($user->id,$user->account_type);

            /*
                $sendData=[
                    'business_name' => $request->business_name,
                    'first_name' => $request->owner_first_name,
                    'last_name' => $request->owner_last_name,
                    'email' => strtolower($request->email),
                    'password' =>$gen_pwd
                    
                ];

                try{
                    Mail::to(strtolower($request->email))->queue(new SendBusinessLoginInfo($sendData));
                   // return $msg=1;
                    }
                    catch(\Exception $ex){
                    $stack_trace = $ex->getTraceAsString();
                    $message = $ex->getMessage().$stack_trace;
                    Log::error($message);                    
                    }
                    */
            });
            // return redirect()->route('leadStaffList')->with('success','Lead Staff Added Successfully');
            return response()->json([
                'status'=>true,           
                'data'=> 1,
                'msg'=>'Lead Staff Added Successfully'
            ]);
        } catch (\Exception $e) {//dd($e->getMessage());
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return response()->json([
                'status'=>false,           
                'data'=> 0,
                'msg'=>$e->getMessage()
            ]);
        }
    }

    public function leadStaffList(Request $request)
    {
        // dd(1);
        $this->authorize("access-manage-lead-staff");  
        $searchData=$request->all();
        $query = User::orderBy('users.id','DESC')        
        ->leftJoin('profiles as ps','users.id','=','ps.user_id');    
                
        if($request->keyword) {		
			if($request->keyword!=''){
				
                $query->where(function($q) use ($request) {      
                    $q->where('users.first_name','LIKE','%'.$request->keyword.'%');
                    $q->orWhere('users.last_name','LIKE','%'.$request->keyword.'%');            
                    $q->orWhere('ps.mobile_no','LIKE','%'.$request->keyword.'%');
                    $q->orWhere('users.email','LIKE','%'.$request->keyword.'%');
                    });
			}
		}

        if($request->email) {		
			if($request->email!=''){
				$query->where('users.email','LIKE','%'.$request->email.'%');
			}
		}

        if($request->mobile_no) {		
			if($request->mobile_no!=''){                
                $query->where('ps.mobile_no','LIKE','%'.$request->mobile_no.'%'); 
			}
		}
        	
        if($request->status!=''){                
            $query->where('users.status','=',$request->status); 
        }
    
        
        
        if($request->from_date && $request->to_date) {		
			if($request->from_date!='' && $request->to_date){
				$fd=$request->from_date;
                $td=$request->to_date;
                $query->whereBetween('users.created_at', [$fd." 00:00:00", $td." 23:59:59"]);
			}
		}

        if(Auth::user()->account_type=='superadmin')
        {
            $allHead=$query->where('users.account_type','=','lead staff')->paginate(15);
            $allHead->appends(request()->query());
        }
        else
        {
            $allHead=$query->where([['users.account_type','=','lead staff'],['users.parent_id','=',Auth::user()->id]])->paginate(15);
            $allHead->appends(request()->query());
        }
        
        //$allHead->appends(request()->query());
        //dd($allHead);
        // return view('leadStaff.leadStaffList',compact('allHead','searchData'));
        return response()->json([
            'status'=>true,           
            'data'=> $allHead,
            'msg'=>1
        ]);
    }

    public function editLeadStaff(Request $request)
    {
        /**
         * For Edit HR/User Data page View
         * Input:id
         * Output:user,all_business,all_access,user_access.
         */
        $this->authorize("access-manage-lead-staff");  
        
        $user=User::where('id','=',$request->id)->first();
        $country=Country::get();      
        if(((Auth::user()->account_type!='superadmin') && ($user->parent_id==Auth::user()->id)) || (Auth::user()->account_type=='superadmin') )
        {
            return response()->json([
                'status'=>true,           
                'data'=> $user,
                'msg'=>1
            ]);
            // return view('leadStaff.editLeadStaff',compact('country','user'));
        }
        else
        {
            return response()->json([
                'status'=>false,           
                'data'=> 0,
                'msg'=>'You do not have permission for this'
            ]);
        //   return abort(403,"You do not have permission for this");
        }   

        
       
        
    }

    public function updateLeadStaff(Request $request)
    {
        /**
         * For Update HR/User Data
         * Input:id,first_name,last_name,email,mobile_no,gender.
         * Output:Redirect with success/error.
         */
        $this->authorize("access-manage-lead-staff");  
       $validator = Validator::make($request->all(),[
            'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'email' => 'required|email|check_mail|max:255|unique:users,email,'.$request->id,
            'mobile_no' => 'required|digits_between:6,15|numeric',          
            'country' => 'required',         
            'password' => 'nullable|string|min:8', 
            'gender' => 'required|string'            
        ],
        [
            'check_mail'=>'Invalid Email Id',
            'first_name.regex'=>'Enter alphabets only.',
            'last_name.regex'=>'Enter alphabets only.',

            'mobile_no.required'=>'Mobile No Required',
            'mobile_no.numeric'=>'Mobile No must be digits',
            'mobile_no.digits_between'=>'Mobile No should be 6 to 15 digits'
            
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        // dd($request->per);
        try {
            $result = DB::transaction(function () use ($request) {
              
                $user = User::where('id','=',$request->id)->Update(['first_name' => $request->first_name, 'last_name' => $request->last_name,'email' => strtolower($request->email) ]);

                $profile = Profile::where('user_id','=',$request->id)->Update(['mobile_no' => $request->mobile_no,'gender'=>$request->gender,'country'=>$request->country]);

                if($request->password!=Null){
                    $user = User::where('id','=',$request->id)->Update(['password' => Hash::make($request->password) ]);
                }
                               
             
            });
            // return redirect()->route('leadStaffList')->with('success','Lead Staff Updated Successfully');
            return response()->json([
                'status'=>true,           
                'data'=> 1,
                'msg'=>'Lead Staff Updated Successfully'
            ]);
        
        } catch (\Exception $e) {//dd($e->getMessage());
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return response()->json([
                'status'=>false,           
                'data'=> 0,
                'msg'=>$e->getMessage()
            ]);
        }
    }

    public function assignBusLead()
    {
        $enrolls = EnrollCompany::where('status',1)->orWhere('status',5)->orderBy('id','DESC')->get();
        $allStaff=User::where('account_type','=' ,'lead staff')->get();
        // return view('business.assign_lead',compact('enrolls','allAgent'));
        return response()->json([
            'status'=>false,           
            'data'=> $enrolls,$allStaff,
            'msg'=>1
        ]);
    }

    public function assignBusLeadStore(Request $request)
    {
        $this->validate($request,[
            'lead_staff'=>'required'
        ]);
        // dd($request->all());
        if(isset($request->lead))
        {
            $leadStatus=EnrollCompany::where('id',$request->lead)->pluck('status')->first();
            if($leadStatus==1 || $leadStatus==5)
            {
                foreach($request->lead as $lead)
                {
                    EnrollCompany::where('id',$lead)->update(['is_assign'=>1,'lead_staff_id'=>$request->lead_staff,'status'=>5]);
                }        
        
                // return redirect('assign_enroll_lead')->with('success','Assigned Successfully!');
                return response()->json([
                    'status'=>true,           
                    'data'=> 1,
                    'msg'=>'Assigned Successfully!'
                ]);
            }
            else{
                // return redirect('assign_enroll_lead')->with('error','Lead Already Verified! ');
                return response()->json([
                    'status'=>true,           
                    'data'=> 1,
                    'msg'=>'Lead Already Verified!'
                ]);
            }
            

        }
        else
        {
        // return redirect('assign_enroll_lead')->with('error','Please Select Lead');
        return response()->json([
            'status'=>false,           
            'data'=> 0,
            'msg'=>'Please Select Lead'
        ]);
        }
    
    }

    
}
<?php

namespace App\Http\Controllers;

use App\Libs\CommonHelper;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
use App\Mail\EnrollmentStatus;
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

class BusinessController extends Controller
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

    public function changeStatus(Request $request)
    {

    //    dd($request->id);
       $userStatus= User::where('id',$request->id)->pluck('status')->first();
    //    dd($userStatus);
        if($userStatus==1)
        {
            //dd(1);
            $updateStatus= User::where('id',$request->id)->update(['status'=>2]);
            return redirect()->back()->with('error','Profile Deactivated!');
           

        }
        else
        {
            $updateStatus= User::where('id',$request->id)->update(['status'=>1]);
            return redirect()->back()->with('success','Profile Activated!');
             
        }

    }

    public function index(Request $request)
    {
        /**
         * View Employer/Business List
         * Input :b_name,email,mobile_no.
         * Output:View With Search.
         */
        $this->authorize("access-manage-business");
        $searchData=$request->all();
        $query = BusinessDetail::orderBy('business_details.id','DESC')
        ->join('users','business_details.user_id','=','users.id')
        ->join('profiles as ps','users.id','=','ps.user_id')
        ->join('no_of_employee_range as ner','business_details.no_of_employee','=','ner.id')
        ->where('users.account_type','=','business')
        ->select('users.*','ps.*','business_details.*','ner.*','users.status as userStatus','users.created_at as usercreatedate');
        if($request->keyword) {		
			if($request->keyword!=''){

                //$word=$request->keyword;
                $query->where(function($q) use ($request) {      
				$q->where('users.first_name','LIKE','%'.$request->keyword.'%');
                $q->orWhere('users.last_name','LIKE','%'.$request->keyword.'%');            
                $q->orWhere('business_details.business_name','LIKE','%'.$request->keyword.'%');            
                $q->orWhere('ps.mobile_no','LIKE','%'.$request->keyword.'%');
                $q->orWhere('users.email','LIKE','%'.$request->keyword.'%');
                });


                // $query ->where('users.first_name','LIKE','%'.$request->keyword.'%')
                // ->orWhere('users.last_name','LIKE','%'.$request->keyword.'%')
                // ->orWhere('business_details.business_name','LIKE','%'.$request->keyword.'%')
                // ->orWhere('ps.mobile_no','LIKE','%'.$request->keyword.'%')
                // ->orWhere('users.email','LIKE','%'.$request->keyword.'%');
               
                // $data = User::where('first_name','LIKE','%'.$request->keyword.'%')->orWhere('last_name','LIKE','%'.$request->keyword.'%')->get('id');
				// $query->whereIn('business_name','LIKE','%'.$request->keyword.'%');
			}
		}

        if($request->email) {		
			if($request->email!=''){
              //  $data = User::where('email','=',strtolower($request->email))->first();
                
				$query->where('users.email','LIKE','%'.strtolower($request->email).'%');
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
            if($request->export)
            {
                $expData=$query->where('users.account_type','=','business')->get();
                return Excel::download(new UsersExport('business',$expData), 'BusinessList.xlsx');             
                
            }
            $businesses = $query->where('users.account_type','=','business')->paginate(5);
        }
        else if(Auth::user()->account_type=='lead head')
        {
            if($request->export)
            {
                $expData=$query->where([['users.account_type','=','business'],['users.parent_id','=',Auth::user()->id]])->get();
                return Excel::download(new UsersExport('business',$expData), 'BusinessList.xlsx');             
                
            }
            $businesses = $query->where([['users.account_type','=','business'],['users.parent_id','=',Auth::user()->id]])->paginate(5);
        }
       
        $businesses->appends(request()->query());
        // dd($businesses);
        return view('business.index',compact('businesses','searchData'));
    }

    public function create(Request $request )
    {
        /**
         * Create Employer Page View
         * Input:enroll_id (if Already Enrolled)
         */
        // $this->authorize("access-manage-business");
        $this->authorize("access-manage-lead");
        $country=Country::get();
        $company=NULL;
        $allRange=noOfEmployeeRange::get();
        if(Auth::user()->account_type=='lead staff')
        {
            if(!($request->enroll_id)){
                // return abort(403,"You do not have permission for this");
                return redirect()->back()->with('error','You do not have permission for this');
            }
        }
        if($request->enroll_id) {		
			if($request->enroll_id!=''){
                $company=EnrollCompany::where('id','=',$request->enroll_id)->first();

                if(!(((Auth::user()->account_type=='lead staff') && ($company->lead_staff_id==Auth::user()->id)) || (Auth::user()->account_type!='lead staff')))
                {
                    // return abort(403,"You do not have permission for this");
                    return redirect()->back()->with('error','You do not have permission for this');
                }
                

				
			}
		}
        return view('business.create',compact('company','country','allRange'));
        
        
    }

    public function store(Request $request){
        /**
         * For Sotre Employer Details
         * Input:business_name,email,owner_first_name,owner_last_name,password,mobile_no,no_of_employee,registration_doc,business_logo,registration_date,business_address,data,status.
         * Output: Redirect with success/error.
         */
        
        $this->authorize("access-manage-lead");
        $data=json_encode($request->addMoreInputFields);
 
        $this->validate($request,[
            'business_name' => 'required|regex:/^[a-zA-Z0-9. ]+$/u|min:3',
            'email' => 'required|email|max:255|check_mail|unique:users',
            'owner_first_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:3|max:255',
            'owner_last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'pan'=>'required|unique:business_details',
            'gst'=>'required|unique:business_details',
            'mobile_no'=>'required|digits_between:6,15',
            'country'=>'required',
            'no_of_employee'=>'required|numeric|gt:0',
            'registration_doc'=>'required|max:1000|mimes:jpg,jpeg,png',
            'business_logo'=>'required|max:1000|mimes:jpg,jpeg,png'

        ],
        [
            'email.check_mail'=>'Invalid Email Id',
            'business_name.regex'=>'Enter alphabets only.',
            'no_of_employee.required'=>'No Of Employee Required',
            'no_of_employee.numeric'=>'No Of Employee must be digits',
            'no_of_employee.gt'=>'No of Employee must be greater than 0',

            'owner_first_name.required'=>'Owner first name Required',
            'owner_first_name.min'=>'Owner first name must be minimum 3 letters ',
            'owner_first_name.regex'=>'Enter alphabets only.',

            'owner_last_name.required'=>'Owner last name Required',
            'owner_last_name.min'=>'Owner last name must be minimum 3 letters ',
            'owner_last_name.regex'=>'Enter alphabets only.',

            'mobile_no.digits_between'=>'Mobile No. should be 6 to 15 digit',            

            'registration_doc.required'=>'Registration Doc Required',
            'registration_doc.max'=>'File is too large to upload',
            'registration_doc.mimes'=>'File type must be jpg/jpeg/png',
            
            'business_logo.required'=>'Logo is required',
            'business_logo.max'=>'File is too large to upload',
            'business_logo.mimes'=>'File type must be jpg/jpeg/png',
           
        ]);

       
       
       
        try {
            $result = DB::transaction(function () use ($request,$data) {

                if(Auth::user()->account_type!='lead staff')
                {
                    $parent=Auth::user()->id;
                }
                else
                {
                    $parent=Auth::user()->parent_id;
                }
               $gen_pwd=Str::random(8);
               //dd($gen_pwd);
               $user_code='REC'.rand(1111111,9999999);
                //dd($user_code);
                $user = User::create([
                            'first_name' => $request->owner_first_name,
                            'last_name' => $request->owner_last_name,
                            'email' => strtolower($request->email),
                            'password' => Hash::make($gen_pwd),
                            'account_type' => 'business',
                            'user_code' => $user_code,
                            'status'=>$request->status,                            
                            'referral_code' => $request->referral_code,
                            'parent_id'=>$parent
                        ]);

                $businessDetails = BusinessDetail::create([
                                    'user_id' => $user->id,
                                    'business_name' => $request->business_name,
                                    'registration_date' => $request->registration_date,
                                    'business_address' => $request->business_address,
                                    'no_of_employee' => $request->no_of_employee,
                                    'gst' => $request->gst,
                                    'pan' => $request->pan,
                                    'contact_persons'=>$data,
                                    'status'=>$request->status
                                ]);

                $profile = Profile::create([
                    'user_id'           => $user->id,
                    'country'           => $request->country,
                    'mobile_no'         => $request->mobile_no                    
                ]);

                if($request->enroll_id!=Null)
                {
                    $update_enroll_ststaus=EnrollCompany::where('id','=',$request->enroll_id)->update(['is_created'=>1,'status'=>3,'user_id'=>$user->id,'creator_id'=>Auth::user()->id]);
                    
                }

                if($request->file('registration_doc')){

                    $path = $request->file('registration_doc')
                        ->store('registration_doc');
                    $businessDetails->registration_doc=$path;
                    $businessDetails->save();                        
                }

                if($request->file('business_logo')){
                    
                    $path = $request->file('business_logo')
                        ->store('business_logo');

                    $businessDetails->logo=$path;
                    $businessDetails->save();                        
                }
                //$sar=saveAssignedRole($user->id,$user->account_type);
                //dd($sar);
                            $helper = new CommonHelper;
                            $result1 = $helper->saveAssignedRole($user->id,$user->account_type);

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
                               // dd($message);
                                Log::error($message);
                                //return $msg=2;
                                }

            });
            if(Auth::user()->account_type=='lead staff'){
                return redirect('/enroll_list')->with('success','Business Added Successfully');
            }
            else{
                return redirect()->route('business.index')->with('success','Business Added Successfully');
            }
           
        } catch (\Exception $e) {//dd( $e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Request $request )
    {
        /**
         * For Employer Edit Page View
         * Input:business
         * Output:Employer details
         */
        $this->authorize("access-manage-business");
        $employer=User::where('id',$request->business)->first();
        $country=Country::get();
        $allRange=noOfEmployeeRange::get();
        
	    return view('business.edit',compact('employer','country','allRange'));
    }
    public function updateBusiness(Request $request )
    {
        
        /**
         * For Update Employer Data
         * Input:id,business_name,email,owner_first_name,owner_last_name,mobile_no,no_of_employee,registration_doc,business_logo,registration_date,business_address,status.
         * Output: Redirect with success/error
         */
        $this->authorize("access-manage-business");
        // $data=null;
        // if(isset($request->addMoreInputFields)){
            $data=json_encode($request->addMoreInputFields);
        // }
                
               
                $this->validate($request,[
                            'business_name' => 'required|regex:/^[a-zA-Z0-9. ]+$/u|min:3',
                            'email' => 'required|email|max:255|check_mail|unique:users,email,'.$request->id,
                            'owner_first_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:3|max:255',
                            'owner_last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
                            
                            'mobile_no'=>'required|digits_between:6,15|numeric',
                            'no_of_employee'=>'required|numeric|gt:0',
                            'country'=>'required',
                            'gst'=>'required',
                            'pan'=>'required',
                            'password' => 'nullable|string|min:8',
                            'registration_doc'=>'nullable|max:1000|mimes:jpg,jpeg,png,pdf',
                            'business_logo'=>'nullable|max:1000|mimes:jpg,jpeg,png'
                
                        ],
                        [
                            'business_name.required'=>'Business Name Required',
                            'business_name.min'=>'Business Name must be minimum 3 letter',
                            'business_name.regex'=>'Enter alpha Numeric only.',
                
                            'owner_first_name.required'=>'Owner First Name Required',
                            'owner_first_name.min'=>'Owner First Name must be minimum 3 letter',
                            'owner_first_name.regex'=>'Enter alphabets only.',
                
                            'owner_last_name.required'=>'Owner Last Name Required',
                            'owner_last_name.min'=>'Owner Last Name must be minimum 3 letter',
                            'owner_last_name.regex'=>'Enter alphabets only.',
                
                            'mobile_no.required'=>'Mobile No Required',
                            'mobile_no.numeric'=>'Mobile No must be digits',
                            'mobile_no.digits_between'=>'Mobile No should be  6 to 15 digit',                             
                                                       
                
                            'check_mail'=>'Invalid Email Id',       

                            'no_of_employee.required'=>'No Of Employee Required',
                            'no_of_employee.numeric'=>'No Of Employee must be digits',
                            'no_of_employee.gt'=>'No Of Employee must be greater than 0',

                            'check_mail'=>'Invalid Email Id',

                            'registration_doc.max'=>'File is too large to upload',
                            'registration_doc.mimes'=>'File type must be jpg/jpeg/png/pdf',
                            'business_logo.max'=>'File is too large to upload',
                            'business_logo.mimes'=>'File type must be jpg/jpeg/png',
                        ]);
                                  
                        try {
                                $result = DB::transaction(function () use ($request,$data) {
                                    // dd($data); 
                                    $bd=BusinessDetail::where('user_id', $request->id)->first();      

                                    $user = User::where('id', $request->id)->update(['first_name' => $request->owner_first_name,'last_name' => $request->owner_last_name,'email' => strtolower($request->email),'status'=>$request->status]);
                                    
                                    $businessDetails = BusinessDetail::where('user_id', $request->id)->update(['business_name' => $request->business_name,'registration_date' => $request->registration_date,'business_address' => $request->business_address,'no_of_employee' => $request->no_of_employee,'contact_persons'=>$data,'gst'=>$request->gst,'pan'=>$request->pan,'bus_status'=>$request->status]);

                                    $profile = Profile::where('user_id', $request->id)->update(['mobile_no'=> $request->mobile_no,'country'=>$request->country]);
                                  
                                    if($request->password!=null){
                                        $upPass = User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
                    
                                    }
                    
                                    if($request->file('registration_doc')){
                                        $imagePath=$bd->registration_doc;
                                        if(File::exists($imagePath)){
                                        unlink($imagePath);
                                        }
                                        $path = $request->file('registration_doc')
                                            ->store('registration_doc');
                                        $update_reg_doc=BusinessDetail::where('user_id', $request->id)->update(['registration_doc'=>$path]);                                     
                                            
                                    }
                    
                                    if($request->file('business_logo')){
                                        
                                        $imagePath=$bd->business_logo;
                                        if(File::exists($imagePath)){
                                        unlink($imagePath);
                                        }
                                        $path = $request->file('business_logo')
                                            ->store('business_logo');
                                            $update_logo=BusinessDetail::where('user_id', $request->id)->update(['logo'=>$path]);                                 
                                            
                                    }
                                });
                                    return redirect()->route('business.index')->with('success','Business Details Updated Successfully');
                            } 
                            catch (\Exception $e) 
                            {
                               // dd( $e->getMessage());
                                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                            }
    }

    public function hrList(Request $request)
    {
        //dd($request->all());
        /**
         * For HR/User List View With Search Filter
         * Input:hr_name,email,mobile_no,business - for search
         * Output:List Of HR/User with search filter
         */
        $this->authorize("access-manage-hr-list");
        $searchData=$request->all();
        
        $query = User::orderBy('users.id','DESC')        
        ->leftJoin('profiles as ps','users.id','=','ps.user_id');
       // ->join('business_details as bd','users.parent_id','=','bd.user_id')
        // ->where('users.account_type','=','hr');
        

         
        if($request->keyword) {		
			if($request->keyword!=''){

                // $query->where(function($q) use ($start_date) {
                //     $q->where('price_date' ,'>=', $start_date);
                //     $q->orWhere('price_date' ,'>=', $start_date);
                //      });
                     
                $query->where(function($q) use ($request) {      
				$q->where('users.first_name','LIKE','%'.$request->keyword.'%');
                $q->orWhere('users.last_name','LIKE','%'.$request->keyword.'%');            
                $q->orWhere('ps.mobile_no','LIKE','%'.$request->keyword.'%');
                $q->orWhere('users.email','LIKE','%'.$request->keyword.'%');
                });
			}
		}
        // $allHr=$query->where('users.account_type','=','hr')->get('account_type')->toArray();
        // // $allHr->appends(request()->query());
        // dd($allHr);
        // dd($query);

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
    
        
        if($request->business) {		
			if($request->business!=''){
				$query->where('users.parent_id',$request->business);
			}
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
            if($request->export)
            {
                $expData=$query->where('users.account_type','=','hr')->get();
                return Excel::download(new UsersExport('hr',$expData), 'HrList.xlsx');             
                
            }
       

        $allBusiness=User::where('account_type','business')->get();
        $allHr=$query->where('users.account_type','=','hr')->paginate(15);
        
        $allHr->appends(request()->query());
        //dd($allHr);
        return view('business.hrlist',compact('allHr','allBusiness','searchData'));
        }
        else if(Auth::user()->account_type=='business')
        {
            if($request->export)
            {
                $expData=$query->where('users.parent_id','=',Auth::user()->id)->get();
                return Excel::download(new UsersExport('hr',$expData), 'HrList.xlsx');             
                
            }

            $allHr=$query->where('users.parent_id','=',Auth::user()->id)->paginate(15);
            $allHr->appends(request()->query());
            return view('business.hrlist',compact('allHr','searchData'));
        }

       
    }

    public function addHr()
    {
        /**
         * For Create HR/User Page View
         * Input:Null
         * Output: All Business, All Access(User/HR)
         */
        $this->authorize("access-manage-hr-list");
        $all_business=User::where('account_type','=','business')->get();
        $all_access=UserAccessMaster::get();
        $country=Country::get();
        return view('business.addHr',compact('all_business','all_access','country'));
    }

    public function saveHrDetails(Request $request)
    {       
         
        /**
         * For Sotre HR/User Details
         * Input: first_name,last_name,email,mobile_no,gender,password,parent_id,per,
         * Output: Redirect with success/error.
         */
        $this->authorize("access-manage-hr-list");   

        $this->validate($request, [
            'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'email' => 'required|email|check_mail|max:255|unique:users',
            'mobile_no' => 'required|digits_between:6,15|numeric',
            // 'dob' => 'required|date_format:Y-m-d',
            'gender' => 'required|string',
            // 'owner_first_name' => 'required|string|max:255',
            // 'owner_last_name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'desg' => 'required|string'

        ],
        [
            'email.check_mail'=>'Invalid Email Id',

            'first_name.regex'=>'First Name should be alphabets only.',
            'last_name.regex'=>'Last Name should be alphabets only.',

            'mobile_no.required'=>'Mobile No. Required',
            'mobile_no.numeric'=>'Mobile No. must be in digits',
            'mobile_no.digits_between'=>'Mobile No. should be of 6 to 15 digits',  
            'desg.required'=>'Designation Required',  
        ]);


        
        // dd($request->per);
        try {
            $result = DB::transaction(function () use ($request) {
            $businessDetails=User::where('id',$request->parent_id)->first();
                $gen_pwd=Str::random(8);
                
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => strtolower($request->email),
                    'password' => Hash::make($gen_pwd),
                    'account_type' => 'hr',
                    'parent_id'=>$request->parent_id
                        ]);

                $profile = Profile::create([
                    'user_id'           => $user->id,
                    'mobile_no'         => $request->mobile_no,
                    'country'         => $request->country,
                    'gender'            => $request->gender,
                    'designations'      => $request->desg
                   
                ]);
                if(($request->per)!=Null){
                    foreach($request->per as $key=>$access)
                {
                    $user_access = IndividualUserAccess::create([
                        'user_id'           => $user->id,
                        'access_id'         => $key,
                        'access_status'         => $access
                        
                    ]);
                }
                }
                
               // $sar=saveAssignedRole($user->id,$user->account_type);
               $helper = new CommonHelper;
                $result = $helper->saveAssignedRole($user->id,$user->account_type);

                $chk_data=DB::table('business_mail_server_details')->where('business_id',$request->parent_id)->pluck('business_id')->first();
                if($chk_data!=Null){
                    $mailsetting=DB::table('business_mail_server_details')->where('business_id',$request->parent_id)->first();
                }
                else{
                    $mailsetting=DB::table('business_mail_server_details')->where('business_id',1)->first();
                }

                    $mail = new PHPMailer(true);          

                    try {
                        
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = $mailsetting->mail_host;                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = $mailsetting->mail_username	;                     //SMTP username
                        $mail->Password   = $mailsetting->mail_password;                               // 
                        $mail->Port       = $mailsetting->mail_port;                                     
                        $mail->setFrom($mailsetting->from_address, $mailsetting->from_name);
                        $mail->addAddress(strtolower($request->email), $request->first_name);     //Add a recipient
                        $mail->AddCC($businessDetails->email, $businessDetails->business->business_name);
                    

                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'HR Login Details';
                        $mailContent = "<body style='background: #f6f6f6; color:#222; padding: 10px 10px; font-size: 13px; line-height: 22px;'>


  
                        <div style='max-width: 700px; font-family: Poppins; z-index: 11; background: #fff; margin: 10px auto; position: relative;'>
                           
                            <div style='padding: 15px 0px; border-bottom: 1px solid #ccc; text-align: center;'>
                                <img style='width: 200px;' src='https://orangeslip.com/new/images/logo.png' alt='' />
                            </div>
                            <div style='padding: 50px 20px;'>
                                <h4 style='text-align: left; margin: 0px;'>Hi $request->first_name </h4>
                               
                                
                                <p style='margin-bottom: 10px;'>
                                Thank You. The registration of your HR profile with this account was successful. For login, kindly <a href='https://orangeslip.com/login'> click this link</a>. The login credentials for your profile are provided here.
                                </p>
                                <p style='margin-bottom: 10px;'>
                                Email: $request->email
                                 
                                </p>
                                <p style='margin-bottom: 10px;'>                                 
                                Password: $gen_pwd                             
                                </p>
                                <p style='margin-bottom: 10px;'>                               
                                Link: <a href='https://orangeslip.com/login'>https://orangeslip.com/login</a>
                                </p>
                                
                            </div>
                            <div style='padding: 20px 20px; background: #002745; color:#fff;'>
                                <p style='text-align: center; margin: 0px;'>Thanks for connecting with us.</p>
                            </div>
                        </div>
                        
                        </body>";


                        $mail->Subject = 'HR Login Details';
                        $mail->Body    = $mailContent;

                        $mail->send();
                    // echo 'Message has been sent';
                    } catch (Exception $e) {
                        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
               
            });
            return redirect()->route('hr_list')->with('success','HR Added Successfully');
        } catch (\Exception $e) {//dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function editHr(Request $request)
    {
        /**
         * For Edit HR/User Data page View
         * Input:id
         * Output:user,all_business,all_access,user_access.
         */
        $this->authorize("access-manage-hr-list");
        $user=User::where('id','=',$request->id)->first();
        $country=Country::get();
        $all_business=User::where('account_type','=','business')->get();
        $all_access=UserAccessMaster::get();
        $user_access=IndividualUserAccess::where('user_id','=',$request->id)->pluck('access_id')->toArray();
        //dd($user_access);
        if(((Auth::user()->account_type!='superadmin') && (Auth::user()->id==$user->parent_id)) || (Auth::user()->account_type=='superadmin') ){
            return view('business.editHr',compact('user','all_business','all_access','user_access','country'));
        }
        else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
        
    }

    public function updateHr(Request $request)
    {
        /**
         * For Update HR/User Data
         * Input:id,first_name,last_name,email,mobile_no,gender.
         * Output:Redirect with success/error.
         */
        $this->authorize("access-manage-hr-list");
        $this->validate($request, [
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
        // dd($request->per);
        try {
            $result = DB::transaction(function () use ($request) {
                $userAccess=IndividualUserAccess::where('user_id','=',$request->id)->get();
               // dd($userAccess);
                $user = User::where('id','=',$request->id)->Update(['first_name' => $request->first_name, 'last_name' => $request->last_name ]);

                $profile = Profile::where('user_id','=',$request->id)->Update(['mobile_no' => $request->mobile_no,'gender'=>$request->gender,'country'=>$request->country]);
                if($request->password!=Null){
                    $user = User::where('id','=',$request->id)->Update(['password' => Hash::make($request->password) ]);
                }
                foreach($userAccess as $acc)
                    {
                       // dd($acc->id);
                        $del_access =DB::table('individual_user_access')->where('id','=',$acc->id)->delete();
                        //$del_access->delete();
                            
                    }
                if(($request->per)!=Null){
                    foreach($request->per as $key=>$access)
                    {
                        $user_access = IndividualUserAccess::create([
                            'user_id'           => $request->id,
                            'access_id'         => $key,
                            'access_status'     => $access
                            
                        ]);
                    }
                }
                
             
            });
            return redirect()->route('hr_list')->with('success','HR Updated Successfully');
        } catch (\Exception $e) {//dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function editProfile()
    {
        /**
         * Edit Profile
         * Input:Null
         * Output: Auth User Data.
         */
        $user=User::where('id','=',Auth::user()->id)->first();       
        $country=Country::get();       
        
            return view('business.editProfile',compact('user','country'));  
        
    }


    public function updateProfile(Request $request)
    {
        /**
         * Update Profile
         * Input:dob,pin_code,avatar,mobile_no,gender,maritial_status,religion,address.
         * Output: Redirect With success/error.
         */
        
        $this->validate($request, [

            //'first_name' => 'required|string|max:255|min:3',
            //'middle_name' => 'required|string|max:255',
            //'last_name' => 'required|string|max:255|min:3',
            //'email' => 'required|string|email|max:255|check_mail|unique:users,email,'.$request->id,
            //'mobile_no' =>'required|digits:10|unique:users,email,'.$request->id,
            'dob' => 'required|date_format:Y-m-d|before:today',
            'pin_code' => 'required|digits:6',
            'avatar' => 'nullable|max:1000|mimes:jpg,jpeg,png'

        ],
        [
            'dob.required'=>'DOB Required',
            'dob.date_format'=>'DOB must be DD-MM-YYYY format',
            'dob.before'=>'DOB must be before today',
            'pin_code.digits'=>'Pin Code must be 6 digits',
            'pin_code.required'=>'Pin Code Required',       
            'avatar.max'=>'File size is too large to upload max size 100KB',
            'avatar.mimes'=>'File type must be jpg/jpeg/png'
 
        ]);
        
        try {
            $result = DB::transaction(function () use ($request) {

               
                if($request->avatar!=Null){
                    $imagePath=$request->old_avatar;
                    if(File::exists($imagePath)){
                    unlink($imagePath);
                    }
                    $avatar_link = $request->file('avatar')
                                    ->store('avatar');
                }
                else{
                    $avatar_link =$request->old_avatar;
                }

                if($request->business_logo!=Null){

                    $imagePath=$request->old_logo;
                    if(File::exists($imagePath)){
                    unlink($imagePath);
                    }
                    $logo_link = $request->file('business_logo')
                                    ->store('business_logo');
                }
                else{
                    $logo_link =$request->old_logo;
                }

                

                
                $profile = Profile::where('user_id',Auth::user()->id)->update([
                     
                    'mobile_no'         => $request->mobile_no,
                    'gender'         => $request->gender,
                    'maritial_status'         => $request->maritial_status,
                    'religion'         => $request->religion,
                    'dob'         => $request->dob,
                    'address'         => $request->address,
                    'pin_code'         => $request->pin_code,
                    'avatar'         => $avatar_link                   
                    
                ]);

                $business = BusinessDetail::where('user_id',Auth::user()->id)->update([                     
                    'logo'         => $logo_link                           
                    
                ]);
                              
            });
            return redirect()->back()->with('success','Profile Updated Successfully.');
        } catch (\Exception $e) {//dd( $e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        } 
        
    }

    


   
    public function enrollCompanyView(Request $request)
    {
        /**
         * For Company Enroll Page View
         * 
         */
        $refCode=NULL;
        if($request->ref_code){
            if($request->ref_code!='')
            {
            $refCode=$request->ref_code;
            }
        }
        $country=Country::get();
        $allRange=noOfEmployeeRange::get();
        return view('page.businesslead',compact('country','allRange','refCode'));
    }

    public function enrollCompanyStore(Request $request)
    {
        /**
         * Store Company Data
         * Input: business_name,email,owner_first_name,owner_last_name,mobile_no,no_of_employee,g-recaptcha-response.
         * Output:Redirect with success/error.
         */
        // return redirect('/thankyou');
      
        $this->validate($request,[
            'business_name'=>'required|regex:/^[a-zA-Z0-9. ]+$/u|min:3',
            'email'=>'required|email|check_mail|rejected',
            'owner_first_name'=>'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'owner_last_name'=>'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'mobile_no'=>'required|numeric|digits_between:6,15',            
            'no_of_employee'=>'required|numeric|gt:0',
            'country'=>'required',
            'gst'=>'required|unique:enroll_companies',
            'pan'=>'required|unique:enroll_companies',
            // 'g-recaptcha-response'=>'required' ,
            'ref_code'=>'nullable',          

        ],
        [
            'business_name.required'=>'Business Name Required',
            'business_name.min'=>'Business Name must be minimum 3 letter',
            'business_name.regex'=>'Enter Alpha Numeric only.',

            'owner_first_name.required'=>'Owner First Name Required',
            'owner_first_name.min'=>'Owner First Name must be minimum 3 letter',
            'owner_first_name.regex'=>'Enter alphabets only.',

            'owner_last_name.required'=>'Owner Last Name Required',
            'owner_last_name.min'=>'Owner Last Name must be minimum 3 letter',
            'owner_last_name.regex'=>'Enter alphabets only.',

            'mobile_no.required'=>'Mobile No Required',
            'mobile_no.numeric'=>'Mobile No must be digits',
            'mobile_no.digits_between'=>'Mobile No should be 6 to 15 digits',
            
             

            'pan.unique'=>'PAN Already Exist',        
            'gst.unique'=>' GST Already Exist',        

            'email.email'=>'Check email format',        
            'email.check_mail'=>'Invalid Email Id',        
            'email.rejected'=>'Email Exist and Not Rejected', 

            // 'g-recaptcha-response.required'=>'Please Select Captcha',
            'no_of_employee.required'=>'No Of Employee Required',
            'no_of_employee.numeric'=>'No Of Employee must be digits',
            'no_of_employee.gt'=>'No Of Employee must be greater than 0'

        ]);
        //dd($request->all());
       if($request->ref_code!=''){
            $chk_ref_code=User::where('user_code',$request->ref_code)->pluck('id')->first();
       
            if(!$chk_ref_code)
            {
                return redirect('enroll_company')->with('error','Reference Code mismatched!')->withInput();
            }
    }
        
        $enroll=EnrollCompany::insert(['business_name'=>$request->business_name,'email'=>strtolower($request->email),'owner_first_name'=>$request->owner_first_name,'owner_last_name'=>$request->owner_last_name,'mobile_no'=>$request->mobile_no,'gst'=>strtoupper($request->gst),'pan'=>strtoupper($request->pan),'country'=>$request->country,'no_of_employee'=>$request->no_of_employee,'referral_code'=>$request->ref_code]);
        if($enroll)
        {
            try{
                Mail::to(strtolower($request->email))->queue(new SendEmployerEnrolled());
               
               return redirect('/thankyou');
           
                }
                catch(\Exception $ex){
                $stack_trace = $ex->getTraceAsString();
                $message = $ex->getMessage().$stack_trace;
              
                Log::error($message);
                
                }

          
        }
        else
        {
            return redirect('/')->with('error','Something was Wrong!');
        }

    }
    public function enrollList(Request $request)
    { 
        /**
         * Enrolled Company List with search filter
         * Input:business_name,email,mobile_no,status - for search.
         * Output: List Of Enrolled Company
         */
        $this->authorize("access-manage-lead");
        $data = $request->all();
        $leadStaff=User::where('account_type','=','lead staff')->get();
        if((Auth::user()->account_type=='superadmin')){

            $query = EnrollCompany::orderBy('id','DESC');
        }
        else if((Auth::user()->account_type=='lead head')){
            $allStaff=User::where([['account_type','=','lead staff'],['parent_id',Auth::user()->id]])->pluck('id')->toArray();
            // $query = EnrollCompany::whereIn('lead_staff_id', $allStaff)->orderBy('id','DESC');
          

            $query = EnrollCompany::where(function($q) use ($allStaff) {  
                $q->whereIn('lead_staff_id', $allStaff);
                // $q->orWhere('lead_staff_id',NULL);
                })->orderBy('id','DESC');

        }
        else{
            $query = EnrollCompany::where('lead_staff_id',Auth::user()->id)->orderBy('id','DESC');
        }

        if($request->business_name) {		
			if($request->business_name!=''){
				$query->where('business_name','LIKE','%'.$request->business_name.'%');
			}
		}

        if($request->email) {		
			if($request->email!=''){
				$query->where('email','LIKE','%'.$request->email.'%');
			}
		}
        if($request->assign_to) {		
			if($request->assign_to!=''){
				$query->where('lead_staff_id','=',$request->assign_to);
			}
		}

        if($request->mobile_no) {		
			if($request->mobile_no!=''){
				$query->where('mobile_no','LIKE','%'.$request->mobile_no.'%');
			}
		}
        
         	
			if($request->status!=''){
				$query->where('status',$request->status);
			}
		 

        if($request->export)
        {
            $expData=$query->get();
            return Excel::download(new UsersExport('enroll',$expData), 'EnrolledList.xlsx');             
            
        }
        $enrolls=$query->paginate(5);

        $enrolls->appends(request()->query());
        //$areturn = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
       // dd($areturn);
       
            return view('business.enroll_list',compact('enrolls','data','leadStaff'));
         

    }
    public function companyEnrollResponse(Request $request)
    { 
        /**
         * for Enrolled Company Response
         * Input:enroll_id,response,reason.
         * Output:msg
         */
        $this->authorize("access-manage-lead");
      // dd($request->response);
      $enroll=EnrollCompany::where('id','=',$request->enroll_id)->first();
      if((Auth::user()->account_type=='lead staff') && (Auth::user()->id!=$enroll->lead_staff_id))
      {
        return $msg='You do not have permission for this!';
      }
       
        if($request->response==1)
        {
            $update_enroll=EnrollCompany::where([['id','=',$request->enroll_id],['status','!=',2]])->update(['is_verified'=>1,'status'=>2,'verifier_id'=>Auth::user()->id]);
            $sendData=[
                'business_name' => $enroll->business_name,                
                'email' => strtolower($enroll->email),                                     
                'status'=>$request->response
            ];
                
                try{
                Mail::to(strtolower($enroll->email))->queue(new EnrollmentStatus($sendData));
               // return $msg=1;
                }
                catch(\Exception $ex){
                $stack_trace = $ex->getTraceAsString();
                $message = $ex->getMessage().$stack_trace;
               // dd($message);
                Log::error($message);
                //return $msg=2;
                }

            return $msg=1;
        }
        else if($request->response==2)//reject
        {
            $update_enroll=EnrollCompany::where([['id','=',$request->enroll_id],['status','!=',2]])->update(['is_verified'=>2,'status'=>4,'reason'=>$request->reason,'verifier_id'=>Auth::user()->id]);

            $sendData=[
                'business_name' => $enroll->business_name,                
                'email' => strtolower($enroll->email),                                     
                'status'=>$request->response
            ];
                
                try{
                Mail::to(strtolower($enroll->email))->queue(new EnrollmentStatus($sendData));
               // return $msg=1;
                }
                catch(\Exception $ex){
                $stack_trace = $ex->getTraceAsString();
                $message = $ex->getMessage().$stack_trace;
               // dd($message);
                Log::error($message);
                //return $msg=2;
                }

            return $msg=2;
        }
        else
        {
            return $msg='something was wrong!';
        }

    }

     

    public function followUpList(Request $request)
    {
        /**
         * Enrolled Company Followup Page View
         * Input:id
         * Output:all_fup,lead_id,empDetail
         */
       // dd($areturn);
        //$areturn=$areturn;
        $this->authorize("access-manage-lead");
        $lead_id=$request->id;
        $empDetail=EnrollCompany::where('id',$lead_id)->first();
        $all_fup=LeadFollowUp::where('lead_id','=',$lead_id)->orderby('id','DESC')->get();


        $maxlead=LeadFollowUp::where('lead_id','=',$lead_id)->max('id');
        $maxstatus=LeadFollowUp::where('id','=',$maxlead)->pluck('status')->first();
       //dd($maxstatus);
       if(((Auth::user()->account_type=='lead staff') && ($empDetail->lead_staff_id==Auth::user()->id)) || (Auth::user()->account_type!='lead staff'))
       {
        return view('business.follow_up',compact('all_fup','lead_id','empDetail','maxstatus'));
       }
       else
       {
        // return abort(403,"You do not have permission for this");
        return redirect()->back()->with('error','You do not have permission for this');
    
       }
        
    }
    public function followUpStore(Request $request)
    {
        
        /**
         * Enrolled Company Followup Store
         * Input:id,remarks,next_date,maxstatus
         * Output:Redirect with success/error
         */
        $this->authorize("access-manage-lead");

        $this->validate($request,[
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
            $data=LeadFollowUp::insert(['lead_id'=>$request->id,'agent_id'=>Auth::user()->id,'date'=>date('Y-m-d H:i:s'),'remarks'=>$request->remarks,'next_contact_date'=>$request->next_date,'next_time'=>$request->next_time,'status'=>1]);
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
    
     /* Login  As Member and back to admin */
     public function login_as_member(Request $request)
     {
        /**
         * for Admin login to another user account 
         */
         $this->authorize("access-manage-role");
         $admin = auth()->user();
         $user_id = $request->input("user_id");
         $user = User::findOrFail($user_id);
         //dd($admin,$user_id,$user);
         Session::put('adminLogin', true);
         Session::put("adminUserId", $admin->id);
         Auth::login($user);
         $username = $user->first_name;
         return redirect('dashboard')->withSuccess("You are now Loged in as $username");
         return redirect()->route('user.dashboard');
     }	
 
     public function login_as_admin()
     {
        /**
         * for Login back to administration from another user 
         */
 
         if(Session::get('adminLogin')){
 
             Session::forget('adminLogin');
 
             $admin_id = Session::get('adminUserId');
 
             Auth::loginUsingId($admin_id, true);
 
             return redirect()->route('dashboard');
 
         }else{
 
             abort(404);
 
         }
 
     }
     public function followUpStatusUpdate(Request $request)
     {
        // dd($request->all());
        $this->authorize("access-manage-lead");
        $data=LeadFollowUp::where('id','=',$request->fup_id)->update(['note'=>$request->note,'status'=>2]);
        if($data)
        {
            return $msg=1;
        }
        else
        {
            return $msg='something was wrong';
        }
     }
     
     public function getDomain(Request $request){
        $business_id=$request->business_id;
        $email=User::where('id',$business_id)->pluck('email')->first();
        $domain = explode("@",$email);
        return $domain[1];
     }
     
    
}
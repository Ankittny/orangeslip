<?php

namespace App\Http\Controllers\API;

use App\Libs\CommonHelper;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\BusinessDetail;
use App\Models\LeadFollowUp;
use App\Models\EnrollCompany;
use App\Models\IndividualUserAccess;
use App\Models\UserAccessMaster;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendBusinessLoginInfo;
use App\Mail\SendEmployerEnrolled;
use Silber\Bouncer\Database\Role;
use Storage;
use Session;
use Auth;
use Str;
use Log;
use Bouncer;
use Carbon\Carbon;
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
        /**
         * for active or inactive user
         */
       $userStatus= User::where('id',$request->id)->pluck('status')->first();
       //dd($userStatus);
        if($userStatus==1)
        {
            $updateStatus= User::where('id',$request->id)->update(['status'=>2]);
            return response()->json([
                'status'=>true,           
                'data'=>0,
                'msg'=>'Profile Deactivated!'
            ]);

        }
        else
        {
            $updateStatus= User::where('id',$request->id)->update(['status'=>1]);
            return response()->json([
                'status'=>true,           
                'data'=>1,
                'msg'=>'Profile Activated!'
            ]);
        }

    }

    public function index(Request $request){
        $this->authorize("access-manage-business");
        $query = BusinessDetail::orderBy('id','DESC');
        if($request->b_name) {		
			if($request->b_name!=''){
				$query->where('business_name','LIKE','%'.$request->b_name.'%');
			}
		}

        if($request->email) {		
			if($request->email!=''){
                $data = User::where('email','=',strtolower($request->email))->first();
                
				$query->where('user_id',$data->id);
			}
		}

        if($request->mobile_no) {		
			if($request->mobile_no!=''){
                $data = Profile::where('mobile_no','=',$request->mobile_no)->orderBy('id','DESC')->first();
                //dd($data);
				$query->where('user_id',$data->user_id);
			}
		}
        
        
        $businesses = $query->get();
        
        return response()->json([
            'status'=>true,           
            'data'=>$businesses,
            'msg'=>1
        ]);
        // return view('business.index',compact('businesses'));
    }

    
   
    public function updateBusiness(Request $request )
    {
        $this->authorize("access-manage-business");
        if(Auth::user()->account_type=='superadmin'){
                $data=json_encode($request->addMoreInputFields);
                //dd($data);
                $validator = Validator::make($request->all(),[
                            'business_name' => 'required|regex:/^[a-zA-Z0-9 ]+$/u|min:3',
                            'email' => 'required|email|max:255|check_mail|unique:users,email,'.$request->id,
                            'owner_first_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:3|max:255',
                            'owner_last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
                            'country'=>'required',
                            'mobile_no'=>'required|digits_between:6,15|numeric',
                            'no_of_employee'=>'required|numeric|gt:0',
                            'registration_doc'=>'max:1000|mimes:jpg,jpeg',
                            'business_logo'=>'max:1000|mimes:jpg,jpeg'
                
                        ],
                        [
                            'business_name.required'=>'Business Name Required',
                            'business_name.min'=>'Business Name must be minimum 3 letters',
                            'business_name.regex'=>'Business Name should be in Alpha Numeric only.',
                
                            'owner_first_name.required'=>'Owner First Name Required',
                            'owner_first_name.min'=>'Owner First Name must be minimum 3 letter',
                            'owner_first_name.regex'=>'Owner First Name should be alphabets only.',
                
                            'owner_last_name.required'=>'Owner Last Name Required',
                            'owner_last_name.min'=>'Owner Last Name must be minimum 3 letter',
                            'owner_last_name.regex'=>'Owner Last Name should be alphabets only.',
                
                            'mobile_no.required'=>'Mobile No Required',
                            'mobile_no.numeric'=>'Mobile No. must be in digits',
                            'mobile_no.digits_between'=>'Mobile No should be of 6 to 15 digits',                    
                
                            'check_mail'=>'Invalid Email Id',       

                            'no_of_employee.required'=>'No Of Employee Required',
                            'no_of_employee.numeric'=>'No Of Employee must be in digits',
                            'no_of_employee.gt'=>'No Of Employee must be greater than 0',

                            'check_mail'=>'Invalid Email Id',

                            'registration_doc.max'=>'File is too large to upload',
                            'registration_doc.mimes'=>'File type must be in jpg/jpeg',
                            'business_logo.max'=>'File is too large to upload',
                            'business_logo.mimes'=>'File type must be in jpg/jpeg',
                        ]);
                
                        if ($validator->fails()) {
                            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
                            }
                
                       
                        try {
                            $result = DB::transaction(function () use ($request,$data) {
                               // dd($data);
                                $user = User::where('id', $request->id)->update(['first_name' => $request->owner_first_name,'last_name' => $request->owner_last_name,'email' => strtolower($request->email)]);
                
                                $businessDetails = BusinessDetail::where('user_id', $request->id)->update(['business_name' => $request->business_name,'registration_date' => $request->registration_date,'business_address' => $request->business_address,'no_of_employee' => $request->no_of_employee,'contact_persons'=>$data,'status'=>$request->status]);
                                $profile = Profile::where('user_id', $request->id)->update(['mobile_no'=> $request->mobile_no,'country'=>$request->country]);
                                
                
                                if($request->file('registration_doc')){
                                    
                                    $path = $request->file('registration_doc')
                                        ->store('registration_doc');
                                        $update_reg_doc=BusinessDetail::where('user_id', $request->id)->update(['registration_doc'=>$path]);
                                     
                                        
                                }
                
                                if($request->file('business_logo')){
                                    
                                    $path = $request->file('business_logo')
                                        ->store('business_logo');
                                        $update_logo=BusinessDetail::where('user_id', $request->id)->update(['logo'=>$path]);
                                     
                                        
                                }
                            });
                            return response()->json(['status' => true, 'msg' => 'Employer Details Updated Successfully', 'data'=>1]);
                            // return redirect()->route('business.index')->with('success','Employer Details Updated Successfully');
                        } catch (\Exception $e) {//dd( $e->getMessage());
                            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                            return response()->json(['status' => false, 'msg' => $e->getMessage(), 'data'=>0]);
                        }

                    }
                    else{
                        return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
                    }
    }

   
               


    public function updateProfileImage(Request $request)
    {
        
        try {
            $validator = Validator::make($request->all(), [
                'avatar' => 'required|base64img|is_png_jpg',
                        
                
            ],[
                'avatar.is_png_jpg'=>'Profile pic must be png or jpg'
            ]);
      
            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors(), 'success'=>0]);
            }
             
            $user = auth()->user();
            $path = $user->profile->avatar;

            Storage::delete($path);
            
     
			$base64_image=$request->avatar;
				
			$image = preg_replace('/^data:image\/\w+;base64,/', '', $base64_image);
			$type = explode(';', $base64_image)[0];
			$type = explode('/', $type)[1]; // png or jpg etc

			// $imageName = str_random(10).'.'.$type;
			$imageName = rand(111111,999999).'.'.$type;
			
			Storage::disk('local')->put('avatar/'.$imageName, base64_decode($image));

            $profile = Profile::where('user_id',Auth::user()->id)->update(['avatar'=> 'avatar/'.$imageName]);
            // $user->profile->update([
            //     'avatar' => $imageName
            // ]);

            return response()->json(['status' => true,'msg' => 'Your profile picture has been changed.']);
            //flash()->success('Success! Profile picture has been changed.');

        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json(['status' => false,'msg' => 'Uploading profile picture failed.']);
            //flash()->error('Error! Changing profile picture failed.');
        }
      

        //dd(1);
        // $validator=Validator::make($request->all(), [

        //     'avatar' => 'max:1000|mimes:jpg,jpeg'
        // ],
        // [
           
        //     'avatar.max'=>'File size is too large to upload max size 100KB',
        //     'avatar.mimes'=>'File type must be jpg/jpeg'
 
        // ]);

        // if($validator->fails())
        // {
        //     return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        // }
/*
        try {
            $result = DB::transaction(function () use ($request) {

               
                if($request->avatar!=Null){
                    $avatar_link = $request->file('avatar')
                                    ->store('avatar');
                }
                else{
                    $avatar_link=Profile::where('user_id',Auth::user()->id)->pluck('avatar')->first();
                    //dd($avatar_link);
                    // $avatar_link =$request->old_avatar;
                }

                                
                $profile = Profile::where('user_id',Auth::user()->id)->update(['avatar'=> $avatar_link]);
                              
            });
            // return redirect()->back()->with('success','Profile Updated Successfully.');
            return response()->json(['status'=>true, 'msg'=>'Profile Image Updated Successfully.', 'data'=>1]);
        } catch (\Exception $e) {dd( $e->getMessage());
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return response()->json(['status'=>false, 'msg'=>$e->getMessage(), 'data'=>0]);
        } 
        */
    }

    public function updateBusinessLogo(Request $request)
    {
        
        try {
            $validator = Validator::make($request->all(), [
                'business_logo' => 'required|base64img|is_png_jpg',
                        
                
            ],[
                'business_logo.is_png_jpg'=>'Logo must be png or jpg'
            ]);
      
            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors(), 'success'=>0]);
            }
             
            $user = auth()->user();
            $path = $user->business->logo;

            Storage::delete($path);
            
     
			$base64_image=$request->business_logo;
				
			$image = preg_replace('/^data:image\/\w+;base64,/', '', $base64_image);
			$type = explode(';', $base64_image)[0];
			$type = explode('/', $type)[1]; // png or jpg etc

			// $imageName = str_random(10).'.'.$type;
			$imageName = rand(111111,999999).'.'.$type;
			
			Storage::disk('local')->put('business_logo/'.$imageName, base64_decode($image));

            $profile = BusinessDetail::where('user_id',Auth::user()->id)->update(['logo'=> 'business_logo/'.$imageName]);
            // $user->profile->update([
            //     'avatar' => $imageName
            // ]);

            return response()->json(['status' => true,'msg' => 'Your Business Logo has been changed.']);
            //flash()->success('Success! Profile picture has been changed.');

        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json(['status' => false,'msg' => 'Uploading Logo  failed.']);
            //flash()->error('Error! Changing profile picture failed.');
        }
      
    }

    public function updateProfile(Request $request)
    {
   
        
        $validator=Validator::make($request->all(), [

            //'first_name' => 'required|string|max:255|min:3',
            //'middle_name' => 'required|string|max:255',
            //'last_name' => 'required|string|max:255|min:3',
            //'email' => 'required|string|email|max:255|check_mail|unique:users,email,'.$request->id,
            //'mobile_no' =>'required|digits:10|unique:users,email,'.$request->id,
            'dob' => 'required|before:today',
            'pin_code' => 'required|digits:6',
            'avatar' => 'max:2000|mimes:jpg,jpeg'

        ],
        [
            'dob.required'=>'DOB Required',
            //'dob.date_format'=>'DOB must be DD-MM-YYYY format',
            'dob.before'=>'DOB must be before today',

            'pin_code.digits'=>'Pin Code must be of 6 digits',

            'pin_code.required'=>'Pin Code Required',
            

            'avatar.max'=>'File size is too large to upload(max size 2MB)',
            'avatar.mimes'=>'File type must be in jpg/jpeg',
 
        ]);
        if($validator->fails())
        {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        }
        //dd($request->all());
        try {
            $result = DB::transaction(function () use ($request) {

               
                if($request->avatar!=Null){
                    $avatar_link = $request->file('avatar')
                                    ->store('avatar');
                }
                else{
                    $avatar_link=Profile::where('user_id',Auth::user()->id)->pluck('avatar')->first();
                    //dd($avatar_link);
                    // $avatar_link =$request->old_avatar;
                }

                                
                $profile = Profile::where('user_id',Auth::user()->id)->update([
                     
                    //'mobile_no'         => $request->mobile_no,
                    'gender'         => $request->gender,
                    'maritial_status'         => $request->maritial_status,
                    'religion'         => $request->religion,
                    'dob'         => $request->dob,
                    'address'         => $request->address,
                    'pin_code'         => $request->pin_code,
                    'avatar'         => $avatar_link                   
                    
                ]);
                              
            });
            // return redirect()->back()->with('success','Profile Updated Successfully.');
            return response()->json(['status'=>true, 'msg'=>'Profile Updated Successfully.', 'data'=>1]);
        } catch (\Exception $e) {//dd( $e->getMessage());
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return response()->json(['status'=>false, 'msg'=>$e->getMessage(), 'data'=>0]);
        } 
        
    }

   

    public function hrList(Request $request)
    {
        $this->authorize("access-manage-hr-list");

        $query = User::orderBy('id','DESC')
        ->join('business_details as bd','users.parent_id','=','bd.user_id')
        ->join('profiles as ps','users.id','=','ps.user_id')
        // ->join('individual_user_access as iua','users.id','=','iua.user_id')
        // ->join('user_access_master as uam','iua.access_id','=','uam.id')
        ->select('users.*','bd.business_name as businessName','ps.mobile_no as contactNo','ps.gender as gender','ps.country as country');
        //->get();
        // return response()->json([
        //     'status'=>true,           
        //     'data'=>$query,
        //     'msg'=>1
        // ]);
        //$allacc='';
        if($request->hr_name) {		
			if($request->hr_name!=''){
				$query->where('users.first_name',$request->hr_name);
			}
		}

        if($request->email) {		
			if($request->email!=''){
				$query->where('users.email',strtolower($request->email));
			}
		}

        if($request->mobile_no) {		
			if($request->mobile_no!=''){
                
                $data=Profile::where('mobile_no','=',$request->mobile_no)->orderBy('id','DESC')->first();
             
				$query->where('users.id','=',$data->user_id);
               
			}
		}
        
        if($request->business) {		
			if($request->business!=''){
				$query->where('users.parent_id',$request->business);
			}
		}

        
         if(Auth::user()->account_type=='superadmin')
        {

            $allBusiness=User::where('account_type','business')->get();
            $allHr=$query->where('users.account_type','hr')->get();

            return response()->json([
                'status'=>true,           
                'data'=>$allBusiness,$allHr,
                'msg'=>1
            ]);
        
            // return view('business.hrlist',compact('allHr','allBusiness'));
        }
        else if(Auth::user()->account_type=='business')
        {
            $allHr=$query->where([['users.account_type','hr'],['users.parent_id','=',Auth::user()->id]])->get();
           $allacc=[];
            $acName='';
            foreach($allHr as $hr)
            {
                foreach($hr->userAccess as $usac=> $acc) 
                {
                    $acName=$acc->title;
                    //dd($acName);    
                }
                $allacc[]=$acName;
               // dd($allacc);
            }   
                    

            // return view('business.hrlist',compact('allHr'));
            return response()->json([
                'status'=>true,           
                'data'=>$allHr,$allacc,
                'msg'=>1
            ]);
        }
        else
        {
            return response()->json([
                'status'=>false,           
                'data'=>0,
                'msg'=>'Something was wrong'
            ]);
        }

       
    }

    public function addHr()
    {
        /**
         * For Create HR/User Page View
         * Input:Null
         * Output: All Business, All Access(User/HR)
         */
        //$all_business=User::where('account_type','=','business')->get();
        $all_access=UserAccessMaster::get();
        return response()->json(['status' => true, 'data' =>$all_access, 'msg'=>'success']);
       
    }


    public function saveHrDetails(Request $request)
    {       
         
        $this->authorize("access-manage-hr-list");   

        $validator = Validator::make($request->all(), [
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
            'check_mail'=>'Invalid Email Id',

            'first_name.regex'=>'First Name should be alphabets only.',
            'last_name.regex'=>'Last Name should be alphabets only.',

            'mobile_no.required'=>'Mobile No. Required',
            'mobile_no.numeric'=>'Mobile No. must be in digits',
            'mobile_no.digits_between'=>'Mobile No. should be of 6 to 15 digits',  
            'desg.required'=>'Designation Required',   
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
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
                    'designations'            => $request->desg
                   
                ]);
                // dd(count($request->per));
                if(count($request->per) > 0 )
                {
                    foreach($request->per as $key=>$access)
                    {
                        //dd($access);
                        $user_access = IndividualUserAccess::create([
                            'user_id'           => $user->id,
                            'access_id'         => $access['access_id'],
                            'access_status'         => $access['access_status']
                            
                        ]);
                    }
                }
                // 
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
                                User ID: .$request->email.
                                 
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
            return response()->json(['status' => true, 'msg' => 'HR Added Successfully', 'data'=>1]);

           // return redirect()->route('hr_list')->with('success','HR Added Successfully');
        } catch (\Exception $e) {//dd($e->getMessage());
            //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'msg' => $e->getMessage(), 'data'=>0]);
        }
    }
    /* Login  As Member and back to admin */
    public function login_as_member(Request $request)
	{
        $this->authorize("access-manage-role");
		$admin = auth()->user();
		$user_id = $request->input("user_id");
		$user = User::findOrFail($user_id);
        //dd($admin,$user_id,$user);
		Session::put('adminLogin', true);
		Session::put("adminUserId", $admin->id);
		Auth::login($user);
		$username = $user->first_name;
		return redirect('dashboard')->withSuccess("You are now Logged in as $username");
		return redirect()->route('user.dashboard');
	}	

	public function login_as_admin(){

		if(Session::get('adminLogin')){

            Session::forget('adminLogin');

            $admin_id = Session::get('adminUserId');

            Auth::loginUsingId($admin_id, true);

            return redirect()->route('dashboard');

        }else{

            abort(404);

        }

	}
    
    public function enrollCompanyStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_name'=>'required|regex:/^[a-zA-Z0-9. ]+$/u|min:3',
            'email'=>'required|email|check_mail|rejected',
            'owner_first_name'=>'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'owner_last_name'=>'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'mobile_no'=>'required|numeric|digits_between:6,15',
            'country'=>'required',        
            'gst'=>'required|unique:enroll_companies',
            'pan'=>'required|unique:enroll_companies', 
            'no_of_employee'=>'required|numeric|gt:0',
            'referral_code'=>'nullable',
            // 'g-recaptcha-response'=>'required'            

        ],
        [
            'business_name.required'=>'Business Name Required',
            'business_name.min'=>'Business Name must be minimum 3 letters',
            'business_name.regex'=>'Business Name should be Alpha Numerics only.',

            'owner_first_name.required'=>'Owner First Name Required',
            'owner_first_name.min'=>'Owner First Name must be minimum 3 letter',
            'owner_first_name.regex'=>'Owner First Name should be alphabets only.',

            'owner_last_name.required'=>'Owner Last Name Required',
            'owner_last_name.min'=>'Owner Last Name must be minimum 3 letter',
            'owner_last_name.regex'=>'Owner Last Name should be alphabets only.',

            'mobile_no.required'=>'Mobile No. Required',
            'mobile_no.numeric'=>'Mobile No. must be in digits',
            'mobile_no.digits_between'=>'Mobile No. should be of 6 to 15 digits',
             

            'check_mail'=>'Invalid Email Id',        
            'rejected'=>'Email Exist and Not Rejected', 

            // 'g-recaptcha-response.required'=>'Please Select Captcha',
            'no_of_employee.required'=>'No. Of Employee Required',
            'no_of_employee.numeric'=>'No. Of Employee must be in digits',
            'no_of_employee.gt'=>'No. Of Employee must be greater than 0'

        ]);
  
        if ($validator->fails()) {
			return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        }
        $chk_ref_code=User::where('user_code',$request->referral_code)->pluck('id')->first();
        if($chk_ref_code!=Null)
        {
            // dd(1);
                $enroll=EnrollCompany::insert(['business_name'=>$request->business_name,'email'=>strtolower($request->email),'owner_first_name'=>$request->owner_first_name,'owner_last_name'=>$request->owner_last_name,'mobile_no'=>$request->mobile_no,'gst'=>$request->gst,'pan'=>$request->pan,'country'=>$request->country,'no_of_employee'=>$request->no_of_employee,'referral_code'=>$request->referral_code]);
                if($enroll)
                {
                    try{
                        Mail::to(strtolower($request->email))->queue(new SendEmployerEnrolled());
                    // return $msg=1;
                    return response()->json(['status' => true, 'msg' => 'Enrolled Successfully On Orangeslip!', 'data'=>1]);
                        
                        }
                        catch(\Exception $ex){
                        $stack_trace = $ex->getTraceAsString();
                        $message = $ex->getMessage().$stack_trace;
                    // dd($message);
                        Log::error($message);
                        //return $msg=2;
                        }

                // return redirect('/')->with('success','Thank You for Enroll! We will get back you soon!');
                }
                else
                {
                    return response()->json(['status' => false, 'msg' => 'Something was Wrong!', 'data'=>0]);
                    
                }                                   
    }
    {
        return response()->json(['status' => false, 'msg' => 'Referral Code did not match!', 'data'=>0]);
        
    }   

    }
    public function enrollList(Request $request)
    { 
        $this->authorize("access-manage-business");
        $query = EnrollCompany::orderBy('id','DESC');
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

        if($request->mobile_no) {		
			if($request->mobile_no!=''){
				$query->where('mobile_no','LIKE','%'.$request->mobile_no.'%');
			}
		}
        
        if($request->status) {		
			if($request->status!=''){
				$query->where('status',$request->status);
			}
		}


        $enrolls=$query->get();
         
            // return view('business.enroll_list',compact('enrolls'));
            return response()->json([
                'status'=>true,           
                'data'=>$enrolls,
                'msg'=>1
            ]);
         

    }
    

    

   

    

   
    

    public function editHr(Request $request)
    {
        
        $user=User::where('id','=',$request->id)->first();
       // $all_business=User::where('account_type','=','business')->get();
        $all_access=UserAccessMaster::get();
       
        $user_access=IndividualUserAccess::where('user_id','=',$request->id)->pluck('access_id')->toArray();
        //dd($user_access);
        $data=[
            'user'=>$user,
            //'all_business'=>$all_business,
            //'all_access'=>$all_access,
            'user_access'=>$user_access

        ];
        if(((Auth::user()->account_type!='superadmin') && (Auth::user()->id==$user->parent_id)) || (Auth::user()->account_type=='superadmin') ){
            // return view('business.editHr',compact('user','all_business','all_access','user_access'));
            return response()->json(['status' => true, 'msg' => 'success', 'data'=>$data]);
        }
        else{
            // return abort(403,"You do not have permission for this");
            return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
        }
        
    }


    public function updateHr(Request $request)
    {
        $role=Auth::user()->account_type;
        if($role=='superadmin' || $role=='business')
        {
        $validator = Validator::make($request->all(), [
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
            'first_name.regex'=>'First Name Should be alphabets only.',
            'last_name.regex'=>'Last Name should be alphabets only.',
            'mobile_no.required'=>'Mobile No. Required',
            'mobile_no.numeric'=>'Mobile No. must be in digits',
            'mobile_no.digits_between'=>'Mobile No. should be of 6 to 15 digits'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        // dd($request->per);
        try {
            $result = DB::transaction(function () use ($request) {
                $userAccess=IndividualUserAccess::where('user_id','=',$request->id)->get();
               // dd($userAccess);
                $user = User::where('id','=',$request->id)->Update(['first_name' => $request->first_name, 'last_name' => $request->last_name,'email' => strtolower($request->email) ]);

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
                            'user_id'           => $access['user_id'],
                            'access_id'         => $access['access_id'],
                            'access_status'         => $access['access_status']
                            
                        ]);
                    }
                }
                
             
            });
            // return redirect()->route('hr_list')->with('success','User Updated Successfully');
            return response()->json(['status' => true, 'msg' => 'HR details Updated Successfully', 'data'=>1]);
        } catch (\Exception $e) {//dd($e->getMessage());
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'msg' => $e->getMessage(), 'data'=>0]);
        }
    }
    else{
        return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
    }
    }

    public function packageSubscription(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
           'pack_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }

            $curPack=DB::table('subscriptions')->where([['business_id',Auth::user()->id],['status','=',1],['remain_qty','>',0],['expire_date','>',Carbon::now()->toDateTimeString()]])->first();
            if($curPack){
                return response()->json(['status' => false, 'msg' => 'Already you have a package.', 'data'=>0]);
            }
                 
                $user=User::where('id','=',Auth::user()->id)->first();

                $packDetails=DB::table('packages')->where('id',$request->pack_id)->first();
                 

                if($user->balance < $packDetails->offer_price){
                    return response()->json([
                        'status'=>false,           
                        'data'=>0,
                        'msg'=>'Insufficiant wallet balance!'
                    ]);
                }
 
            try {
                    $result = DB::transaction(function () use ($request, $user,$packDetails) {
                    $bytes = random_bytes(40);
                    $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);

                   
                    $exp_date=Carbon::now()->addDays($packDetails->duration)->toDateTimeString();
                    // dd($exp_date);
                    $subscription=DB::table('subscriptions')->insert(['pack_id'=>$request->pack_id,'business_id'=>Auth::user()->id,'purchase_price'=>$packDetails->offer_price,'expire_date'=>$exp_date,'used_qty'=>0,'remain_qty'=>$packDetails->quantity]);

                    $transaction=DB::table('transactions')->insert(['user_id'=>Auth::user()->id,'currency_id'=>1,'type'=>'Debit','source'=>'Subscription of Package','description'=>"Package Details",'amount'=>$packDetails->offer_price,'updated_balance'=>$user->balance - $packDetails->offer_price,'status'=>1,'transaction_id'=>$transaction_id]);

                    $update_balance_to_user=User::where('id','=',Auth::user()->id)->update(['balance'=>$user->balance - $packDetails->offer_price,'updated_at'=>date('Y-m-d H:i:s')]);

                });
                return response()->json([
                    'status'=>true,           
                    'data'=>1,
                    'msg'=>'Package Purchased Successfully!'
                ]);
                //return redirect()->route('business.index')->with('success','Business Added Successfully');
            } catch (\Exception $e) {//dd( $e->getMessage());
               // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                return response()->json([
                    'status'=>true,           
                    'data'=>0,
                    'msg'=> $e->getMessage()
                ]);
            }
                    
        

    }

    public function subscribedPackages(Request $request)
    {      
        $subscribedPack=DB::table('subscriptions')
        ->join('packages as pack','subscriptions.pack_id','=','pack.id')
        ->where('business_id','=',Auth::user()->id)->get();
        return response()->json([
            'status'=>true,           
            'data'=>$subscribedPack,
            'msg'=> 'ok'
        ]);
    }
    public function affiliateLinks(Request $request)
    {
        //dd(1);
        $user_code=Auth::user()->user_code;
        if($user_code!=NULL){
            $affHistory=User::where('referral_code','=',Auth::user()->user_code)->get();
            return response()->json([
                'status'=>true,           
                'data'=>$affHistory,
                'msg'=> 'ok'
            ]);
        }   
        else{
            return response()->json([
                'status'=>false,           
                'data'=>0,
                'msg'=> 'No User Code Found'
            ]);
        }    
    }


    public function registrationHr(Request $request)
    {       
         
        

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            // 'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'email' => 'required|email|check_mail|max:255|unique:users',
            'mobile_no' => 'required|digits_between:6,15|numeric',
            // 'dob' => 'required|date_format:Y-m-d',
            'gender' => 'required|string',
            // 'owner_first_name' => 'required|string|max:255',
            // 'owner_last_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
             

        ],
        [
            'check_mail'=>'Invalid Email Id',

            'first_name.regex'=>'First Name should be alphabets only.',
            'last_name.regex'=>'Last Name should be alphabets only.',

            'mobile_no.required'=>'Mobile No. Required',
            'mobile_no.numeric'=>'Mobile No. must be in digits',
            'mobile_no.digits_between'=>'Mobile No. should be of 6 to 15 digits',  
            'desg.required'=>'Designation Required',   
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        // dd($request->per);
        try {
            $result = DB::transaction(function () use ($request) {
                 
                $user = User::create([
                            'first_name' => strtolower($request->name),                            
                            'email' => strtolower($request->email),
                            'password' => $request->password,
                            'account_type' => 'hr',
                            'parent_id'=>$request->parent_id
                        ]);
                       
                $profile = Profile::create([
                    'user_id'           => $user->id,
                    'mobile_no'         => $request->mobile_no,
                    'country'         => $request->country,
                    'gender'            => $request->gender,
                    'designations'            => $request->desg
                   
                ]);
                // dd(count($request->per));
                if(count($request->per) > 0 )
                {
                    foreach($request->per as $key=>$access)
                    {
                        //dd($access);
                        $user_access = IndividualUserAccess::create([
                            'user_id'           => $user->id,
                            'access_id'         => $access['access_id'],
                            'access_status'         => $access['access_status']
                            
                        ]);
                    }
                }
                // 
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
                    

                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML

                        $mailContent = "<body style='background: #f6f6f6; color:#222; padding: 10px 10px; font-size: 13px; line-height: 22px;'>


  
                        <div style='max-width: 700px; font-family: Poppins; z-index: 11; background: #fff; margin: 10px auto; position: relative;'>
                           
                            <div style='padding: 15px 0px; border-bottom: 1px solid #ccc; text-align: center;'>
                                <img style='width: 200px;' src='https://orangeslip.com/new/images/logo.png' alt='' />
                            </div>
                            <div style='padding: 50px 20px;'>
                                <h4 style='text-align: left; margin: 0px;'>Hi $request->name </h4>
                               
                                
                                <p style='margin-bottom: 10px;'>
                                Thank You. The registration of your HR profile with this account was successful. For login, kindly <a href='https://orangeslip.com/login'> click this link</a>. The login credentials for your profile are provided here.
                                </p>
                                <p style='margin-bottom: 10px;'>
                                User ID: .$request->email.
                                 
                                </p>
                                <p style='margin-bottom: 10px;'>                                 
                                Password: $request->password                             
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
            return response()->json(['status' => true, 'msg' => 'HR Registration Successfully', 'data'=>1]);

           // return redirect()->route('hr_list')->with('success','HR Added Successfully');
        } catch (\Exception $e) {//dd($e->getMessage());
            //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'msg' => $e->getMessage(), 'data'=>0]);
        }
    }
    public function businessList()
    {
        // dd(1);
        $allBusiness=BusinessDetail::orderBy('business_name','ASC')->get();      
         
            return response()->json([
                'status'=>true,           
                'data'=>$allBusiness,
                'msg'=> 1
            ]);
         
    }
    

    
}

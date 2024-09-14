<?php
namespace App\Http\Controllers\API;

use App\Libs\CommonHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Trigger;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Repository\User\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Laravel\Passport\HasApiTokens;
use Session;
use App\Models\Profile;
use App\Models\Otp;
use App\Models\CandidateDetail;
use App\Models\IndividualUserAccess;
use Log; 
use Bouncer;
use Silber\Bouncer\Database\Role;  
use App\Mail\Welcome;
 

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */

	protected $repository;
	use SendsPasswordResetEmails;

    public function __construct(UserRepository $userRepository)
    {
		$this->repository = $userRepository;
        $this->middleware('guest')->only(['getResetToken','register','login','reset','sendOtpPhone']);
      
    }

    public function sendOtpPhone(Request $request){
        //dd(1);
        // session()->forget('phoneOtp');
        // $validator = Validator::make($request->all(),[
        //     'phone'=>'required|numeric|digits:10'           
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        //     }
        // $phone_otp=rand(111111,999999);
        // //dd($phone_otp);
        // Session::put('phoneOtp', $phone_otp);
        // ////send to phone
        // // return $phone_otp;
        // return response()->json([
        //     'status'=>true,           
        //     'data'=>$phone_otp,
        //     'msg'=>'OTP send Successfully!'
        // ]);

        $validator = Validator::make($request->all(), 
            [
                'phone'=>'required|numeric|digits:10'   
            ]
        ); 
       
        if ($validator->fails()) {
			
			return response()->json([
                'status' => false, 
                'errors' => $validator->errors()
            ]);
        }
        
        $otp = rand(100000,999999);
       // dd($otp);
    //  Log::info("otp = ".$otp);
        //$save_otp=DB::table('otp')->insert(['mobile_or_email'=>$request->phone,'otp'=>$otp, 'otp_creation_time'=>Carbon::now()]);
        
        $user = DB::table('otp')->where('mobile_or_email','=',$request->phone)
                        // ->where('otp_creation_time','<',Carbon::now()->subMinute()->toDateTimeString())
                        // ->orWhereNull('otp_creation_time')
                        ->first();
         //dd($user->otp_creation_time, Carbon::now()->subMinute()->toDateTimeString() );
        if($user){
            DB::table('otp')->where('mobile_or_email','=',$request->phone)->update(['otp' => $otp,'otp_creation_time'=>Carbon::now()->toDateTimeString()]);
        
          //  Mail::to($user->email)->send(new SendOtp($otp));

            return response(['status' => true, 'message' => 'OTP sent successfully','data'=>$otp]);
        }
        else{
            $save_otp=DB::table('otp')->insert(['mobile_or_email'=>$request->phone,'otp'=>$otp, 'otp_creation_time'=>Carbon::now()->toDateTimeString()]);
            return response(['status' => true, 'message' => 'OTP sent successfully','data'=>$otp]);
            //return response(["status" => 401, 'message' => 'Invalid Request']);
        }

    }

    public function register(Request $request)
    { 
		
		//dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'email' => 'required|email|max:255|unique:users|check_mail',
            'phone' => 'required|numeric|digits:10',
            'password' => 'required|string|min:8|confirmed'
            //'otp'=>'required|numeric|digits:6'
        ],
        [
            'check_mail'=>'Invalid Email Id',
            'check_phone_otp'=>'OTP Mismatched',
            'name.regex'=>'Fill the Name with alphabets only.',
        ]);
  
        if ($validator->fails()) {
        return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        }
        
                $otp_db  = DB::table('otp')->where([
                    ['mobile_or_email','=',$request->phone],
                    ['otp','=',$request->otp],
                    ['otp_creation_time','>',Carbon::now()->subMinutes(5)->toDateTimeString()]
                ])->first();
               // dd($otp_db, Carbon::now()->subMinutes(5)->toDateTimeString());

            // if($otp_db)
            // {
                
                //$sss= DB::table('otp')->where('mobile_or_email','=',$request->phone)->update(['otp' => Null,'otp_creation_time' => Null]);
                

                try{
                    
                    $result = DB::transaction(function () use ($request) {
                    
                        $user=User::create([
                            'first_name' => strtolower($request->name),
                            'email' => strtolower($request->email),
                            'password' => Hash::make($request->password),
                            'verification_token' => Str::random(60),
                            'account_type' => 'candidate'
                        ]);
                        $profile = Profile::create([
                            'user_id'           => $user->id,
                            'mobile_no'         => $request->phone
                            
                        ]);
                        $candidate=CandidateDetail::create([
                                    'candidate_code'=>"TCV".$user->id,
                                    'user_id'=>$user->id,
                                    'name'=>strtolower($request->name),
                                    'email'=>strtolower($request->email), 
                                    'phone'=>$request->phone, 
                                    'added_by'=>0,
                                    'business_id'=>0,
                                    'hr_id'=>0
                                ]);
            
                        $token=$user->verification_token;
            
                        // Mail::to($mailData['email'])->queue(new Welcome($token));
                        Mail::to(strtolower($request->email))->queue(new Welcome($token));
            
                        $helper = new CommonHelper;
                        $result = $helper->saveAssignedRole($user->id,$user->account_type);  
            
                });  
                //dd($request->all());
                //    return $status=1;
                // return redirect('/login')->with('success','Your account has been successfully registered. Please verify your email ');
                    return response()->json([
                        'status'=>true,           
                        'data'=>1,
                        'msg'=>'Your account has been successfully registered. Please verify your email.'
                    ]);
                
                } catch (\Exception $e) {//dd($e->getMessage());
                        // return $status=2;
                        //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                        return response()->json([
                            'status'=>false,           
                            'data'=>2,
                            'msg'=>$e->getMessage()
                        ]);
                    }
            // }
            // else
            // {
            //     return response()->json([
            //         'status'=>false,           
            //         'data'=>1,
            //         'msg'=>'Invalid Otp'
            //     ]);
            // }
        
    }
  
	public function login(Request $request)
    {    
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required',          
            'account_type' => 'required',          
        
        ]);
  
        if ($validator->fails()) {
			return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        }
       
		if(isset($request->password)){
			$credentials = request(['email', 'password','account_type']);  //dd($credentials);
			if(!Auth::attempt($credentials)){
				return response()->json([
                    'status' => false,
					'type' => 'Unauthorized',
					'msg'=> 'Your login credentials are incorrect! Please try again.'
				], 401);
			}
		}else{
			return response()->json([
                    'status' => false,
					'type' => 'error',
					'msg'=> 'Password is not correct'
				], 401);
		}
        //dd(auth()->user());
		 		
            // if((auth()->user()->account_type=='candidate') && (auth()->user()->is_email_verified!=1))  //02052023 commented
            // {
            //     $token=auth()->user()->verification_token;  

            //     auth()->logout(); 

                      
            //     // Mail::to($mailData['email'])->queue(new Welcome($token));
            //     Mail::to($request->email)->queue(new Welcome($token));
            //     return response()->json([
            //                 'status' => false,
            //                 'type' => 'Unverified',
            //                 'msg'=> 'Your account has not been verified yet. We have resent the verification link to your mail. Please check your mail and verify it!'
            //             ], 401);
                
            // }

            if(auth()->user()->status!=1)
            {
                auth()->logout();
                return response()->json([
                    'status' => false,
                    'type' => 'Inactive',
                    'msg'=> 'Your account is Inactive. Please contact to Administration!'
                ], 401);
            }
        
		
            $credentials = request(['email', 'password','account_type']);  //dd($credentials);
			if(auth()->attempt($credentials)){
                $token=auth()->user()->createToken('Token');
                /** lOGIN iNFO */

                $ip= $_SERVER['SERVER_ADDR'];
                $mytime = Carbon::now();
                $mytime->toDateTimeString();
                DB::table('login_info')->insert(['user_id'=>Auth::user()->id,'ip_address'=>$ip,'app_type'=>'apk']);
                User::where('id',Auth::user()->id)->update(['last_login'=>$mytime]);
                /** lOGIN iNFO END */
				return response()->json([
                    'status' => true,
					'token' => $token,
					'msg'=> 'Login Success'
				], 200);
			}
            else{
                return response()->json([
                    'status' => true,
					'data' => 0,
					'msg'=> 'Something was wrong!'
				], 401);
            }
      
         
    }
    
	public function logout(Request $request)
    {
        //dd(1);
        $token=$request->user()->token();
        $token->revoke();
       
        return response()->json([
            'status' => true,
            'msg' => 'Successfully logged out',
			'data' => 1
        ]);
    }
    
    public function user()
    {
        

		$user=auth()->user();
		 $profile=$user->profile;
		 $business=$user->business;
        // $data=[
        //     'user'=>$user,
        //     'profile'=>$profile,
        // ];
        $user_access=IndividualUserAccess::where([['user_id','=',$user->id],['access_status','=',1]])->get();
        $user->user_access=$user_access;
		if(!$user){
			return response()->json(['status' => false,'msg'=>'No User Found', 'data'=>0]);
		}
		
        return response()->json(['status' => true,'data'=>$user]);
    }
    
    
    public function getResetToken(Request $request)
    {
        
         $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',         
        
        ]);
  
        if ($validator->fails()) {
			return response()->json(['status' => false, 'msg' =>'validation error', 'errors'=> $validator->errors()]);
        }
        
        
        $user=User::where('email',strtolower($request->email))->first(); 
        //dd($user);
        if(!$user){
			return response()->json(['status' => false,'error'=>'User not found']);
		}
       
        /*$request->request->add(['email' => $user->email]);
        $response = $this->broker()->sendResetLink(
            'email'=>$request->only('email')
        );
        
        if ($response === Password::RESET_LINK_SENT) {
			return response()->json(['msg'=>trans($response),'status'=>'Success']);
		}else{
			return response()->json(['msg'=>trans($response),'status'=>'Failed']);
		}
        
        */
        try {
			
			$token = hash('sha256', Str::random(30));
			
			DB::table('password_resets')->where('email', $user->email)->delete();			
			
			DB::table('password_resets')->insert(
				['email' => $user->email, 'token' => Hash::make($token), 'created_at' => date("Y-m-d H:i:s"),]
			);		
			
			$this->email = $user->email; 
            $html='Paswword Reset Link: '.route('password.reset', $token);

			//dd(['username'=>$user->email,'link' => route('password.reset', $token)]);

			// Mail::send('emails.forgot_password', ['username'=>$user->email,'link' => route('password.reset', $token)], function ($message) {
			// 	$message->subject('Reset Password');
			// 	$message->to($this->email);
				
			// });

			Mail::raw($html, function ($message) {
                $message
                  ->to($this->email)
                  ->subject('Reset Password');
              });

			return response()->json(['msg'=>trans(Password::RESET_LINK_SENT),'status'=>true]);
		
		}catch(\Exception $exception){
         
            return response()->json(['status' => false,'error' => 'Server Error occurred.']);
            			
        }

    }
    

  

}

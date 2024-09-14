<?php

namespace App\Http\Controllers\Auth;
use App\Libs\CommonHelper;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Profile;
use App\Models\CandidateDetail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
Use DB;
use Illuminate\Http\Request;
Use Session; 
use Bouncer;
use Silber\Bouncer\Database\Role; 
use Illuminate\Support\Facades\Mail;
use App\Mail\Welcome;
use Illuminate\Support\Str;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required','numeric','digits:10'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'account_type' => 'superadmin'
        ]);
    }
    

    public function candidateSignup(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
            'name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255|min:3',
            'email' => 'required|email|max:255|unique:users|check_mail',
            'phone' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
           // 'otp'=>'required|numeric|digits:6|check_phoneOtp'
        ],
        [
            'check_mail'=>'Invalid Email Id',
            //'check_phone_otp'=>'Wrong OTP',
            'name.regex'=>'Enter alphabets only.',
        ]);
        //dd($request->all());
        try{
             
            $result = DB::transaction(function () use ($request) {
               
                $user=User::create([
                    'first_name' => $request->name,
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
                            'candidate_code'=>"REC".$user->id,
                            'user_id'=>$user->id,
                            'name'=>$request->name,
                            'email'=>strtolower($request->email), 
                            'phone'=>$request->phone, 
                            'added_by'=>0,
                            'business_id'=>0,
                            'hr_id'=>0
                        ]);
    
                $token=$user->verification_token;
    
                // Mail::to($mailData['email'])->queue(new Welcome($token));
                Mail::to($request->email)->queue(new Welcome($token));
    
                $helper = new CommonHelper;
                $result = $helper->saveAssignedRole($user->id,$user->account_type);  
    
           });  
           //dd($request->all());
           return $status=1;
         // return redirect('/login')->with('success','Your account has been successfully registered. Please verify your email ');
         
         
        } catch (\Exception $e) {dd($e->getMessage());
                return $status=2;
                 //return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
        
    }
    
    public function sendOtpPhone(Request $request){
        //dd(1);
        session()->forget('phoneOtp');
        $this->validate($request,[
            'phone'=>'required|numeric|digits:10'           
        ]);
        
        $phone_otp=rand(111111,999999);
        //dd($phone_otp);
        Session::put('phoneOtp', $phone_otp);
        ////send to phone
        return $phone_otp;

    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\Welcome;
use Carbon\Carbon;
use DB;
use Auth;
Use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Session\TokenMismatchException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
       
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
     $check_email = User::where('email',$request->email)->first();
    if(!$check_email){
        return redirect("login")->withError('Oppes! You have entered invalid email');
    }
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
  
            return redirect()->route('dashboard');
        }
    
        return redirect("login")->withError('Oppes! You have entered invalid password');
    }


    
    // public function authenticate(Request $request)
    // {
    //     dd(1);
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);
 
    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();
 
    //         return redirect()->intended('dashboard');
    //     }
 
    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ]);
    // }
    /*
    protected function authenticated(Request $request, $user)
    {
        $ip= $_SERVER['SERVER_ADDR'];
       $mytime = Carbon::now();
        $mytime->toDateTimeString();
       DB::table('login_info')->insert(['user_id'=>Auth::user()->id,'ip_address'=>$ip,'app_type'=>'web']);
       User::where('id',Auth::user()->id)->update(['last_login'=>$mytime]);
        //dd($user);
        //dd($user->status);
        if($user->status==2)
        {
            auth()->logout();
            return redirect('/login')->with('error','Your account has been Deactive. Please contact to Administrator!');
        }

        if($user->account_type=='candidate')
        {
            if($user->is_email_verified!=1)
            {
                auth()->logout(); 

                $token=$user->verification_token;        
                // Mail::to($mailData['email'])->queue(new Welcome($token));
                Mail::to($user->email)->queue(new Welcome($token));
    
                return redirect('/login')->with('error','Your account has not been verified yet. We have resent the verification link to your mail. Please check your mail and verify it!');
            }
            else{
                return redirect()->route('dashboard');
            }
        }      

        if($user->account_type=='business' || $user->account_type=='hr')
        {
            if($user->change_password!=1)
            {
                    
                return redirect('/change_password');
            }
            else{
                return redirect()->route('dashboard');
            }
        }
        
    }
*/

}

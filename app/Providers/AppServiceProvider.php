<?php

namespace App\Providers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Session;
use App\Models\EnrollCompany;
use App\Models\User;
use Laravel\Passport\Passport;
use DB;
use Illuminate\Support\Facades\Auth;
use Config;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        //Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
    //     $auth = $this->app['auth'];
    //     dd($auth->user()); // OK });
    //    // dd($auth->user()); // NULL
//dd(auth()->user()->id);
        // $mailsetting=DB::table('business_mail_server_details')->where('business_id',1)->first();
        // if($mailsetting){
        //     $data = [                 
        //         'driver' => 'smtp',
        //         'host' => $mailsetting->mail_host,
        //         'port' => $mailsetting->mail_port,
        //         'encryption' =>env('MAIL_ENCRYPTION', 'tls'),
        //         'username' => $mailsetting->mail_username,
        //         'password' => $mailsetting->mail_password,               
        //         'from'=>[
        //             'address'=>$mailsetting->from_address,
        //             'name'=>$mailsetting->from_name                     
        //         ]
        //     ];
        //     Config::set('mail',$data);
        // }

        //
        Validator::extend('check_mail', function ($attribute, $value, $parameters, $validator) {
            //dd($value);
            $pattern = "/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
            $res=preg_match ($pattern, $value);
            //dd($res);
            // if(str_contains($value, '+')){
            //     return false;
            // }
            if($res==0){
                return false;
            }
            
            return true;
        });

        Validator::extend('check_phoneOtp', function ($attribute, $value, $parameters, $validator) {
            //dd($value);
            if((Session::get('phoneOtp'))!=$value){
                return false;
            }
            
            return true;
        });

        Validator::extend('rejected', function ($attribute, $value, $parameters, $validator) {
            //dd($value);
            $ifExist=EnrollCompany::where('email','=',$value)->orderby('id','DESC')->first();


            if($ifExist==Null){

                return true;
            }
            else{
                if($ifExist->status==4)
                {
                    return true;
                }
                else{
                    return false;
                }
            }
            
            
        });

        Validator::extend('base64img', function ($attribute, $value, $parameters, $validator) {
            if((preg_match('/^data:image\/(\w+);base64,/', $value))) {
				return true;								
			}
			return false;
            
        });        
        
        Validator::extend('is_png_jpg',function($attribute, $value, $params, $validator) {
			
			$type = explode(';', $value)[0];
			$type = explode(':', $type)[1];
	
			if (($type == 'image/png') || ($type == 'image/jpeg') || ($type == 'image/jpg')) {
				return true;
			}
			return false;
			
		});
       
    }

}

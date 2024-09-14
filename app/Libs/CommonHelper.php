<?php
namespace App\Libs;

use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\BusinessDetail;
use App\Models\CandidateDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;
use Bouncer;

class CommonHelper{  
    
	
	
    public function __construct()
    {
        
    }

    public function saveAssignedRole($user_id, $type){
        //dd(1);
        // $this->authorize("access-manage-member-role");
		
		// $this->validate($request, [
        //     'user_id' => 'required|exists:users,id',
        //     'type' => 'required'
        // ]);
		
		$id = $user_id;
		
		$user = User::where('id', $id)->firstOrFail();
		
		Bouncer::retract($user->account_type)->from($user);
		
		$user->account_type = $type;
		$user->save();		

		if($user->account_type == 'superadmin'){
            //Bouncer::assign($request->input('type'))->to($user);			
			$user->administrator = 1;			
		}else{
            Bouncer::assign($user->account_type)->to($user);
			$user->administrator = 0;
		}
		
		$user->save();
		
		//flash()->success("Member Role has beeen successfully changed");	
				
		//return redirect(route('profile.display',$user->username));
       //return redirect()->back();
    }

	public function chkEmail($email,$business){
		$chk_email=CandidateDetail::where([['email','=',$email],['business_id','=',$business]])->first();
		if($chk_email==Null){
			return $flag=0;
		}
          else{
			return $flag=$chk_email->id;
		  }
	}
	
    
}

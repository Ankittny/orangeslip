<?php

namespace App\Http\Controllers\API;

use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use App\Models\CandidateDetail;
use App\Models\CandidateEducationDetail;
use App\Models\CandidateProfessionalDetail;
use App\Models\CandidateOtherDetail;
use App\Models\Verification;
use App\Models\VerificationStaff;
use App\Models\Transaction;
use App\Models\UserAccessMaster;
use App\Models\User;
use DB;
use Auth;

 
class VerificationController extends Controller
{
    public function verificationTypes(Request $request)
    {
        /**
         * Verification Request Page View
         * input:Candidate - base64_encode(id)
         * Output:candidates,verification_types
         */
        
       

        $verification_types=DB::table('verification_types')->get();
        return response()->json(['status' => true, 'msg' => 'Success', 'data'=>$verification_types]);
            
      

    }

    public function store(Request $request)
    {
        $this->authorize("access-manage-verification");
        
        $user=Auth::user();
        
        if(!$user){
            return response()->json(['status' => false, 'msg' => 'User Not Found', 'data'=>0]);
        }

        $candidate_id=$request->id;        
        $hr_id=$request->hr_id;

        $canDetails=CandidateDetail::where('id',$request->id)->first();
        $hrDetails=User::where('id',$request->hr_id)->first();
        
        if($canDetails->business_id!=$hrDetails->parent_id){
            return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);

        }
        
       
        //dd(count($request->v_type));
        
            if(count($request->v_type) > 0 )
            {
               

                $cnt=0;
                $kyc_type=[];
                $per_err=[];

                foreach($request->v_type as $access)
                {
                    $v_id=UserAccessMaster::where('name','=',$access)->pluck('id')->first();       
                    $candidate_controller = new CandidateController;             
                    $status=$candidate_controller->chkUserAccess($hr_id,$v_id);

                    if($status!=0)
                    {
                        // dd($user->id);
                        $user_old_balance=User::where('id','=',$user->id)->pluck('balance')->first(); 
                        $amount=DB::table('verification_types')->where('name','=',$access)->pluck('amount')->first();
                        $bytes = random_bytes(40);
                        $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);
                        if($amount <= $user_old_balance)
                        {
                                 
                            $business_id=User::where('id','=',$hr_id)->pluck('parent_id')->first();                                
                            
                            $transaction = Transaction::insert([
                                'user_id' => $user->id,
                                'type' => 'Debit',
                                'source' => 'Verification Request',
                                'description' => $access." Verification Request",
                                'amount' => $amount,
                                'updated_balance' =>(float)($user_old_balance - $amount),
                                'status' => 1,
                                'transaction_id' => $transaction_id                            
                            ]);   
                            //dd($access);
                            $verification = Verification::insert([
                                'candidate_id'=>$candidate_id,
                                'hr_id'=>$hr_id,
                                'verification_type'=>$access,
                                'business_id'=>$business_id
                            ]);
                            
                            User::where('id','=',$user->id)
                            ->update([
                                'balance'=>(float)($user_old_balance - $amount)                                            
                            ]);  
                        
                            $cnt++;
                            $kyc_type[]=$access;
                            // return response()->json(['status' => true, 'msg' => 'Verification Request Submited Successfully.', 'data'=>1]);
                                    
                        }
                        else
                        {
                            return response()->json(['status' => false, 'msg' => "$cnt Request Submitted. (Insufficient Wallet Balance)", 'data'=>0]);
                        }
                    }
                    else
                    {
                        $per_err[]=$access;
                        // return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);

                    }
                    
                    
                }
                if(count($kyc_type) > 0){
                    return response()->json(['status' => true, 'msg' => implode($kyc_type,',').' Verification Request Submitted Successfully', 'data'=>1]);
                }

                
                if(count($per_err) > 0){
                    return response()->json(['status' => false, 'msg' => implode($per_err,',').' Permission Denied', 'data'=>0]);
                }

                if((count($kyc_type) <= 0) && (count($per_err) <= 0))
                {
                    return response()->json(['status' => false, 'msg' => ' Something was wrong', 'data'=>0]);
                }
            } 
            else
            {
                return response()->json(['status' => false, 'msg' => 'Please Select Verification Type', 'data'=>0]);
            }  
       
    }


    
    public function verificationList(Request $request)
    {
        /**
         * Verificatin Request List Page
         * input:ver_type,staff,status
         * Output:verifications,verification_types,staff
         */
        $this->authorize("access-manage-verification");
        // $query=Verification::orderBy('id','DESC');

        $query=DB::table('verifications as v')
        ->leftjoin('candidate_details as cd', 'v.candidate_id', '=', 'cd.id')
        ->leftjoin('users as hr', 'v.hr_id', '=', 'hr.id')
        ->leftjoin('users as staff', 'v.staff_id', '=', 'staff.id')
        
        ->select('v.*', 'cd.name as candidateName','hr.first_name as userName','staff.first_name as staffName')
        ->orderBy('id','DESC');


        
        
        if($request->ver_type) {		
			if($request->ver_type!=''){
				$query->where('v.verification_type',$request->ver_type);
			}
		}
        if($request->staff) {		
			if($request->staff!=''){
				$query->where('v.staff_id',$request->staff);
			}
		}
        if($request->status) {		
			if($request->status!=''){
				$query->where('v.status',$request->status);
			}
		}


        //if((Auth::user()->account_type!='hr') && (Auth::user()->account_type!='business'))
        if(Auth::user()->account_type=='superadmin')
        {
           
            $verifications=$query->get();
            $staff=User::where('parent_id','=',Auth::user()->id)->get();
            // return view('admin.verification.index', compact('verifications','verification_types','staff'));
            $data=[
                'verifications'=>$verifications,
               
                'staff'=>$staff
            ];
            return response()->json([
                'status'=>true,           
                'data'=>$data,
                'success'=>1
            ]);
        }
        else if(Auth::user()->account_type=='business')
        {
           
            $hr=User::where('parent_id',Auth::user()->id)->pluck('id')->toArray();
            //dd($hr);
            $verifications=$query->whereIn('v.hr_id',$hr)->get();
            // return view('admin.verification.index',compact('verifications','verification_types'));
            $data=[
                'verifications'=>$verifications
                
               
            ];
            return response()->json([
                'status'=>true,           
                'data'=>$data,
                'success'=>1
            ]);
        }
         
        else if(Auth::user()->account_type=='hr')
        {
         
            $verifications=$query->where('v.hr_id','=',Auth::user()->id)->get();
            // return view('admin.verification.index',compact('verifications','verification_types'));
            $data=[
                'verifications'=>$verifications
                
               
            ];
            return response()->json([
                'status'=>true,           
                'data'=>$data,
                'success'=>1
            ]);
        }

        else if(Auth::user()->account_type=='verification staff')
        {
          
            $verifications=$query->where('v.staff_id','=',Auth::user()->id)->get();
            // return view('admin.verification.index',compact('verifications','verification_types'));
            $data=[
                'verifications'=>$verifications
                
            ];
            return response()->json([
                'status'=>true,           
                'data'=>$data,
                'success'=>1
            ]);
        }
        else if(Auth::user()->account_type=='verification head')
        {
            $staff=User::where('parent_id','=',Auth::user()->id)->get();
           
            $data=VerificationStaff::where('user_id','=',Auth::user()->id)->first();
             //dd($data);
            $verifications=$query->where('v.verification_type','LIKE','%'.$data->department.'%')
            ->get();
            // return view('admin.verification.index',compact('verifications','verification_types','staff'));
            $data=[
                'verifications'=>$verifications,
                
                'staff'=>$staff
            ];
            return response()->json([
                'status'=>true,           
                'data'=>$data,
                'success'=>1
            ]);
        }
        else{
            // return abort(403,"You do not have permission for this");
            return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
        }

       // $staff=User::where('account_type','=','verification staff')->get();
       
        
    }


    public function getStaff(Request $request)
    {
        
        $verifications=Verification::where('id','=',$request->verification_id)->pluck('verification_type')->first();
        //dd($verifications);


        $staff= DB::table('verification_staffs')
            ->join('users', 'users.id', '=', 'verification_staffs.user_id')
            ->where('verification_staffs.department','LIKE','%'.$verifications.'%')             
            ->get();

        //dd($staff);
            
        //$staff=VerificationStaff::where('department','LIKE','%'.$verifications.'%')->get();
        //dd($staff);
        // return $staff;
        return response()->json([
            'status'=>true,           
            'data'=>$staff,
            'success'=>1
        ]);


    }


}

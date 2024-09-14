<?php

namespace App\Http\Controllers;

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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

 
class VerificationController extends Controller
{
    public function create(Request $request)
    {
        /**
         * Verification Request Page View
         * input:Candidate - base64_encode(id)
         * Output:candidates,verification_types
         */
         
        $this->authorize("access-manage-verification");
        $role=Auth::user()->account_type;
        if($role=='hr'){
            $user_id=Auth::user()->id;
       
            $parent_id=Auth::user()->parent_id;
            $allHR=User::where('id','=',$user_id)->get();
        }
        else if($role=='business'){
            $user_id=Auth::user()->id;
            
            $parent_id=Auth::user()->id;
            $allHR=User::where('parent_id','=',$parent_id)->get();
        }

        if($request->candidate){
            $candidates=CandidateDetail::where('id','=',$request->candidate)->first();
        }
               
        $verification_types=DB::table('verification_types')->get();
        $candidates=CandidateDetail::where('business_id','=',$parent_id)->get();

         
            return view('admin.verification.create',compact('candidates','verification_types','allHR'));  
       
      

    }

    public function store(Request $request)
    {
        /**
         * Store Verification Request
         * input:candidate - base64_encode(id),v_type
         * output: Redirect with success/error.
         */
         
        // dd($request->all());
        $this->authorize("access-manage-verification");
        
        $user=Auth::user();
        
        if(!$user){
            // return response()->json(['status' => false, 'msg' => 'User Not Found', 'data'=>0]);
            return redirect()->back()->with('error','HR Not Found.');
        }

        $candidate_id=$request->candidate;        
        $hr_id=$request->hr_id;

        $canDetails=CandidateDetail::where('id',$candidate_id)->first();
        $hrDetails=User::where('id',$request->hr_id)->first();
       
        if($canDetails->business_id!=$hrDetails->parent_id){
            // return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
            return redirect()->back()->with('error','You do not have permission for this.');

        }  
            // dd(count($request->v_type));
        
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
                            // return response()->json(['status' => false, 'msg' => "$cnt Request Submitted. (Insufficient Wallet Balance)", 'data'=>0]);
                            return redirect()->back()->with('error',"$cnt Request Submitted. (Insufficient Wallet Balance)");

                        }
                    }
                    else
                    {
                        $per_err[]=$access;
                        // return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);

                    }
                    
                    
                }
                //dd($kyc_type);
                if(count($kyc_type) > 0){
                    // return response()->json(['status' => true, 'msg' => implode($kyc_type,',').' Verification Request Submitted Successfully', 'data'=>1]);
                    $msg=implode(",",$kyc_type).' Verification Request Submitted Successfully';
                    return redirect()->back()->with('success',$msg);

                }

                
                if(count($per_err) > 0){
                    // return response()->json(['status' => false, 'msg' => implode($per_err,',').' Permission Denied', 'data'=>0]);
                    $msg=implode(',',$per_err).' Permission Denied';
                    return redirect()->back()->with('error',$msg);

                }

                if((count($kyc_type) <= 0) && (count($per_err) <= 0))
                {
                    // return response()->json(['status' => false, 'msg' => ' Something was wrong', 'data'=>0]);
                    return redirect()->back()->with('error','Something was wrong');
                }
            } 
            else
            {
                // return response()->json(['status' => false, 'msg' => 'Please Select Verification Type', 'data'=>0]);
                return redirect()->back()->with('error','Please Select Verification Type');

            } 
        //dd($request->all());
        // $candidate_id=$request->candidate;
        // //dd($candidate_id);
        // $hr_id=Auth::user()->id;
        // $v_type=$request->input("v_type");

        // $v_id=UserAccessMaster::where('name','=',$v_type)->pluck('id')->first();
       
        // $candidate_controller = new CandidateController;             
        // $status=$candidate_controller->chkUserAccess($hr_id,$v_id);
        // //dd($status);
        // //$status=CandidateController::chkUserAccess(Auth::user()->id,"create_offer_letter");
        
        // if($status!=0)
        // {
        //     $user_old_balance=User::where('id','=',$hr_id)->pluck('balance')->first();

        //     $amount=DB::table('verification_types')->where('name','=',$v_type)->pluck('amount')->first();

        //     $bytes = random_bytes(40);
        //     $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);

        //     if($amount <= $user_old_balance)
        //     {
        //             $result = DB::transaction(function () use ($request,$hr_id,$amount,$user_old_balance,$transaction_id,$candidate_id) {
        //                 $transaction = Transaction::create([
        //                             'user_id' => $hr_id,
        //                             'type' => 'Debit',
        //                             'source' => 'Verification Request',
        //                             'description' => $request->input("v_type")." Verification Request",
        //                             'amount' => $amount,
        //                             'updated_balance' =>(float)($user_old_balance - $amount),
        //                             'status' => 1,
        //                             'transaction_id' => $transaction_id                            
        //                         ]);       
        //                         $verification = Verification::insert([
        //                             'candidate_id'=>$candidate_id,
        //                             'hr_id'=>$hr_id,
        //                             'verification_type'=>$request->input("v_type")
        //                         ]);   
        //                         $user = User::where('id','=',$hr_id)
        //                                     ->update([
        //                                         'balance'=>(float)($user_old_balance - $amount)
                                                
        //                         ]);   
        //             });
        //                     return redirect('verificationlist')->with('success','Verification Request Submited Successfully.');
                        
        //     }
        //     else
        //     {
        //         return redirect()->back()->with('error','You Have No Sufficient Wallet Balance.');
        //     }             
        // }
        // else
        // {
        //     return abort(403,"You do not have permission for this");
        // }

        
       
    }


    public function verificationList(Request $request)
    {
        /**
         * Verificatin Request List Page
         * input:ver_type,staff,status
         * Output:verifications,verification_types,staff
         */
        $this->authorize("access-manage-verification");
        $searchData=$request->all();
        $query=Verification::orderBy('id','DESC');
        
        if($request->ver_type) {		
			if($request->ver_type!=''){
				$query->where('verification_type',$request->ver_type);
			}
		}
        if($request->staff) {		
			if($request->staff!=''){
				$query->where('staff_id',$request->staff);
			}
		}
         	
			if($request->status!=''){
				$query->where('status',$request->status);
			}
		 


        //if((Auth::user()->account_type!='hr') && (Auth::user()->account_type!='business'))
        if(Auth::user()->account_type=='superadmin')
        {
            if($request->export)
            {
                $expData=$query->get();
                return Excel::download(new UsersExport('kyc',$expData), 'VerificationList.xlsx');             
                
            }

            $verification_types=DB::table('verification_types')->get();
            $verifications=$query->paginate(15);
            $staff=User::where('account_type','=','verification staff')->get();
            $verifications->appends(request()->query());
            return view('admin.verification.index', compact('verifications','verification_types','staff','searchData'));
        }
        else if(Auth::user()->account_type=='business')
        {
            
            $verification_types=DB::table('verification_types')->get();
            $hr=User::where('parent_id',Auth::user()->id)->pluck('id')->toArray();

            if($request->export)
            {
                $expData=$query->whereIn('hr_id',$hr)->get();
                return Excel::download(new UsersExport('kyc',$expData), 'VerificationList.xlsx');             
                
            }
            //dd($hr);
            $verifications=$query->whereIn('hr_id',$hr)->paginate(15);
            $verifications->appends(request()->query());
            return view('admin.verification.index',compact('verifications','verification_types','searchData'));
        }
         
        else if(Auth::user()->account_type=='hr')
        {
            if($request->export)
            {
                $expData=$query->where('hr_id','=',Auth::user()->id)->get();
                return Excel::download(new UsersExport('kyc',$expData), 'VerificationList.xlsx');             
                
            }
            $verification_types=DB::table('verification_types')->get();
            $verifications=$query->where('hr_id','=',Auth::user()->id)->paginate(15);
            $verifications->appends(request()->query());
            return view('admin.verification.index',compact('verifications','verification_types','searchData'));
        }

        else if(Auth::user()->account_type=='verification staff')
        {
            if($request->export)
            {
                $expData=$query->where('staff_id','=',Auth::user()->id)->get();
                return Excel::download(new UsersExport('kyc',$expData), 'VerificationList.xlsx');             
                
            }
            $verification_types=DB::table('verification_types')->get(); 
            $verifications=$query->where('staff_id','=',Auth::user()->id)->paginate(15);
            $verifications->appends(request()->query());
            return view('admin.verification.index',compact('verifications','verification_types','searchData'));
        }
        else if(Auth::user()->account_type=='verification head')
        {
           
            $staff=User::where('parent_id','=',Auth::user()->id)->get();
            $verification_types=DB::table('verification_types')->get();
            $data=VerificationStaff::where('user_id','=',Auth::user()->id)->first();
            //  dd($data);
            if($request->export)
            {
                $expData=$query->where('verification_type','LIKE','%'.$data->department.'%')->get();
                return Excel::download(new UsersExport('kyc',$expData), 'VerificationList.xlsx');             
                
            }
            $verifications=$query->where('verification_type','LIKE','%'.$data->department.'%')
            ->paginate(15);
            $verifications->appends(request()->query());
            return view('admin.verification.index',compact('verifications','verification_types','staff','searchData'));
        }

       // $staff=User::where('account_type','=','verification staff')->get();
       
        
    }



    public function getStaff(Request $request)
    {
        /**
         * Get Staff List For Assign 
         * input:verification_id
         * output:staff
         */
        $verifications=Verification::where('id','=',$request->verification_id)->pluck('verification_type')->first();
        //dd($verifications);

        if(Auth::user()->account_type=='superadmin'){
            $staff= DB::table('verification_staffs')
            ->join('users', 'users.id', '=', 'verification_staffs.user_id')
            ->where('verification_staffs.department','LIKE','%'.$verifications.'%')   
            ->where('users.account_type', '=', 'verification staff')          
            ->get();
        }
        else{
            $staff= DB::table('verification_staffs')
            ->join('users', 'users.id', '=', 'verification_staffs.user_id')
            ->where('verification_staffs.department','LIKE','%'.$verifications.'%')    
            ->where('users.parent_id', '=', Auth::user()->id)    
            ->where('users.account_type', '=', 'verification staff')         
            ->get();
        }
        

        //dd($staff);
            
        //$staff=VerificationStaff::where('department','LIKE','%'.$verifications.'%')->get();
        //dd($staff);
        return $staff;


    }


}

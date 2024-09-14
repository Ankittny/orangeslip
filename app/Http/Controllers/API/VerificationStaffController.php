<?php


namespace App\Http\Controllers\API;

use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\VerificationStaff;
use App\Models\Verification;
use App\Models\User;
use App\Models\Profile;
Use Auth;
use DB;
use Session;
use App\Libs\CommonHelper;


class VerificationStaffController extends Controller
{
   
    public function VerificationAssign(Request $request)
    {
        /**
         * Assign Verification
         * input:staff_id,v_id,doc[name],doc[file].
         * output:redirect with success/error.
         */
        $this->authorize("access-manage-verification");
        
        $validator=Validator::make($request->all(),[
            'staff_id'=>'required' 
        ],
        [
            'staff_id.required'=>'Select Staff'
            
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors'=>$validator->errors()]);
            }
       // dd($request->all());
        $staff_id=$request->staff_id;
        $verification_id=$request->v_id;
        // if($staff_id!=null)
        // {
            foreach($request->doc as $key => $value){
                
                $validator=Validator::make($value,[
                    'name'=>'required',
                    'file'=>'required|mimes:jpg,jpeg'
                ],
                [
                    'name.required'=>'Document Name Required',                     
                    'file.required'=>'File Required',
                    'file.mimes'=>'File Type must be jpg/jpeg'
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'errors'=>$validator->errors()]);
                    }
                //dd($value['file']);
                $doc_file_link = $request->file('doc')[$key]['file']
                            ->store('verification_doc');
    
                // $doc_file_link = $request->file($value['file'])
                // ->store('verification_doc');
                $ver_doc_save=DB::table('verification_documents')->insert(['verification_id'=>$verification_id,'staff_id'=>Auth::user()->id,'doc_name'=>$value['name'],'doc_file'=>$doc_file_link,'doc_type'=>1]);
            }

            $assign=Verification::where('id','=',$verification_id)->update(['staff_id'=>$staff_id,'is_assigned'=>1,'status'=>2]);
            if($assign)
            {
                return response()->json(['status' => true, 'msg' => 'ok', 'data'=>1]);
                // return response()->json(['status' => true, 'errors'=>'ok']);
                // return $msg='ok';
            }
            else
            {
                // return $msg='Something was wrong!';
                // return response()->json(['status' => false, 'errors'=>'Something was wrong!']);
                return response()->json(['status' => false, 'msg' => 'Something was wrong!', 'data'=>0]);
            }
       // }
        // else
        // {
        //     // return $msg='Select Staff!';
        //     return response()->json(['status' => false, 'errors'=>'Select Staff!']);
        // }
    
            
        
    }

    public function SubmitVerificationReport(Request $request)
    {
        /**
         * Submit Verification Report
         * input:v_status,comment, doc[name],doc[file].
         * output:json[]
         */
   
        $validator=Validator::make($request->all(),[
            'v_status'=>'required',
            'comment'=>'required'
        ],
        [
            'v_status.required'=>'Status Required',                     
            'comment.required'=>'Remark Required'
            
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        $verification_id=$request->v_id1;
        // $verification_id=$request->verification_id;
         
        foreach($request->doc as $key => $value){

            $validator=Validator::make($value,[
                'name'=>'required',
                'file'=>'required|mimes:jpg,jpeg'
            ],
            [
                'name.required'=>'Document Name Required',                     
                'file.required'=>'File Required',
                'file.mimes'=>'File Type must be jpg/jpeg'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
                }

                
            //dd($value['file']);
            $doc_file_link = $request->file('doc')[$key]['file']
                        ->store('verification_doc');

            // $doc_file_link = $request->file($value['file'])
            // ->store('verification_doc');
        $ver_doc_save=DB::table('verification_documents')->insert(['verification_id'=>$verification_id,'staff_id'=>Auth::user()->id,'doc_name'=>$value['name'],'doc_file'=>$doc_file_link,'doc_type'=>2]);
        }


          // dd(1);

            $report=Verification::where('id','=',$verification_id)->update(['details'=>$request->comment,'updated_to_hr'=>1,'status'=>$request->v_status]);
            
            if($report)
            {
                // return $msg='ok';
                // return response()->json(['status' => true, 'errors'=>'ok']);
                return response()->json(['status' => true, 'msg' => 'ok', 'data'=>1]);
            }
            else
            {
                // return $msg='Something was wrong!';
                // return response()->json(['status' => false, 'errors'=>'Something was wrong!']);
                return response()->json(['status' => false, 'msg' => 'Something was wrong!', 'data'=>0]);
            }
        
    }
    public function rejectRequest(Request $request)
    {
        /**
         * Reject Assign Request
         * input:id(verification id).
         * output:Redirect
         */
        
        $verification_id=$request->id;
         
        $report=Verification::where('id','=',$verification_id)->update(['staff_id'=>Null,'status'=>5]);
       
        $ver_rej_req=DB::table('verification_rejected_requests')->insert(['verification_id'=>$verification_id, 'staff_id'=>Auth::user()->id]);
        if($report)
        {
            // return redirect('verificationlist');
            return response()->json(['status' => true, 'msg'=>'Assignment Rejected Successfully!', 'data'=>1]);
        }
        else
        {
            return response()->json(['status' => false, 'msg'=>'Something was wrong', 'data'=>0]);
        }
            
          

    }
    
    public function ViewVerificationReport(Request $request)
    {
        /**
         * View Verification Report
         * input:verification_id
         * output:msg,ver_doc
         */
        
        $verification_id=$request->id;
        if($verification_id!=null)
        {
            $ver_report=Verification::where('id','=',$verification_id)->get(['status','details']);
            $ver_doc=DB::table('verification_documents')->where([['verification_id','=',$verification_id],['doc_type','=',2]])->get(['doc_name','doc_file']);
            //dd($msg);
            $data=[
                'ver_report'=>$ver_report,
                'ver_doc'=>$ver_doc,
            ];
            return response()->json(['status' => true, 'msg' => 'success', 'ver_report'=>$ver_report,'ver_doc'=>$ver_doc]);
           
        }
        else
        {
            // return $msg='Something Was Wrong!';
            return response()->json(['status' => false, 'msg' => 'Something was wrong!', 'data'=>1]);
        }

    }
    public function ViewVerificationDoc(Request $request)
    {
        /**
         * View Verification Document
         * input:verification_id
         * output:data
         */
        $verification_id=$request->id;
        if($verification_id!=null)
        {
           // $msg=Verification::where('id','=',$verification_id)->get();
            $data=DB::table('verification_documents')->where([['verification_id','=',$verification_id],['doc_type','=',1]])->get();
            //dd($msg);
            
            //  return $data;
            return response()->json(['status' => true, 'msg' => 1, 'data'=>$data]);
           
        }
        else
        {
            // return $data='Something Was Wrong!';
            return response()->json(['status' => false, 'msg' => 'Something was wrong!', 'data'=>0]);
        }

    }


    


    public function addVerificationStaff()
    {
        /**
         * For Create Agent Page View
         * Output:role
         */
        $this->authorize("access-manage-verification-staff");      
        $country=Country::get();
        $allHead=User::where('account_type','=','verification head')->get();
        return view('admin.verificationstaff.create',compact('country','allHead'));
    }

    public function storeVerificationStaff(Request $request)
    { 

       /**
        * for Store Agent Detail
        * Input:first_name,last_name,email,mobile_no,gender,role,password.
        * Output: Redirect with success/error
        */
        $this->authorize("access-manage-verification-staff"); 
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

               $dept=VerificationStaff::where('user_id','=',$request->head)->first();
               $department=$dept->department;

                
                $user = User::create([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email' => strtolower($request->email),
                            'password' => Hash::make($request->password),
                            'account_type' => 'verification staff',
                            'user_code' => $user_code,
                            'parent_id'=>$pid
                        ]);
                        
                $profile = Profile::create([
                    'user_id'           => $user->id,
                    'mobile_no'         => $request->mobile_no,
                    'gender'            => $request->gender,
                    'country'           => $request->country
                   
                ]);

                $verification_staff = VerificationStaff::create([
                    'user_id'           => $user->id,
                    'department'         => $department
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
            // return redirect()->route('verificationStaffList')->with('success','Verification Staff Added Successfully');
            return response()->json(['status' => true, 'msg' =>'Verification Staff Added Successfully', 'data'=>1]);
        } catch (\Exception $e) {//dd($e->getMessage());
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'msg' =>$e->getMessage(), 'data'=>0]);
        }
    }

    public function verificationStaffList(Request $request)
    {
        // dd(1);
        $this->authorize("access-manage-verification-staff"); 
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
        if(Auth::user()->account_type=='superadmin')
        {
            $allHead=$query->where('users.account_type','=','verification staff')->paginate(15);
            $allHead->appends(request()->query());
        }
        else
        {
            $allHead=$query->where([['users.account_type','=','verification staff'],['users.parent_id','=',Auth::user()->id]])->paginate(15);
            $allHead->appends(request()->query());
        }
        
        
        //dd($allHead);
        // return view('admin.verificationstaff.index',compact('allHead','searchData'));
        return response()->json(['status' => true, 'msg' =>1, 'data'=>$allHead,$searchData]);
    }

    public function editVerificationStaff(Request $request)
    {
        /**
         * For Edit HR/User Data page View
         * Input:id
         * Output:user,all_business,all_access,user_access.
         */
        $this->authorize("access-manage-verification-staff"); 
        
        
        $country=Country::get(); 
        $user=User::where('id','=',$request->id)->first(); 
        //dd($user->parent_id);      
        if(((Auth::user()->account_type!='superadmin') && ($user->parent_id==Auth::user()->id)) || (Auth::user()->account_type=='superadmin') )
        {
            // return view('admin.verificationstaff.edit',compact('country','user')); 
            return response()->json(['status' => true, 'msg' =>1, 'data'=>$country,$user]); 
        }
        else
        {
            
        //   return abort(403,"You do not have permission for this");
        return response()->json(['status' => false, 'msg' =>'You do not have permission for this', 'data'=>0]); 
        }       
        
       
        
    }

    public function updateVerificationStaff(Request $request)
    {
        /**
         * For Update HR/User Data
         * Input:id,first_name,last_name,email,mobile_no,gender.
         * Output:Redirect with success/error.
         */
        $this->authorize("access-manage-verification-staff"); 
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
            // return redirect()->route('verificationStaffList')->with('success','Verification Staff Updated Successfully');
            return response()->json(['status' => true, 'msg' =>'Verification Staff Updated Successfully', 'data'=>1]); 
        } catch (\Exception $e) {//dd($e->getMessage());
            // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'msg' =>$e->getMessage(), 'data'=>0]); 
        }
    }
    

}

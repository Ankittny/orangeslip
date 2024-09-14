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
use App\Models\User;
use App\Models\Profile;
use App\Models\Transaction;
use App\Models\Deposit;
use DB;
use Session;
Use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferLetterGenerated;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WalletController extends Controller
{
    public function index(Request $request)
    {
		
        // $query = Transaction::where('user_id','=',Auth::user()->id)->orderBy('id','DESC');
        $query=DB::table('transactions as txns')
        ->join('users', 'txns.user_id', '=', 'users.id')        
        // ->select('cd.*', 'state.*', 'city.*','countries.*','job_roles.*','emp.*','hr.*')
        ->select('txns.*', 'users.first_name')
        // ->where('txns.user_id','=',Auth::user()->id)
        ->orderBy('txns.id','DESC');
        
        if($request->transaction_id) {		
			if($request->transaction_id!=''){
				$query->where('txns.transaction_id',$request->transaction_id);
			}
		}

        if($request->type) {		
			if($request->type!=''){
				$query->where('txns.type',$request->type);
			}
		}

        if($request->user) {		
			if($request->user!=''){
				$query->where('txns.user_id',$request->user);
			}
		}

        if($request->status!='') {		
			
				$query->where('txns.status',$request->status);
			
		}

        if($request->from_date && $request->to_date) {		
			if($request->from_date!='' && $request->to_date){
				$fd=$request->from_date;
                $td=$request->to_date;
                $query->whereBetween('txns.created_at', [$fd." 00:00:00", $td." 23:59:59"]);
			}
		}
        
        $transaction=$query->get();  
        // $balance=   DB::table('transactions as txns')->where('user_id',$request->user)->latest()->pluck('updated_balance')->first();
        $balance=   DB::table('transactions as txns')->where('user_id',$request->user)->orderBy('id','DESC')->pluck('updated_balance')->first();
               
        return response()->json([
            'status'=>true,           
            'data'=>$transaction,
            'balance'=>$balance,
            'success'=>1
        ]);
        // return view('wallet.transaction')->with('transaction',$transaction);

		
    }
    

    public function credit_amount(Request $request)
	{	
        //dd($request->all());
        $validator = Validator::make($request->all(),[
            'amount'=>'required|numeric|gt:0',
            'user_id'=>'required|numeric'
        ]);

        if($validator->fails()) 
        {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        }

            $userStatus=User::where('id',$request->user_id)->pluck('status')->first();
        if($userStatus==1)
        {
            try {
                $amount = (float)$request->input("amount");		
                $user_id = $request->input("user_id");
                $creditUser=User::where('id',$request->input("user_id"))->first();  
                $user_name=$creditUser->first_name;  
                //dd($user_id,$user_name);
                $updated_balance=$creditUser->balance;
                // Check if amount is available in that currency in admin account --
                $admin = auth()->user();
                    if($user_id == $admin->id){
                        // return [
                        //     'status' => false,
                        //     'message' => "Sender and Receiver are same user !"
                        // ];
                        return response()->json(['status' => false, 'msg' => 'Sender and Receiver are same user !', 'data'=>0]);
                    }
            
                $bytes = random_bytes(40);
                $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);
                if(Auth::user()->balance >= $amount)
                {

                    $result = DB::transaction(function () use ($request,$user_name,$user_id,$amount,$updated_balance,$transaction_id) {
                        $credit = Transaction::create([
                                    'user_id' => $request->input("user_id"),
                                    'type' => 'Credit',
                                    'source' => 'From '.Auth::user()->first_name,
                                    'description' => $request->input("description"),
                                    'amount' => $amount,
                                    'updated_balance' =>(float)($updated_balance + $amount),
                                    'status' => 1,
                                    'transaction_id' => $transaction_id                            
                                ]);       
                                $receiver = User::where('id','=',$request->input("user_id"))
                                ->update([
                                    'balance'=>(float)($updated_balance + $amount),
                                    'updated_at'=>date('Y-m-d H:i:s')                          
                        ]);         
                        //$user_name=User::where('id',$request->input("user_id"))->pluck('first_name')->first();        
                        $debit = Transaction::create([
                                    'user_id' => Auth::user()->id,
                                    'type' => 'Debit',
                                    'source' => 'To '.$user_name,
                                    'description' => $request->input("description"),
                                    'amount' => $amount,
                                    'updated_balance' =>(float)(Auth::user()->balance - $amount),
                                    'status' => 1,
                                    'transaction_id' => $transaction_id                            
                                ]);            
                    
                        $sender = User::where('id','=',Auth::user()->id)
                                        ->update([
                                            'balance'=>(float)(Auth::user()->balance - $amount),
                                            'updated_at'=>date('Y-m-d H:i:s')                          
                                ]);            
                        

                    });

                    //return redirect()->route('business.index');
                    // return redirect()->back()->with('success','Amont Credited Successfully');
                    return response()->json(['status' => true, 'msg' => 'Amount Credited Successfully', 'data'=>1]);
                }
                else{
                    // return redirect()->back()->with('error','Insufficient Wallet Balance');
                    return response()->json(['status' => false, 'msg' => 'Insufficient Wallet Balance', 'data'=>0]);
                }
            }

            catch (\Exception $e) 
            {
                //dd( $e->getMessage());
                // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                return response()->json(['status' => false, 'msg' =>$e->getMessage(), 'data'=>0]);
            }
        }
        else{
            // return redirect()->back()->with('error','Insufficient Wallet Balance');
            return response()->json(['status' => false, 'msg' => 'Inactive Profile!', 'data'=>0]);
        }
	}
    
    public function debit_amount(Request $request)
	{	
        //dd($request->all());
         
        $validator = Validator::make($request->all(),[
            'amount'=>'required|numeric|gt:0',
            'user_id'=>'required|numeric'
        ]);
		if ($validator->fails())
        {
            return response()->json(['status' => false, 'msg' => $validator->errors(), 'data'=>0]);
        }
            $userStatus=User::where('id',$request->user_id)->pluck('status')->first();
        if($userStatus==1)
        {
            try {
                $amount = (float)$request->input("amount");		
                $user_id = $request->input("user_id");
                $debitUser=User::where('id',$request->input("user_id"))->first();  
                $user_name=$debitUser->first_name;  
                //dd($user_id,$user_name);
                $updated_balance=$debitUser->balance;
                // Check if amount is available in that currency in admin account --
                $admin = auth()->user();
                if($user_id == $admin->id){
                    
                    return response()->json(['status' => false, 'msg' => 'Sender and Receiver are same user !', 'data'=>0]);
                }
            
                $bytes = random_bytes(40);
                $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);
                if($updated_balance >= $amount)
                {
                    $result = DB::transaction(function () use ($request,$user_id,$amount,$updated_balance,$transaction_id,$user_name) {
                        $debit = Transaction::create([
                                    'user_id' => $request->input("user_id"),
                                    'type' => 'Debit',
                                    'source' => 'To '.Auth::user()->account_type,
                                    'description' => $request->input("description"),
                                    'amount' => $amount,
                                    'updated_balance' =>(float)($updated_balance - $amount),
                                    'status' => 1,
                                    'transaction_id' => $transaction_id                            
                                ]);       
                                $sender = User::where('id','=',$request->input("user_id"))
                                ->update([
                                    'balance'=>(float)($updated_balance - $amount),
                                    'updated_at'=>date('Y-m-d H:i:s')                          
                        ]);                 
                        $credit = Transaction::create([
                                    'user_id' => Auth::user()->id,
                                    'type' => 'Credit',
                                    'source' => 'From '.$user_name,
                                    'description' => $request->input("description"),
                                    'amount' => $amount,
                                    'updated_balance' =>(float)(Auth::user()->balance + $amount),
                                    'status' => 1,
                                    'transaction_id' => $transaction_id                            
                                ]);            
                    
                        $receiver = User::where('id','=',Auth::user()->id)
                                        ->update([
                                            'balance'=>(float)(Auth::user()->balance + $amount),
                                            'updated_at'=>date('Y-m-d H:i:s')                          
                                ]); 
                    });
             
                    return response()->json(['status' => true, 'msg' => 'Amount Debited Successfully', 'data'=>1]);
                }
                else{
                    // return redirect()->back()->with('error','Insufficient Wallet Balance');
                    return response()->json(['status' => false, 'msg' => 'Insufficient Wallet Balance', 'data'=>0]);

                }
            }

            catch (\Exception $e) 
            {
                //dd( $e->getMessage());
                // return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                return response()->json(['status' => false, 'error' => $e->getMessage(), 'data'=>0]);
            }
        }
        else{
            // return redirect()->back()->with('error','Insufficient Wallet Balance');
            return response()->json(['status' => false, 'msg' => 'Inactive Profile!', 'data'=>0]);
        }
	}

    public function DepositList()
    {
        if(Auth::user()->administrator==1)
        {
            $deposits=Deposit::orderby('id','DESC')
            ->join('users','deposits.user_id','=','users.id')
            ->select('deposits.*','users.first_name as userName')
            ->get();

        }
        else
        {
            $deposits=Deposit::where('user_id','=',Auth::user()->id)
            ->join('users','deposits.user_id','=','users.id')
            ->select('deposits.*','users.first_name as userName')
            ->orderby('id','DESC')->get();
        }
        
        // return view('wallet.depositlist')->with('deposits',$deposits);
        return response()->json([
            'status'=>true,           
            'data'=>$deposits,
            'success'=>1
        ]);
    }
   
    public function DepositStore(Request $request)
    {
       
       
        $validator = Validator::make($request->all(),[
            'amount'=>'required|numeric|gt:0',
            // 'tid'=>'required|regex:/^[a-zA-Z0-9]+$/|max:255|unique:deposits',
            'tid'=>'required|max:255|unique:deposits',
            'doc'=>'nullable|mimes:jpg,jpeg|max:2000'
        ],
        [
            'amount.gt'=>'Amount must greater then 0',
            'tid.required'=>'Transaction Id Required',
            'tid.unique'=>'Transaction Id Already Exist.',
            'doc.required'=>'File Required.',
            'doc.mimes'=>'The File must be a file of type: jpg, jpeg.',
            'doc.max'=>'File is too large to upload',
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        }

        $path='';
        $amount=$request->amount;
        $tid=$request->tid;
        $doc=$request->doc;
        $comment=$request->comment;

        if(Auth::user()->account_type=='hr'){
            $bytes = random_bytes(40);
            $tid="TXN".substr(bin2hex($bytes), 0, 23);
        }
       
        if($request->doc!=Null)
        {
         $path = $request->file('doc')
         ->store('deposit_doc');
        }

        
        $data=Deposit::insert(['user_id'=>Auth::user()->id,'amount'=>$amount,'tid'=>$tid,'doc'=>$path,'comment'=>$comment,'bank_id'=>$request->bank_id]);
       
        if($data)
        {
            // return redirect('depositlist')->with('success','Deposit Request Submitted Successfully!');
            return response()->json(['status' => true, 'msg' => 'Deposit Request Submitted Successfully!', 'data'=>1]);
        }
        else
        {
            // return redirect('depositlist')->with('error','Something was Wrong!');
            return response()->json(['status' => false, 'msg' => 'Something was Wrong!', 'data'=>0]);
        }
    }

    public function ApproveHrDeposit(Request $request)
    {
       
        $response=$request->response;
        $deposits=Deposit::where('id','=',$request->deposit_id)->first();
        $userDetails=User::where('id','=',$deposits->user_id)->first();
        $remark=$request->remark;
        //$reason=$request->reason;
        if($deposits->status!=1){
            // return $msg='Already taken action for this request';
            return response()->json(['status' => false, 'msg' => 'Already taken action for this request', 'data'=>0]);
        }
       
        if($userDetails->account_type!='hr' || Auth::user()->account_type!='business' )
        {
             return response()->json(['status' => false, 'msg' => 'You do not have permission for this', 'data'=>0]);
        }

        if($response=="2")
        {
            $amount=$deposits->amount;
            
                if($userDetails->Parent->balance < $amount){
                    
                    return response()->json(['status' => false, 'msg' => 'Insufficient Balance', 'data'=>0]);
                }
             

            $result = DB::transaction(function () use ($request,$response,$userDetails,$deposits,$amount,$remark) {

               
                  
               
                $user_id=$deposits->user_id;
              
                $user_old_balance=$userDetails->balance;
                $bytes = random_bytes(40);
                $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);

                if($userDetails->account_type=='hr'){
                    $parent_old_balance=$userDetails->Parent->balance;
                    $new_transaction_dbt = new Transaction;
                    $new_transaction_dbt->user_id = $userDetails->parent_id;
                    $new_transaction_dbt->currency_id = 1;//$currency_id;
                    $new_transaction_dbt->reference_no = "";
                    $new_transaction_dbt->type = "Debit";
                    $new_transaction_dbt->source = "Deposit Approve for HR- $userDetails->first_name";
                    $new_transaction_dbt->description = "Deposit Request Approved.";
                    $new_transaction_dbt->amount = $amount;
                    $new_transaction_dbt->updated_balance = $parent_old_balance - $amount;
                    $new_transaction_dbt->status = 1; // Success
                    $new_transaction_dbt->transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);
                    $new_transaction_dbt->save();

                    User::where('id','=',$userDetails->parent_id)->update(['balance'=>$parent_old_balance - $amount,'updated_at'=>date('Y-m-d H:i:s')]);
                    
                }

                $new_transaction = new Transaction;
                $new_transaction->user_id = $user_id;
                $new_transaction->currency_id = 1;//$currency_id;
                $new_transaction->reference_no = "";
                $new_transaction->type = "Credit";
                $new_transaction->source = "Deposit Request";
                $new_transaction->description = "Deposit Request Approved.";
                $new_transaction->amount = $amount;
                $new_transaction->updated_balance = $user_old_balance + $amount;
                $new_transaction->status = 1; // Success
                $new_transaction->transaction_id = $transaction_id;
                $new_transaction->save();

                

                $update_balance_to_user=User::where('id','=',$user_id)->update(['balance'=>$user_old_balance + $amount,'updated_at'=>date('Y-m-d H:i:s')]);

               $update_deposits_status=Deposit::where('id','=',$request->deposit_id)->update(['status'=>$response,'reason'=>$remark,'updated_at'=>date('Y-m-d H:i:s')]);

            });
                    // return $msg='Deposit Request Approved Successfully...';
                    return response()->json(['status' => true, 'msg' => 'Deposit Request Approved Successfully', 'data'=>1]);
                
                
        }
        else
        {
            $update_deposits_status=Deposit::where('id','=',$request->deposit_id)->update(['status'=>$response,'reason'=>$remark,'updated_at'=>date('Y-m-d H:i:s')]);
            // return $msg='Request Rejected.';
            return response()->json(['status' => true, 'msg' => 'Request Rejected.', 'data'=>0]);
        }
                    


    }

    public function hrDepositList()
    {

        if(Auth::user()->account_type=='business')
        {
            $allHr=User::where('parent_id','=',Auth::user()->id)->pluck('id')->toArray();

            $deposits=Deposit::orderby('id','DESC')
            ->join('users','deposits.user_id','=','users.id')
            ->whereIn('user_id',$allHr)
            ->select('deposits.*','users.first_name as userName')
            ->get();

        }
        else
        {
            return response()->json([
                'status'=>false,           
                'data'=>0,
                'msg'=>'Permission Denied!'
            ]);
        }
        
        // return view('wallet.depositlist')->with('deposits',$deposits);
        return response()->json([
            'status'=>true,           
            'data'=>$deposits,
            'success'=>1
        ]);
    }



}
    
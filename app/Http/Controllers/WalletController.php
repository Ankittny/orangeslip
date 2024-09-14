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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class WalletController extends Controller
{
    public function index(Request $request)
    {
		/**
         * Transaction List
         * input:transaction_id,type,user,status,from_date,to_date - for search
         * Output:transaction
         */
        $searchData=$request->all();
        if(Auth::user()->account_type=='superadmin'){
            // dd(1);
            $query = Transaction::orderBy('id','DESC');
        }
        else {
            if($request->user) {
                $query = Transaction::where('user_id','=',$request->user)->orderBy('id','DESC');
            }
            else{
                $query = Transaction::where('user_id','=',Auth::user()->id)->orderBy('id','DESC');
            }
            
        }
        
       

        if($request->transaction_id) {		
			if($request->transaction_id!=''){
				$query->where('transaction_id',$request->transaction_id);
			}
		}

        if($request->type) {		
			if($request->type!=''){
				$query->where('type',$request->type);
			}
		}

        // if($request->user) {		
		// 	if($request->user!=''){
		// 		$query->where('user_id',$request->user);
		// 	}
        //     else{
        //         $query = where('user_id','=',Auth::user()->id);
        //     }
		// }

        if($request->status) {		
			if($request->status!=''){
				$query->where('status',$request->status);
			}
		}

        if($request->from_date && $request->to_date) {		
			if($request->from_date!='' && $request->to_date){
				$fd=$request->from_date;
                $td=$request->to_date;
                $query->whereBetween('created_at', [$fd." 00:00:00", $td." 23:59:59"]);
			}
		}
        if($request->export)
        {
            
            $expData=$query->get();
            // dd($expData);
            return Excel::download(new UsersExport('txn',$expData), 'TransactionList.xlsx');             
            
        }
        
        $transaction=$query->paginate(15);       
        $transaction->appends(request()->query());
        return view('wallet.transaction',compact('transaction','searchData'));

		
    }
    public function credit_amount_form($id)
	{
		/**
         * Credit Amount Page View
         * Input:id
         * output:user,updated_balance,id
         */
        
		$user = User::where('id', $id)->first();
        if((Auth::user()->account_type=='superadmin') || ((Auth::user()->account_type=='business') && (Auth::user()->id==$user->parent_id)))
        {
            $updated_balance = $user->balance;
		    return view('wallet.credit',compact('user','updated_balance','id'));
        }
        else
        {
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
		 
        
	
	}

    public function credit_amount(Request $request)
	{	
        /**
         * Credit Amount Store
         * input:amount,user_id,updated_balance,description
         * output:Redirect with success/error
         */
         
         $this->validate($request,[
            'amount'=>'required|numeric|gt:0',
            'user_id'=>'required|numeric'
        ]);
		
        try {
                $amount = (float)$request->input("amount");		
                $user_id = $request->input("user_id");
                $user_name=User::where('id',$request->input("user_id"))->pluck('first_name')->first();  
                //dd($user_id,$user_name);
                $updated_balance=$request->input("updated_balance");
                // Check if amount is available in that currency in admin account --
                $admin = auth()->user();
                if($user_id == $admin->id){
                    return [
                        'status' => false,
                        'message' => "Sender and Receiver are same user !"
                    ];
                }
            
                $bytes = random_bytes(40);
                $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);
                if(Auth::user()->balance >= $amount){

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
                return redirect()->back()->with('success','Amont Credited Successfully');
            }
            else{
                return redirect()->back()->with('error','Insufficient Wallet Balance');
            }
            }

            catch (\Exception $e) 
            {
                //dd( $e->getMessage());
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
	}
    public function debit_amount_form($id)
	{
		/**
         * Debit Amount Page View
         * Input:id
         * output:user,updated_balance,id
         */

		$user = User::where('id', $id)->first();

        if((Auth::user()->account_type=='superadmin') || ((Auth::user()->account_type=='business') && (Auth::user()->id==$user->parent_id)))
        {
			$updated_balance = $user->balance;		 
		    return view('wallet.debit',compact('updated_balance','id','user'));
        }
        else{
            // return abort(403,"You do not have permission for this");
            return redirect()->back()->with('error','You do not have permission for this');
        }
	
	}

    public function debit_amount(Request $request)
	{	
        /**
         * Debit Amount Store
         * input:amount,user_id,updated_balance,description
         * output:Redirect with success/error
         */
         
        $this->validate($request,[
            'amount'=>'required|numeric|gt:0',
            'user_id'=>'required|numeric'
        ]);
		
        try {
                $amount = (float)$request->input("amount");		
                $user_id = $request->input("user_id");
                $updated_balance=$request->input("updated_balance");
                // $user_name=User::where('id',$request->input("user_id"))->pluck('first_name')->first();   
                // Check if amount is available in that currency in admin account --
                $admin = auth()->user();
                if($user_id == $admin->id){
                    return [
                        'status' => false,
                        'message' => "Sender and Receiver are same user !"
                    ];
                }
            
                $bytes = random_bytes(40);
                $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);
                if($updated_balance >= $amount)
                {
                        $result = DB::transaction(function () use ($request,$user_id,$amount,$updated_balance,$transaction_id) {
                            $user_name=User::where('id',$request->input("user_id"))->pluck('first_name')->first(); 
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
                                    //dd(1);
                            $receiver = User::where('id','=',Auth::user()->id)
                                            ->update([
                                                'balance'=>(float)(Auth::user()->balance + $amount),
                                                'updated_at'=>date('Y-m-d H:i:s')                          
                                    ]);            
                            

                        });
                        
                    // return redirect()->route('business.index');
                        return redirect()->back()->with('success','Amount Debited Successfully');
                }
                else
                {
                    return redirect()->back()->with('error','Insufficient Wallet Balance');
                }
            }

            catch (\Exception $e) 
            {
                //dd( $e->getMessage());
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
	}

    public function DepositList(Request $request)
    {
        /**
         * Deposit List
         * Input:null
         * Output:deposits
         */
        $searchData=$request->all();
        $query = Deposit::orderBy('id','DESC');

        if($request->transaction_id) {		
			if($request->transaction_id!=''){
				$query->where('tid',$request->transaction_id);
			}
		}

        if($request->status) {		
			if($request->status!=''){
				$query->where('status',$request->status);
			}
		}

        if($request->from_date && $request->to_date) {		
			if($request->from_date!='' && $request->to_date){
				$fd=$request->from_date;
                $td=$request->to_date;
                $query->whereBetween('created_at', [$fd." 00:00:00", $td." 23:59:59"]);
			}
		}

        if(Auth::user()->administrator==1)
        {
                if($request->export)
            {                
                $expData=$query->where('bank_id','!=',NULL)->get();
                return Excel::download(new UsersExport('depositlist',$expData), 'DepositList.xlsx');  
            }

            $deposits=$query->where('bank_id','!=',NULL)->paginate(15);
            
        }
        else
        {
            if($request->export)
            {                
                $expData=$query->where('user_id','=',Auth::user()->id)->get();
                return Excel::download(new UsersExport('depositlist',$expData), 'DepositList.xlsx'); 
            }
            $deposits=$query->where('user_id','=',Auth::user()->id)->paginate(15);
        }
        
        return view('wallet.depositlist',compact('deposits','searchData'));
        
    }
    public function DepositPage()
    {
        /**
         * Deposit Request Page View
         * 
         */
        $bank_details=DB::table('bank_details')->where('status','=',1)->get();
        return view('wallet.deposit',compact('bank_details'));
    }
    public function DepositStore(Request $request)
    {       
         /**
         * Deposit Request Store
         * input:amount,tid,doc,comment.
         * output:Redirect with success/error
         */
        
        $this->validate($request,[
            'amount'=>'required|numeric|gt:0',
            // 'tid'=>'required|regex:/^[a-zA-Z0-9]+$/|max:255|unique:deposits',
            'tid'=>'required|regex:/^[a-zA-Z0-9]+$/|max:255|unique:deposits',
            'doc'=>'nullable|mimes:jpg,jpeg,png|max:2000'
        ],
        [
            'amount.gt'=>'Amount must greater then 0',
            'tid.required'=>'Transaction Id Required',
            'tid.regex'=>'Transaction Id invalid format',
            'tid.unique'=>'Transaction Id Already Exist.',
            'doc.required'=>'File Required.',
            'doc.mimes'=>'The File must be a file of type: jpg, jpeg, png.',
            'doc.max'=>'File is too large to upload',
        ]);                                                                                             
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
            return redirect('depositlist')->with('success','Deposit Request Submitted Successfully!');
        }
        else
        {
            return redirect('depositlist')->with('error','Something was Wrong!');
        }
    }

    public function ApproveDeposit(Request $request)
    {
        /**
         * Deposit Request Approve
         * input:response,remark,reason,deposit_id.
         * output:Redirect with success/error
         */

        $response=$request->response;
        $remark=$request->remark;
        $reason=$request->reason;

        $deposits=Deposit::where('id','=',$request->deposit_id)->first();

        if($deposits->status!=1){
            return $msg='Already taken action for this request';
        }
        $user_id=$deposits->user_id;
        $amount=$deposits->amount;
        //$user_type=Auth::user()->user_type;
        $user_old_balance=User::where('id','=',$user_id)->pluck('balance')->first();
        $bytes = random_bytes(40);
        $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);

       //dd($response);
        if($response=="2")
        {
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

            if($update_balance_to_user)
            {
                $update_deposits_status=Deposit::where('id','=',$request->deposit_id)->update(['status'=>$response,'reason'=>$remark,'updated_at'=>date('Y-m-d H:i:s')]);
                return $msg='ok';
            }
            else
            {
                return $msg='Something Was Wrong.';
            }
        }
        else
        {
            $update_deposits_status=Deposit::where('id','=',$request->deposit_id)->update(['status'=>$response,'reason'=>$reason,'updated_at'=>date('Y-m-d H:i:s')]);
            return $msg='Request Rejected.';
        }
                    


    }

    public function hrDepositList(Request $request)
    {
        $searchData=$request->all();
        $allHr=User::where('parent_id','=',Auth::user()->id)->pluck('id')->toArray();

        $query=Deposit::orderby('id','DESC')
        ->join('users','deposits.user_id','=','users.id')
        ->whereIn('user_id',$allHr)
        ->select('deposits.*','users.first_name as userName');

      
         if($request->transaction_id) {		
			if($request->transaction_id!=''){
				$query->where('deposits.tid',$request->transaction_id);
			}
		}

        	
        if($request->status!=''){
            $query->where('deposits.status',$request->status);
        }
		

        if($request->from_date && $request->to_date) {		
			if($request->from_date!='' && $request->to_date){
				$fd=$request->from_date;
                $td=$request->to_date;
                $query->whereBetween('deposits.created_at', [$fd." 00:00:00", $td." 23:59:59"]);
			}
		}




        if(Auth::user()->account_type=='business')
        {       
            if($request->export)
            {                
                $expData=$query->get();
                return Excel::download(new UsersExport('hrdeposit',$expData), 'HrDepositList.xlsx'); 
            }
            $deposits=$query->paginate(10);

        }
        else
        {
            
            // return redirect('depositlist')->with(403,'you do not have permission for this!');
            return redirect()->back()->with('error','You do not have permission for this');
        }
        
         return view('wallet.hrDepositRequests',compact('deposits','searchData'));
        
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
            return $msg='Already taken action for this request';
        }
       
        if($userDetails->account_type!='hr' || Auth::user()->account_type!='business' )
        {
             return $msg='You do not have permission for this';
        }

        if($response=="2")
        {
            $amount=$deposits->amount;
            
                if($userDetails->Parent->balance < $amount){
                    
                    return $msg='Insufficient Balance';
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
                    return $msg='ok';

                //dd($response);
        }
        else
        {
            $update_deposits_status=Deposit::where('id','=',$request->deposit_id)->update(['status'=>$response,'reason'=>$remark,'updated_at'=>date('Y-m-d H:i:s')]);
            // return $msg='Request Rejected.';
            return $msg='Request Rejected.';
        }
                    


    }

    public function packagesDetails(Request $request)
    {
        $searchData=$request->all();
        $allBusiness =User::where('account_type','business')->get();
        $allPack=DB::table('packages')->orderBy('id','DESC')->get();
        $query=DB::table('subscriptions')
        ->join('packages as pack','subscriptions.pack_id','=','pack.id')
        ->join('business_details as bd','subscriptions.business_id','=','bd.user_id')
        ->select('subscriptions.*','pack.pack_name as pack_name','bd.*');

        if($request->pack) {		
			if($request->pack!=''){
              //  $data = User::where('email','=',strtolower($request->email))->first();
                
				$query->where('pack.id',$request->pack);
			}
		}
        if($request->status!=''){
            $query->where('subscriptions.status','=',$request->status);
            
        }

        if($request->business) {		
			if($request->business!=''){
              //  $data = User::where('email','=',strtolower($request->email))->first();
                
				$query->where('subscriptions.business_id',$request->business);
			}
		}

         
        if($request->from_date && $request->to_date) {		
			if($request->from_date!='' && $request->to_date){
				$fd=$request->from_date;
                $td=$request->to_date;
                $query->whereBetween('subscriptions.expire_date', [$fd." 00:00:00", $td." 23:59:59"]);
			}
		}

        if(Auth::user()->account_type=='superadmin')
        {           
            if($request->export)
            {
                $expData=$query->get();
                return Excel::download(new UsersExport('subpack',$expData), 'SubscribedPackageList.xlsx');             
                
            } 
            $subscribedPack=$query->paginate(10);
        }
        else{
            if($request->export)
            {
                $expData=$query->where('business_id','=',Auth::user()->id)->get();
                return Excel::download(new UsersExport('subpack',$expData), 'SubscribedPackageList.xlsx');             
                
            }
            $subscribedPack=$query->where('business_id','=',Auth::user()->id)->paginate(10);
        }
        // dd($subscribedPack);
        
        return view('wallet.packagesDetails',compact('subscribedPack','allPack','searchData','allBusiness'));

    }

    public function packageSubscription($pack_id, Request $request)
    {
      
        $validator = Validator::make(["pack_id"=>$pack_id], [
            'pack_id'=>'required'
                
        ]);

        if ($validator->fails()) {
            // return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            return redirect('packages_details')->with('error',$validator->errors());
            }

            $curPack=DB::table('subscriptions')->where([['business_id',Auth::user()->id],['status','=',1],['remain_qty','>',0],['expire_date','>',Carbon::now()->toDateTimeString()]])->first();
            if($curPack){
                // return response()->json(['status' => false, 'msg' => 'Already you have a package.', 'data'=>0]);
                return redirect('packages_details')->with('error','Already you have a package.');
            }
                 
                $user=User::where('id','=',Auth::user()->id)->first();

                $packDetails=DB::table('packages')->where('id',$pack_id)->first();
                 

                if($user->balance < $packDetails->offer_price){
                    // return response()->json([
                    //     'status'=>false,           
                    //     'data'=>0,
                    //     'msg'=>'Insufficiant wallet balance!'
                    // ]);
                    return redirect('packages_details')->with('error','Insufficiant wallet balance!');
                }
 
            try {
                    $result = DB::transaction(function () use ($request,$pack_id, $user,$packDetails) {
                    $bytes = random_bytes(40);
                    $transaction_id = "TXN".substr(bin2hex($bytes), 0, 23);

                    $exp_date=Carbon::now()->addDays($packDetails->duration)->toDateTimeString();
                    // dd($exp_date);
                    $subscription=DB::table('subscriptions')->insert(['pack_id'=>$pack_id,'business_id'=>Auth::user()->id,'purchase_price'=>$packDetails->offer_price,'expire_date'=>$exp_date,'used_qty'=>0,'remain_qty'=>$packDetails->quantity]);

                    $transaction=DB::table('transactions')->insert(['user_id'=>Auth::user()->id,'currency_id'=>1,'type'=>'Debit','source'=>'Subscription of Package','description'=>"Package Details",'amount'=>$packDetails->offer_price,'updated_balance'=>$user->balance - $packDetails->offer_price,'status'=>1,'transaction_id'=>$transaction_id]);

                    $update_balance_to_user=User::where('id','=',Auth::user()->id)->update(['balance'=>$user->balance - $packDetails->offer_price,'updated_at'=>date('Y-m-d H:i:s')]);

                });
                
                return redirect('packages_details')->with('success','Package Purchased Successfully!');
            } catch (\Exception $e) {//dd( $e->getMessage());
              
                return redirect('packages_details')->with('error',$e->getMessage());
            }
               
    }

}
    
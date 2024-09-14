<?php

namespace App\Http\Controllers;

use App\Libs\CommonHelper;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\BusinessDetail;
use App\Models\LeadFollowUp;
use App\Models\EnrollCompany;
use App\Models\IndividualUserAccess;
use App\Models\JobRole;
use App\Models\Industry;
use App\Models\DocumentType;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendBusinessLoginInfo;
use Silber\Bouncer\Database\Role;
use Session;
use Auth;
use Bouncer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require base_path("vendor/PHPMailer/PHPMailer/src/Exception.php");
require base_path("vendor/PHPMailer/PHPMailer/src/PHPMailer.php");
require base_path("vendor/PHPMailer/PHPMailer/src/SMTP.php");
require base_path("vendor/autoload.php");


class SettingController extends Controller
{
    public function createView()
    {
        /**
         * Contact Details Setting Page View
         * Input:Null.
         * Output:settings.
         */
        $this->authorize('access-manage-settings');
       
        $settings=DB::table('settings')->get();
        return view('admin.setting.create',compact('settings'));
    }

    public function SettingsStore(Request $request)
    {
        /**
         * Update Contact Details.
         * input:key, value.
         * output:Redirect with success/error.
         */
        $this->authorize('access-manage-settings');
       $this->validate($request,[
        'set_key'=>'required|string',
        'set_value'=>'required|string'
       ]);
        
            DB::table('settings')->insert(['key'=>$request->set_key,'value'=>$request->set_value]);
            
            return redirect()->back()->with('success','Settings Added Successfully.');
    }

    public function settingEdit(Request $request)
    {
       
        $setting=DB::table('settings')->where('id',$request->id)->first();
        return $setting;

    }
    public function settingUpdate(Request $request)
    {
        
        $this->validate($request,[
            'key_set'=>'required|string',
            'value_set'=>'required|string'
            
             
        ]);
        $setting=DB::table('settings')->where('id',$request->set_id)->update(['key'=>$request->key_set, 'value'=>$request->value_set, 'status'=>$request->status_set]);
        if($setting){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }

    }

    public function salaryComponent(Request $request)
    {
        /**
         * Add,Edit Salary Component and List Page View
         * input:category,status - for search
         * Output:salary_components
         */
        $this->authorize('access-manage-settings');
        $searchData=$request->all();
        $query = DB::table('salary_components')->orderBy('id','DESC');
        
        if($request->category) {		
			if($request->category!=''){
				$query->where('category',$request->category);
			}
		}

        if($request->status) {		
			if($request->status!=''){
				$query->where('status',$request->status);
			}
		}
        $salary_components=$query->paginate(10);
        return view('admin.setting.salary_component',compact('salary_components','searchData'));
    }
    
    public function salaryComponentStore(Request $request)
    {
        /**
         * Store Salary Component
         * input:category,component
         * Output:Redirect with success/error
         */

        $this->authorize('access-manage-settings');
       
         $data=DB::table('salary_components')->insert(['component'=>$request->component,'category'=>$request->category]);
        if($data)
        {
            return redirect('salary_component')->with('success','Item Added Successfully.');
        }
    }

    public function getSalComponent(Request $request)
    {
        /**
         * Edit Salary Component
         * input:id
         * Output:data
         */
        $this->authorize('access-manage-settings');
       
        $data=DB::table('salary_components')->where('id','=',$request->id)->first();
        return $data;
    }
    public function updateSalComponent(Request $request)
    {
        /**
         * Update Salary Component
         * input:comp_id,component1,category1,com_status
         * Output:msg
         */
        $this->authorize('access-manage-settings');
       //dd($request->all());
        $data=DB::table('salary_components')->where('id','=',$request->comp_id)->update(['component'=>$request->component1,'category'=>$request->category1,'status'=>$request->com_status]);
        if($data){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }
    }


    
    public function jobRole()
    {
        /**
         * Add Job Role Page View
         * input:null
         * Output:allRole
         * 
         */
        //$this->authorize('access-manage-settings');
        $industries=Industry::orderBy('name','ASC')->get();
        $allRole=JobRole::orderBy('name','ASC')->paginate(10);
        return view('admin.setting.job_role',compact('allRole','industries'));
    }

    public function jobRoleStore(Request $request)
    {
         /**
         *  Job Role Store
         * input:job_role
         * Output:Redirect with success/error.
         * 
         */

        //$this->authorize('access-manage-settings');
       $chkRole=JobRole::where('name',$request->job_role)->first();
       if($chkRole){
           return redirect()->back()->with('error','JoB Role Already Exist!!');
        }
     
         $data=JobRole::insert(['name'=>$request->job_role,'industry_id'=>$request->industry]);
        if($data)
        {
            return redirect('job_role')->with('success','Item Added Successfully.');
        }
    }

    public function getJobRoll(Request $request)
    {
        /**
         * Edit Job Role
         * input:id
         * Output:data
         */
        $this->authorize('access-manage-settings');
       
        $data=JobRole::where('id','=',$request->id)->first();
        return $data;
    }
    public function updateJobRoll(Request $request)
    {
         /**
         * Update Job Role
         * input:roll_id,roll_name,roll_status
         * Output:msg
         */
        //$this->authorize('access-manage-settings');
       //dd($request->all());
        $data=JobRole::where('id','=',$request->roll_id)->update(['name'=>$request->roll_name,'industry_id'=>$request->industry]);
        if($data){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }
    }


    public function responseReason()
    {
        /**
         * Offer Letter Reject Response Page view
         * input:null
         * output:allReason
         */
        $this->authorize('access-manage-settings');
       
        $allReason=DB::table('reschedule_reasons')->orderBy('title','DESC')->paginate(10);
        return view('admin.setting.response_reason',compact('allReason'));
    }

    public function responseReasonStore(Request $request)
    {
        /**
         * Offer Letter Reject Response Store
         * input:reason
         * output:Redirect with success/error
         */

        $this->authorize('access-manage-settings');
       
         $data=DB::table('reschedule_reasons')->insert(['title'=>$request->reason]);
        if($data)
        {
            return redirect('response_reason')->with('success','Item Added Successfully.');
        }
    }

    public function getResReason(Request $request)
    {
        /**
         * Edit Reject Response
         * input:id
         * output:data
         */

        $this->authorize('access-manage-settings');
       
        $data=DB::table('reschedule_reasons')->where('id',$request->id)->first();
        return $data;
    }
    public function updateResReason(Request $request)
    {
        /**
         * Update Reject Response
         * input:roll_id,roll_name,roll_status
         * output:msg
         */
        $this->authorize('access-manage-settings');
       
        $data=DB::table('reschedule_reasons')->where('id','=',$request->roll_id)->update(['title'=>$request->roll_name,'status'=>$request->roll_status]);
        if($data){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }
    }

    public function userAccess()
    {
        /**
         * Add User Access Page View
         * input:null,
         * output:access
         */
        $this->authorize('access-manage-settings');
       
        $access=DB::table('user_access_master')->orderBy('title','DESC')->paginate(10);
        return view('admin.setting.user_access',compact('access'));
    }

    public function userAccessStore(Request $request)
    {
        /**
         * Store User Access.
         * input:access_title,access_name
         * output:Redirect with success/error
         */

        $this->authorize('access-manage-settings');
       
         $data=DB::table('user_access_master')->insert(['title'=>$request->access_title,'name'=>$request->access_name]);
        if($data)
        {
            return redirect('user_access')->with('success','Item Added Successfully.');
        }
    }

    public function getUserAccess(Request $request)
    {
        /**
         * Edit User Access.
         * input:id
         * output:data
         */
        $this->authorize('access-manage-settings');
       
        $data=DB::table('user_access_master')->where('id','=',$request->id)->first();
        return $data;
    }
    public function updateUserAccess(Request $request)
    {
        /**
         * Update User Access.
         * input:comp_id,component1,category1,com_status
         * output:msg
         */
        $this->authorize('access-manage-settings');
       //dd($request->all());
        $data=DB::table('user_access_master')->where('id','=',$request->comp_id)->update(['component'=>$request->component1,'category'=>$request->category1,'status'=>$request->com_status]);
        if($data){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }
    }

    public function manageState()
    {
        /**
         * Add State.
         * input:null
         * output:allState
         */

        $this->authorize('access-manage-settings');
        //$allCountry=Country::get();
        $allState=State::paginate(10);
         
        return view('admin.setting.state',compact('allState'));
    }
    public function manageStateStore(Request $request)
    {
         /**
         * Store State.
         * input:state_title
         * output:Redirect with success/error
         */

        $this->authorize('access-manage-settings');
       //$allCountry=Country::get();
       $allState=State::insert(['state_title'=>$request->state_title]);
         
       return redirect('manage_state')->with('success','Item Added Successfully.');
    }
    public function getStateDetails(Request $request)
    {
        /**
         * Edit State.
         * input:id
         * output:state
         */
        $this->authorize('access-manage-settings');
       //$allCountry=Country::get();
       $state=State::where('state_id','=',$request->id)->first();
         
        return $state;
    }
    public function updateStateDetails(Request $request)
    {
         /**
         * Update State.
         * input:state_id,state_title,status
         * output:msg
         */

        $this->authorize('access-manage-settings');
       //dd($request->all());
        $data=State::where('state_id','=',$request->state_id)->update(['state_title'=>$request->state_title,'status'=>$request->status]);
        if($data){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }
    }

    public function manageCity(Request $request)
    {
        /**
         * Add City
         * input:state,status for search
         * output:allState,allCity
         */
        //$this->authorize('access-manage-settings');
        $query = City::orderBy('state_id','ASC');
        
        if($request->state) {		
			if($request->state!=''){
				$query->where('state_id',$request->state);
			}
		}
        if($request->city) {		
			if($request->city!=''){
				$query->where('name',$request->city);
			}
		}

        if($request->status) {		
			if($request->status!=''){
				$query->where('status',$request->status);
			}
		}
        $search=$request->all();
         
       //$allCountry=Country::get();
       $allState=State::get();
       $allCity=$query->paginate(10);
         
        return view('admin.setting.city',compact('allState','allCity','search'));
    }
    public function manageCityStore(Request $request)
    {   
         /**
         * Store City
         * input:state,city
         * output:Redirect with success/error
         */
        //$this->authorize('access-manage-settings');
       //$allCountry=Country::get();
       $chkCity=City::where([['name',$request->city],['state_id',$request->state]])->first();
       if($chkCity){
        return redirect()->back()->with('error','City Already Exist!!');
       }
       $allState=City::insert(['name'=>$request->city,'state_id'=>$request->state]);
         
       return redirect('manage_city')->with('success','Item Added Successfully.');
    }

    public function getCityDetails(Request $request)
    {
        /**
         * Edit City
         * input:id
         * output:city
         */
        //$this->authorize('access-manage-settings');
       //$allCountry=Country::get();
       $city=City::where('id','=',$request->id)->first();
         
        return $city;
    }
    public function updateCityDetails(Request $request)
    {
        /**
         * Update City
         * input:city_id,city,state,status
         * output:msg
         */

        
        //$this->authorize('access-manage-settings');
      
        $data=City::where('id','=',$request->city_id)->update(['name'=>$request->city,'state_id'=>$request->state,'status'=>$request->status]);
        if($data){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }
    }

    public function manageDocType()
    {
        /**
         * Add Documnet Type Page View
         * Output:allTypes
         */
        $this->authorize('access-manage-settings');
       
        $allTypes=DocumentType::orderBy('name','DESC')->paginate(10);
        return view('admin.setting.document_type',compact('allTypes'));
    }
    public function manageDocTypeStore(Request $request)
    {
        /**
         * Store Documnet Type
         * input:name
         * Output:Redirect with success/error
         */
        $this->authorize('access-manage-settings');
       
       $this->validate($request,[
        'name'=>'required|alpha|min:3'
       ],
       [
        'name.required'=>'Documnet Type Name Required',
        'name.alpha'=>'No Space allowed',
        'name.min'=>'Documnet Type Name must be minimum 3 letters',
       ]);
       $data=DocumentType::insert(['name'=>$request->name]);
         
       return redirect('manage_document_type')->with('success','Item Added Successfully.');
    }
    public function getDocTypeDetails(Request $request)
    {
        /**
         * Edit Documnet Type
         * input:id
         * Output:docType
         */

        $this->authorize('access-manage-settings');
       //$allCountry=Country::get();
       $docType=DocumentType::where('id','=',$request->id)->first();
         
        return $docType;
    }
    public function updateDocTypeDetails(Request $request)
    {
       /**
         * Update Documnet Type
         * input:type_id,type_name,type_status
         * Output:msg
         */

        $this->authorize('access-manage-settings');
       //dd($request->all());
        $data=DocumentType::where('id','=',$request->type_id)->update(['name'=>$request->type_name,'status'=>$request->type_status]);
        if($data){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }
    }

    public function mailServerSetting()
    {
       /**
         * Update Documnet Type
         * input:type_id,type_name,type_status
         * Output:msg
         */
        $msd=DB::table('business_mail_server_details')->where('business_id','=',Auth::user()->id)->first();
        //dd($msd);
         return view('admin.setting.mail_server_detail',compact('msd'));
    }
    public function mailServerSettingStore(Request $request)
    {
       /**
         * Update Documnet Type
         * input:type_id,type_name,type_status
         * Output:msg
         */
        
        $mail = new PHPMailer(true);            

        try {
            
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $request->mail_host;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $request->mail_username	;                     //SMTP username
            $mail->Password   = $request->mail_password;                               // 
            $mail->Port       = $request->mail_port;                                     
            $mail->setFrom($request->from_address, $request->from_name);
            $mail->addAddress('wasim.its@itspectrumsolutions.com', 'Wasim');     //Add a recipient
            // $mail->addAddress(Auth::user()->email, Auth::user()->first_name);     //Add a recipient
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mailContent = '<body style="background: #f6f6f6; color:#222; padding: 10px 10px; font-size: 13px; line-height: 22px;"> <div style="max-width: 700px; font-family: Poppins; z-index: 11; background: #fff; margin: 10px auto; position: relative;">     <div style="padding: 15px 0px; border-bottom: 1px solid #ccc; text-align: center;">  <img style="width: 200px;" src="https://p4.bemychoice.com/new/images/logo.png" alt="" /> </div> <div style="padding: 50px 20px;">   <h4 style="text-align: left; margin: 0px;">Hello Mr/Mrs</h4>    <p style="text-align: left; margin: 0px;">Welcome to the <strong>TrueCV</strong> platform! </p> <br> <p style="margin-bottom: 10px;">  This is Test Mail For Your SMTP Setting.   </p>   </div>   <div style="padding: 20px 20px; background: #002745; color:#fff;">   <p style="text-align: center; margin: 0px;">Thanks for connecting with us.</p>  </div>  </div>   </body>';  
            //dd($mailContent);
            $mail->Subject = 'Test Mail for SMTP Setting';
            $mail->Body    = $mailContent ;         

            $mail->send();
            if($mail->send()){
                $msd=DB::table('business_mail_server_details')->insert(['business_id'=>Auth::user()->id,'mail_host'=>$request->mail_host,'mail_port'=>$request->mail_port,'mail_username'=>$request->mail_username,'mail_password'=>$request->mail_password,'from_address'=>$request->from_address,'from_name'=>$request->from_name]);
            }
        $mailStatus= 'Test Mail Sent! & SMTP Details Store Successfully';
        return redirect('/mail_server_setting')->with('success',$mailStatus);
        } catch (Exception $e) {
            $mailStatus= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return redirect('/mail_server_setting')->with('error',$mailStatus);
        }

         
    }
    public function mailServerSettingUpdate(Request $request)
    {
       /**
         * Update Documnet Type
         * input:type_id,type_name,type_status
         * Output:msg
         */
       

        $mail = new PHPMailer(true);            

        try {
            
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $request->mail_host;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $request->mail_username	;                     //SMTP username
            $mail->Password   = $request->mail_password;                               // 
            $mail->Port       = $request->mail_port;                                     
            $mail->setFrom($request->from_address, $request->from_name);
            $mail->addAddress('wasim.its@itspectrumsolutions.com', 'Wasim');     //Add a recipient
            // $mail->addAddress(Auth::user()->email, Auth::user()->first_name);     //Add a recipient
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mailContent = '<body style="background: #f6f6f6; color:#222; padding: 10px 10px; font-size: 13px; line-height: 22px;"> <div style="max-width: 700px; font-family: Poppins; z-index: 11; background: #fff; margin: 10px auto; position: relative;">     <div style="padding: 15px 0px; border-bottom: 1px solid #ccc; text-align: center;">  <img style="width: 200px;" src="https://p4.bemychoice.com/new/images/logo.png" alt="" /> </div> <div style="padding: 50px 20px;">   <h4 style="text-align: left; margin: 0px;">Hello Mr/Mrs</h4>    <p style="text-align: left; margin: 0px;">Welcome to the <strong>TrueCV</strong> platform! </p> <br> <p style="margin-bottom: 10px;">  This is Test Mail For Your SMTP Setting.   </p>   </div>   <div style="padding: 20px 20px; background: #002745; color:#fff;">   <p style="text-align: center; margin: 0px;">Thanks for connecting with us.</p>  </div>  </div>   </body>';  
            //dd($mailContent);
            $mail->Subject = 'Test Mail for SMTP Setting';
            $mail->Body    = $mailContent ;         

            $mail->send();
            if($mail->send()){
                $msd=DB::table('business_mail_server_details')->where(['business_id'=>Auth::user()->id])->update(['mail_host'=>$request->mail_host,'mail_port'=>$request->mail_port,'mail_username'=>$request->mail_username,'mail_password'=>$request->mail_password,'from_address'=>$request->from_address,'from_name'=>$request->from_name]);
            }
        $mailStatus= 'Test Mail Sent! & SMTP Details Store Successfully';
        return redirect('/mail_server_setting')->with('success',$mailStatus);
        } catch (Exception $e) {
            $mailStatus= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return redirect('/mail_server_setting')->with('error',$mailStatus);
        }


        //  return redirect('/mail_server_setting')->with('success','update ok');
    }
         
    
    public function designationView()
    {
        /**
         * Add Job Role Page View
         * input:null
         * Output:allRole
         * 
         */
        $this->authorize('access-manage-settings');
       
        $allDesg=DB::table('designations')->orderBy('name','DESC')->paginate(10);
        return view('admin.setting.designation',compact('allDesg'));
    }

    public function designationStore(Request $request)
    {
         /**
         *  Designation  Store
         * input:designation
         * Output:Redirect with success/error.
         * 
         */

        $this->authorize('access-manage-settings');
       
         $data=DB::table('designations')->insert(['name'=>$request->designation]);
        if($data)
        {
            return redirect('designation')->with('success','Designation Added Successfully.');
        }
    }

    public function getDesignation(Request $request)
    {
        /**
         * Edit Job Role
         * input:id
         * Output:data
         */
        $this->authorize('access-manage-settings');
       
        $data=DB::table('designations')->where('id','=',$request->id)->first();
        return $data;
    }
    public function updateDesignation(Request $request)
    {
         /**
         * Update Job Role
         * input:roll_id,roll_name,roll_status
         * Output:msg
         */
        $this->authorize('access-manage-settings');
       //dd($request->all());
        $data=DB::table('designations')->where('id','=',$request->desg_id)->update(['name'=>$request->desg_name,'status'=>$request->desg_status]);
        if($data){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }
    }
    public function empRangeView()
    {
        /**
         * Add Job Role Page View
         * input:null
         * Output:allRole
         * 
         */
        $this->authorize('access-manage-settings');
       
        $allRange=DB::table('no_of_employee_range')->orderBy('range_start','DESC')->paginate(10);
        return view('admin.setting.employeeRange',compact('allRange'));
    }

    public function empRangeStore(Request $request)
    {
         /**
         *  Designation  Store
         * input:designation
         * Output:Redirect with success/error.
         * 
         */

        $this->authorize('access-manage-settings');
       
         $data=DB::table('no_of_employee_range')->insert(['range_start'=>$request->range_start,'range_end'=>$request->range_end]);
        if($data)
        {
            return redirect('emp_range')->with('success','Employee Range Added Successfully.');
        }
    }

    public function getempRange(Request $request)
    {
        /**
         * Edit Job Role
         * input:id
         * Output:data
         */
        $this->authorize('access-manage-settings');
       
        $data=DB::table('no_of_employee_range')->where('id','=',$request->id)->first();
        return $data;
    }
    public function updateempRange(Request $request)
    {
         /**
         * Update Job Role
         * input:roll_id,roll_name,roll_status
         * Output:msg
         */
        $this->authorize('access-manage-settings');
       //dd($request->all());
        $data=DB::table('no_of_employee_range')->where('id','=',$request->range_id)->update(['range_start'=>$request->range_start,'range_end'=>$request->range_end,'status'=>$request->status]);
        if($data){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }
    }

    public function packagesView(Request $request){
        
         

        $allPack=DB::table('packages')->orderBy('id','DESC')->get();
        return view('admin.setting.packages',compact('allPack'));

    }
    public function packageStore(Request $request){

        $this->validate($request,[
            'pack_name'=>'required',
            'price'=>'required|numeric',
            'off_price'=>'required|numeric',
            'duration'=>'required|numeric',
            'quantity'=>'required|numeric'
             
        ]);
        $allPack=DB::table('packages')->insert(['pack_name'=>$request->pack_name, 'price'=>$request->price, 'offer_price'=>$request->off_price, 'duration'=>$request->duration, 'quantity'=>$request->quantity,'description'=>$request->description]);
        return redirect('/packages');

    }
    public function packageEdit(Request $request){
       
        $pack=DB::table('packages')->where('id',$request->id)->first();
        return $pack;

    }
    public function packageUpdate(Request $request){
        $this->validate($request,[
            'pack_name'=>'required',
            'price'=>'required|numeric',
            'off_price'=>'required|numeric',
            'duration'=>'required|numeric',
            'quantity'=>'required|numeric'
             
        ]);
        $pack=DB::table('packages')->where('id',$request->pack_id)->update(['pack_name'=>$request->pack_name, 'price'=>$request->price, 'offer_price'=>$request->off_price, 'duration'=>$request->duration, 'quantity'=>$request->quantity,'description'=>$request->description,'status'=>$request->pack_status]);
        if($pack){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }

    }

    public function bankDetailsView(Request $request){
        
         

        $allBank=DB::table('bank_details')->orderBy('id','DESC')->get();
        return view('admin.setting.bankDetails',compact('allBank'));

    }
    public function bankDetailsStore(Request $request){

        $this->validate($request,[
            'bank_name'=>'required',
            'ac_no'=>'required|numeric',
            'ac_name'=>'required',
            'ifsc'=>'required',
            'branch_code'=>'nullable',
            'branch_address'=>'nullable'
             
        ]);
        $allPack=DB::table('bank_details')->insert(['bank_name'=>$request->bank_name, 'ac_no'=>$request->ac_no, 'ac_name'=>$request->ac_name,  'ifsc'=>$request->ifsc, 'branch_code'=>$request->branch_code, 'branch_address'=>$request->branch_address]);
        return redirect('/bank_details');

    }
    public function bankEdit(Request $request){
       
        $bank=DB::table('bank_details')->where('id',$request->id)->first();
        return $bank;

    }
    public function bankUpdate(Request $request){
        $this->validate($request,[
            'bank_name'=>'required',
            'ac_no'=>'required|numeric',
            'ifsc'=>'required',
            'branch_code'=>'nullable',
            'branch_address'=>'nullable'
             
        ]);
        $bank=DB::table('bank_details')->where('id',$request->bank_id)->update(['bank_name'=>$request->bank_name, 'ac_no'=>$request->ac_no, 'ifsc'=>$request->ifsc, 'branch_code'=>$request->branch_code, 'branch_address'=>$request->branch_address,'status'=>$request->bank_status]);
        if($bank){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }

    }

    public function metaDataView(Request $request){
        
         

        $metaData=DB::table('meta_data')->orderBy('id','DESC')->get();
        return view('admin.setting.meta_data',compact('metaData'));

    }
    public function metaDataStore(Request $request){

        $this->validate($request,[
            'url'=>'required',
            'title'=>'required',
            'description'=>'required',
            'keywords'=>'required'
            
             
        ]);
        $metaData=DB::table('meta_data')->insert(['url'=>$request->url, 'meta_title'=>$request->title, 'meta_description'=>$request->description,  'meta_keywords'=>$request->keywords]);
        return redirect('/meta_data_details');

    }
    public function metaDataEdit(Request $request){
       
        $metaData=DB::table('meta_data')->where('id',$request->id)->first();
        return $metaData;

    }
    public function metaDataUpdate(Request $request){
        $this->validate($request,[
            'meta_url'=>'required',
            'meta_title'=>'required',
            'meta_description'=>'required',
            'meta_keywords'=>'required'
             
        ]);
        $metaData=DB::table('meta_data')->where('id',$request->meta_id)->update(['url'=>$request->meta_url, 'meta_title'=>$request->meta_title, 'meta_description'=>$request->meta_description,  'meta_keywords'=>$request->meta_keywords,'status'=>$request->meta_status]);
        if($metaData){
            return $msg="ok";
        }
        else{
        return $msg="Something was wrong!!";
        }

    }

    public function manageKyc()
    {
        /**
         * Add City
         * input:state,status for search
         * output:allState,allCity
         */
        $this->authorize('access-manage-settings');
        $allKyc = DB::table('verification_types')->orderBy('id','ASC')->get();
        
        return view('admin.setting.kyc',compact('allKyc'));
    }
    public function updateKyc(Request $request)
    {
        /**
         * Add City
         * input:state,status for search
         * output:allState,allCity
         */
        $this->authorize('access-manage-settings');
//dd(1);

        try {
            DB::table('verification_types')->truncate();
            for($i=1;$i<=3;$i++){

                $title = 't_'.$i;                
                $name = 'n_'.$i;                
                $amount = 'a_'.$i;                
                DB::table('verification_types')->insert(['title'=>$request->{$title},'name'=>$request->{$name},'amount'=>$request->{$amount}]);
               
            }

             
		   
			return redirect('manageKyc')->with('success','KYC Types has been successfully Updated.'); 
			
			
		} catch (\Exception $exception) {
			Log::error($exception);
            flash()->error($exception->getMessage());
        }
        return redirect()->back();


       
    }
    public function manageOfferLetter()
    {
        /**
         * Add City
         * input:state,status for search
         * output:allState,allCity
        */
        $role=Auth::user()->account_type;
        $query=DB::table('offer_letter_templates')->orderBy('id','ASC');
        if(($role=='superadmin'))
        {
            $allTmp =$query->get();
        }
        else if(($role=='business'))
        {
            $allTmp =$query->where('business_id',Auth::user()->id)->get();
        }
        else if(($role=='hr'))
        {
            $allTmp =$query->where('business_id',Auth::user()->parent_id)->get();
        }
    
       
        
        return view('admin.setting.offer-letter',compact('allTmp'));
    }
    public function manageOfferLetterUpdate(Request $request)
    {
        /**
         * Add City
         * input:state,status for search
         * output:allState,allCity
        */
        $this->validate($request,[
            'letter_head'=>'required|mimes:jpg,jpeg',
             
            'temp_name'=>'required'
             
        ],[
            'temp_name.required'=>'Name required',

            'letter_head.required'=>'File required',
            'letter_head.mimes'=>'File type must be jpg,jpeg',

        ]);

        $role=Auth::user()->account_type;
        
        if(($role=='superadmin') || ($role=='business'))
        {
            $business_id=Auth::user()->id;
        }
         
       else if(($role=='hr'))
        {
            $business_id=Auth::user()->parent_id;
        }
        else{
            return redirect()->back()->with('erroe','You have No Permission for this'); 
        }
        // dd($request->all());
        if($request->letter_head!=Null){
            $letter_head = $request->file('letter_head')
                            ->store('templates');
        }
        DB::table('offer_letter_templates')->insert(['business_id'=>$business_id,'name'=>$request->temp_name,'letter_head'=>$letter_head,'description'=>$request->description]);
        return redirect()->back()->with('success','Template Added Successfully.'); 
    }

    public function manageIndustry(Request $request)
    {
        /**
         * Add City
         * input:state,status for search
         * output:allState,allCity
         */
        //$this->authorize('access-manage-settings');
       
        
        // if($request->state) {		
		// 	if($request->state!=''){
		// 		$query->where('state_id',$request->state);
		// 	}
		// }

        // if($request->status) {		
		// 	if($request->status!=''){
		// 		$query->where('status',$request->status);
		// 	}
		// }
       //$allCountry=Country::get();
       $industries=Industry::orderBy('name','ASC')->get();
        
         
        return view('admin.setting.industries',compact('industries'));
    }
    public function manageIndustryStore(Request $request)
    {
         /**
         * Store State.
         * input:state_title
         * output:Redirect with success/error
         */

        $this->authorize('access-manage-settings');
       //$allCountry=Country::get();
       $industry=Industry::where('name',$request->industry)->first();
       if($industry){
        return redirect('manage_industries')->with('error','Industry Already Exist');
       }
       Industry::insert(['name'=>$request->industry]);
         
       return redirect('manage_industries')->with('success','Industry Added Successfully.');
    }


}
<?php

namespace App\Http\Controllers\API;

use \App\Http\Controllers\Controller;
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
use App\Models\DocumentType;
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
    public function allCountry(){
       
       
        $countries=DB::table('countries')->get();
        return response()->json([
            'status'=>true,           
            'data'=>$countries,
            'success'=>1
        ]);
       // return view('admin.setting.create',compact('settings'));
    }

    public function createView(){
        $this->authorize('access-manage-settings');
       
        $settings=DB::table('settings')->get();
        return view('admin.setting.create',compact('settings'));
    }

     

    public function salaryComponent(Request $request)
    {
        // $this->authorize('access-manage-settings');
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
        $salary_components=$query->get();
        return response()->json([
            'status'=>true,           
            'data'=>$salary_components,
            'success'=>1
        ]);
        // return view('admin.setting.salary_component',compact('salary_components'));
    }
    
     

     


    
    public function jobRole()
    {
        // $this->authorize('access-manage-settings');
       
        $allRole=JobRole::orderBy('name','DESC')->get();
        // return view('admin.setting.job_role',compact('allRole'));
        return response()->json([
            'status'=>true,           
            'data'=>$allRole,
            'success'=>1
        ]);
    }

     

     
     


    public function responseReason()
    {
       
       
        $allReason=DB::table('reschedule_reasons')->orderBy('title','DESC')->get();
        // return view('admin.setting.response_reason',compact('allReason'));
        return response()->json(['status' => true, 'success' => 'Success', 'data'=>$allReason]);
    }

    
    public function manageState()
    {
        
       //$allCountry=Country::get();
       $allState=State::get();
    //    return response()->json(['status' => true, 'success' => 'Success', 'data'=>$allState]);
       return response()->json(['status' => true, 'success' => 'Success', 'data'=>$allState]);
        // return view('admin.setting.state',compact('allState'));
    }
     

    public function manageCity(Request $request)
    {
        //$this->authorize('access-manage-settings');
        $query = City::orderBy('state_id','ASC');
        
        if($request->state) {		
			if($request->state!=''){
				$query->where('state_id',$request->state);
			}
		}

        if($request->status) {		
			if($request->status!=''){
				$query->where('status',$request->status);
			}
		}
       //$allCountry=Country::get();
       $allState=State::get();
       $allCity=$query->get();
       return response()->json(['status' => true, 'success' => 'Success', 'data'=>$allCity]);
         
        // return view('admin.setting.city',compact('allState','allCity'));
    }
     

    public function manageDocType()
    {
        // $this->authorize('access-manage-settings');
       
        $allTypes=DocumentType::orderBy('name','ASC')->get();
        return response()->json(['status' => true, 'msg' => 'Success', 'data'=>$allTypes]);
        // return view('admin.setting.document_type',compact('allTypes'));
    }
      

   
     

    public function designationView()
    {
        /**
         * Add Job Role Page View
         * input:null
         * Output:allRole
         * 
         */
  
       
        $allDesg=DB::table('designations')->orderBy('name','DESC')->get();
        return response()->json([
            'status'=>true,           
            'data'=>$allDesg,
            'msg'=>'ok'
        ]);
    }
    public function empRangeView(Request $request)
    {
        /**
         * Add Job Role Page View
         * input:null
         * Output:allRole
         * 
         */
         
         $query = DB::table('no_of_employee_range')->orderBy('range_start','ASC');
         if($request->id) {		
             if($request->id!=''){
                 $query->where('id',$request->id);
             }
         }
         $allRange = $query->where('status',1)->get();
        //$allRange=DB::table('no_of_employee_range')->where('status',1)->orderBy('range_start','DESC')->get();
        return response()->json([
            'status'=>true,           
            'data'=>$allRange,
            'msg'=>'ok'
        ]);
    }
    
    public function mailServerSetting()
    {
       /**
         * Update Documnet Type
         * input:type_id,type_name,type_status
         * Output:msg
         */
        $msd=DB::table('business_mail_server_details')->where('business_id','=',Auth::user()->id)->first();
        // dd($msd);
        return response()->json([
            'status'=>true,           
            'data'=>$msd,
            'msg'=>'ok'
        ]);
        // return view('admin.setting.mail_server_detail',compact('msd'));
    }
    public function mailServerSettingStore(Request $request)
    {
       /**
         * Update Documnet Type
         * input:type_id,type_name,type_status
         * Output:msg
         */
        $validator = Validator::make($request->all(),  [
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_username'=>'required|string',
            'mail_password'=>'required|string',
            'from_address'=>'required|string',
            'from_name'=>'required|string',
                //'doc_file'=>'mimes:jpg,jpeg|max:1048'
                
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }

        


        $mail = new PHPMailer(true);            

        try {
            
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $request->mail_host;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $request->mail_username	;                     //SMTP username
            $mail->Password   = $request->mail_password;                               // 
            $mail->Port       = 587;                                     
            $mail->setFrom($request->from_address, $request->from_name);
            // $mail->addAddress('wasim.its@itspectrumsolutions.com', 'Wasim');     //Add a recipient
            $mail->addAddress(Auth::user()->email, Auth::user()->first_name);     //Add a recipient
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mailContent = '<body style="background: #f6f6f6; color:#222; padding: 10px 10px; font-size: 13px; line-height: 22px;"> <div style="max-width: 700px; font-family: Poppins; z-index: 11; background: #fff; margin: 10px auto; position: relative;">     <div style="padding: 15px 0px; border-bottom: 1px solid #ccc; text-align: center;">  <img style="width: 200px;" src="https://p4.bemychoice.com/new/images/logo.png" alt="" /> </div> <div style="padding: 50px 20px;">   <h4 style="text-align: left; margin: 0px;">Hello Mr/Mrs</h4>    <p style="text-align: left; margin: 0px;">Welcome to the <strong>Recrueet</strong> platform! </p> <br> <p style="margin-bottom: 10px;">  This is Test Mail For Your SMTP Setting.   </p>   </div>   <div style="padding: 20px 20px; background: #002745; color:#fff;">   <p style="text-align: center; margin: 0px;">Thanks for connecting with us.</p>  </div>  </div>   </body>';   

            $mail->Subject = 'Test Mail for SMTP Setting';
            $mail->Body    = $mailContent ;         

          
            $mail->send();
            if($mail->send()){
                $msd=DB::table('business_mail_server_details')->insert(['business_id'=>Auth::user()->id,'mail_host'=>$request->mail_host,'mail_port'=>$request->mail_port,'mail_username'=>$request->mail_username,'mail_password'=>$request->mail_password,'from_address'=>$request->from_address,'from_name'=>$request->from_name]);
            }
        $mailStatus= 'Test Mail Sent! & SMTP Details Store Successfully';
        return response()->json([
            'status'=>true,           
            'data'=>1,
            'msg'=>$mailStatus
        ]);
        } catch (Exception $e) {
            $mailStatus= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return response()->json([
                'status'=>false,           
                'data'=>1,
                'msg'=>$mailStatus
            ]);

        }




    
        // return redirect('/mail_server_setting')->with('success','success ok');
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
            $mail->Port       = 587;                                     
            $mail->setFrom($request->from_address, $request->from_name);
            // $mail->addAddress('wasim.its@itspectrumsolutions.com', 'Wasim');     //Add a recipient
            $mail->addAddress(Auth::user()->email, Auth::user()->first_name);     //Add a recipient
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mailContent = '<body style="background: #f6f6f6; color:#222; padding: 10px 10px; font-size: 13px; line-height: 22px;"> <div style="max-width: 700px; font-family: Poppins; z-index: 11; background: #fff; margin: 10px auto; position: relative;">     <div style="padding: 15px 0px; border-bottom: 1px solid #ccc; text-align: center;">  <img style="width: 200px;" src="https://p4.bemychoice.com/new/images/logo.png" alt="" /> </div> <div style="padding: 50px 20px;">   <h4 style="text-align: left; margin: 0px;">Hello Mr/Mrs</h4>    <p style="text-align: left; margin: 0px;">Welcome to the <strong>Recrueet</strong> platform! </p> <br> <p style="margin-bottom: 10px;">  This is Test Mail For Your SMTP Setting.   </p>   </div>   <div style="padding: 20px 20px; background: #002745; color:#fff;">   <p style="text-align: center; margin: 0px;">Thanks for connecting with us.</p>  </div>  </div>   </body>';   

            $mail->Subject = 'Test Mail for SMTP Setting';
            $mail->Body    = $mailContent ;                

            $mail->send();

            if($mail->send()){
                $msd=DB::table('business_mail_server_details')->where(['business_id'=>Auth::user()->id])->update(['mail_host'=>$request->mail_host,'mail_port'=>$request->mail_port,'mail_username'=>$request->mail_username,'mail_password'=>$request->mail_password,'from_address'=>$request->from_address,'from_name'=>$request->from_name]);
            }
        $mailStatus= 'Test Mail Sent! & SMTP Details Store Successfully';
        return response()->json([
            'status'=>true,           
            'data'=>1,
            'msg'=>$mailStatus
        ]);
        } catch (Exception $e) {
            $mailStatus= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return response()->json([
                'status'=>false,           
                'data'=>1,
                'msg'=>$mailStatus
            ]);
        }

        //  return redirect('/mail_server_setting')->with('success','update ok');
    }

    public function packagesView(Request $request)
    {   
        
        $query = DB::table('packages')->orderBy('id','ASC');
        if($request->id) {		
            if($request->id!=''){
                $query->where('id',$request->id);
            }
        }
        $allPack=$query->get();
        return response()->json([
            'status'=>true,           
            'data'=>$allPack,
            'msg'=>'success'
        ]);

    }
    public function bankDetailsView(Request $request){
        
         
        $allBank=DB::table('bank_details')->where('status',1)->orderBy('id','DESC')->get();
        // return view('admin.setting.bankDetails',compact('allBank'));
        return response()->json([
            'status'=>true,           
            'data'=>$allBank,
            'msg'=>'success'
        ]);

        
    }
    public function userAccess()
    {
        /**
         * Add User Access Page View
         * input:null,
         * output:access
         */
       
       
        $access=DB::table('user_access_master')->orderBy('title','DESC')->get();
        return response()->json([
            'status'=>true,           
            'data'=>$access,
            'msg'=>'success'
        ]);
    }
    public function allCourse(Request $request)
    {
               
        $query = DB::table('course_masters')->orderBy('course_name','ASC');
        if($request->education_master_id) {		
            if($request->education_master_id!=''){
                $query->where('education_master_id',$request->education_master_id);
            }
        }
        $allCourse=$query->get()->toArray();
        // $allCourse += array('id'=> 9999,['course_name']=> "Other",['education_master_id']=> $request->education_master_id);
        $adCourse=array_push($allCourse, array("id" => 9999, "course_name" => "Other", "education_master_id" => $request->education_master_id));    
         
        
       // dd($allCourse);
        return response()->json([
            'status'=>true,           
            'data'=>$allCourse,
            'msg'=>'success'
        ]);
    }
    public function allSpecialization(Request $request)
    {
               
        $query = DB::table('specialization_masters')->orderBy('name','ASC');
        if($request->course_master_id) {		
            if($request->course_master_id!=''){
                $query->where('course_master_id',$request->course_master_id);
            }
        }
        $allSpecialization=$query->get();
        
        return response()->json([
            'status'=>true,           
            'data'=>$allSpecialization,
            'msg'=>'success'
        ]);
    }
}
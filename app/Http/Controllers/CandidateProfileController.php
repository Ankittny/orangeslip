<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
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
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\OfferLetter;
use App\Models\CandidateFollowUp;
use App\Models\CheckedOfferLetter;
use App\Models\BusinessReviewDetail;
use App\Models\JobRole;
use DB;
use Auth;
use Session;
use PDF;
use Form;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferLetterGenerated;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CandidateProfileController extends Controller
{
    public function verify_email_user($token)
    {
        /**
         * Check Email Verified or Not
         * input:token
         * output:Redirect with success/error
         */

        $user = User::where("verification_token", $token)->first();

        if($user == NULL){

            abort(404);

        }
        $user->is_email_verified = 1;

        $user->save();
        //flash()->success('Your email has been successfully verified !');

        return redirect('login')->with('success','Your email has been successfully verified !');

    }

    public function candidateProfile()
    {
        /**
         * Candidate Profile Page View
         * input:null
         * Output:candidate,education_details,profession_details,skills,languages,hobbies,states,cities,job_role.
         */
        $id=Auth::user()->id;      
         
        $candidate=CandidateDetail::where('user_id','=',$id)->first();
        //dd($candidate->id);
        $job_role=JobRole::orderBy('name','ASC')->get();
        $education_details=CandidateEducationDetail::where('candidate_id','=',$candidate->id)->get();
        $profession_details=CandidateProfessionalDetail::where('candidate_id','=',$candidate->id)->get();
        $skills=CandidateOtherDetail::where([['candidate_id','=',$candidate->id],['type','=','skill']])->get();
        $languages=CandidateOtherDetail::where([['candidate_id','=',$candidate->id],['type','=','language']])->get();
        $hobbies=CandidateOtherDetail::where([['candidate_id','=',$candidate->id],['type','=','hobby']])->get();
        $states=State::where('country_id','=',$candidate->country)->get();
        $cities=City::where('state_id','=',$candidate->state)->orderby('name','ASC')->get();
        $other_details=CandidateOtherDetail::where('candidate_id','=',$candidate->id)->get();
        return View::make('candidate.index',compact('candidate','education_details','profession_details','skills','languages','hobbies','states','cities','job_role','other_details'));
    }

    public function candidateUncheckOffer()
    {
        /**
         * Unverified Offer List Page View
         * input : auth email
         * Output: offerletters
         */
     
        $email=Auth::user()->email;                
        $candidates=CandidateDetail::where([['email','=',$email],['user_id','=',0]])->orderBy('id','DESC')->pluck('id')->toArray();      
        // $offerletters=OfferLetter::whereIn('candidate_id',$candidates)->where('is_checked','=',0)->orderBy('id','DESC')->get();     

        $offerletters=DB::table('offer_letters as ol')     
        ->leftJoin('candidate_details as cd', 'ol.candidate_id', '=', 'cd.id')        
        ->leftJoin('job_roles', 'ol.post', '=', 'job_roles.id')
        ->leftJoin('business_details as emp', 'ol.business_id', '=', 'emp.user_id')    
        // ->leftJoin('business_review_details as brd', 'brd.business_id', '=', 'ol.business_id')
        ->leftJoin('business_review_details as brd', function($brd)
                    {
                        $brd->on('brd.business_id', '=', 'ol.business_id');
                        $brd->where('brd.user_id','=', Auth::user()->id);
                    })
                   
        
        // ->select('ol.*','cd.candidate_code as candidateCode','cd.photo as candidatePhoto','emp.business_name as companyName','emp.user_id as businessId','ol.is_accepted as offerStatus')
        ->select( 'ol.*','brd.review as review','brd.comment as comment','cd.candidate_code as candidateCode','cd.photo as candidatePhoto','ol.place_of_joining as placeOfJoining','ol.joining_date as joiningDate','ol.time_of_joining as timeOfJoining','ol.annual_ctc as annualCtc','emp.business_name as companyName','emp.user_id as businessId')
        ->whereIn('ol.candidate_id',$candidates)
        
        // ->where('ol.is_checked','=',0)
        ->orderBy('ol.id','DESC')
        ->get();
        // dd($offerletters);

        return view('candidate.uncheck_offer_list',compact('offerletters'));
    }

    public function isChecked(Request $request)
    {
        /**
         * update status as Verifiy Offer Letter by candidate
         * input:offerletter_id
         * output:msg
         */
        $offerletters=OfferLetter::where('id','=',$request->offerletter_id)->update(['is_checked'=>1]);
        $create_data=CheckedOfferLetter::insert(['user_id'=>Auth::user()->id,'offer_letter_id'=>$request->offerletter_id]);
        if($create_data)
        {
            return $msg=1;
        }
        else{
            return $msg=0;
        }

    }

    public function candidateOffer(Request $request)
    {        
    
        /**
         * Verified Offer Letter List Page View
         * input:Null
         * Output:offerletters
         */
        $offerletters=CheckedOfferLetter::where('user_id','=',Auth::user()->id)->get();
       
        return view('candidate.offer_list',compact('offerletters'));
    }

    // public function candidateView(Request $request)
    // {
    //     $id=base64_decode($request->id);
        
    //     $this->authorize("access-manage-candidate");
    //     $candidate=CandidateDetail::where('id','=',$id)->first();
    //     $education_details=CandidateEducationDetail::where('candidate_id','=',$id)->get();
    //     $profession_details=CandidateProfessionalDetail::where('candidate_id','=',$id)->get();
    //     $skills=CandidateOtherDetail::where([['candidate_id','=',$id],['type','=','skill']])->get();
    //     $languages=CandidateOtherDetail::where([['candidate_id','=',$id],['type','=','language']])->get();
    //     $hobbies=CandidateOtherDetail::where([['candidate_id','=',$id],['type','=','hobby']])->get();
    //     return View::make('admin.candidate.candidateview',compact('candidate','education_details','profession_details','skills','languages','hobbies'));
    // }
    /*
    public function addPersonal(Request $request)
    {
         
         # Update Personal Details
         # input:candidate_id,cname,email,gender,job_role1,state,city,phone,total_experience,dob,religion,fname,mname,sname,present_address,permanent_address.
         # output: msg
    
      
        $id=$request->candidate_id;
        $this->validate($request, [
            'cname' => 'required|string|min:3',
            'email' => 'required|email|check_mail|unique:users,email,'.Auth::user()->id,
            'gender' => 'required|alpha',            
            'state' => 'required',
            'city' => 'required',
            'phone'=>'required|numeric|digits_between:6,15',
            'total_experience'=>'required',
            'job_role1'=>'required',
            'dob'=>'required|date_format:Y-m-d|before:today'
        ],
        [
            'cname.required'=>'Name Required',
            'cname.min'=>'Name must be minimum 3 letters',
            'phone.digits_between'=>'Phone Number should be of 6 to 15 digits',
            'total_experience.required'=>'Total Experience Required',
            'dob.required'=>'DOB Required',
            'dob.date_format'=>'DOB Date format must be DD-MM-YYYY',
            'dob.before'=>'DOB must be before today',

            'check_mail'=>'Invalid Email Id',
             
        ]); 
        //dd($request->job_role1);
        $user=User::where('id',Auth::user()->id)->update(['first_name'=>$request->cname,'email'=>$request->email]);

        $data=CandidateDetail::where('id','=',$id)->update(['name'=>$request->cname,'email'=>$request->email,'phone'=>$request->phone,'gender'=>$request->gender,'state'=>$request->state,'city'=>$request->city,'religion'=>$request->religion,'dob'=>$request->dob,'fathers_name'=>$request->fname,'mothers_name'=>$request->mname,'spouse_name'=>$request->sname,'present_address'=>$request->present_address,'permanent_address'=>$request->permanent_address,'job_role'=>$request->job_role1,'total_experience'=>$request->total_experience]); 

        if($data)
        {
            return $msg=1;
        }
        else{
            return $msg=2;
        }
        
    }   

    public function addEducation(Request $request)
    {  
        
         # Add Education Details .
         # input:candidate_id,institute,degree,year_of_passing,marks,percentage.
         # Output:msg.
         

        $this->validate($request, [
            'institute' => 'required|regex:/^[a-zA-Z. ]+$/u|min:3',
            'degree' => 'required|regex:/^[a-zA-Z. ]+$/u|min:3',
            'year_of_passing'=>'required|numeric|digits:4|gt:1900',
            'marks'=>'required|string',
            'percentage'=>'required|numeric|between:0,99.99|gt:0',               
        ],
        [
            'institute.required'=>'Institute Name Required',
            'institute.min'=>'Institute Name should be minimum 3 letter',
            'institute.regex'=>'Institute Name should be in alphabets only.',

            'degree.required'=>'Degree Name Required',
            'degree.min'=>'Degree Name should be minimum 3 letter',
            'degree.regex'=>'Degree Name should be in alphabets only.',

            'year_of_passing.required'=>'Passing Year Required',
            'year_of_passing.digits'=>'Passing Year must be 4 digits',
            'year_of_passing.numeric'=>'Passing Year must be in digits',
            'year_of_passing.gt'=>'Passing Year must be after 1950',

            'percentage.required'=>'Percentage  Required',
            'percentage.between'=>'Percentage must be in 0 to 99.99',
            'percentage.numeric'=>'Percentage must be in digits',
            'percentage.gt'=>'Percentage must be greater then 0',

            'marks.required'=>'Marks  Required',
             
            'marks.string'=>'Marks must be String',
            
        ]);

        $data=CandidateEducationDetail::insert(['candidate_id'=>$request->candidate_id,'institute_name'=>$request->institute,'degree'=>$request->degree,'year_of_passing'=>$request->year_of_passing,'marks'=>$request->marks,'percentage'=>$request->percentage]);
            
        if($data)
        {
            return $msg=1;
        }
        else{
            return $msg=2;
        }
                 
    }

    public function delEdu($id)
    {
         
         # Delete Education
         # input:id(Education)
         # output:redirect with success/error
         
        $education=CandidateEducationDetail::where('id','=',$id)->first();
               $education->delete();      
       
        return redirect()->back()->with('success','Education Dtails Deleted Successfully');
    }

    public function addProfession(Request $request)
    {
        
         # Add Professional Details.
         # Input:candidate_id,company,job_role,from_date,to_date,description.
         # Output: msg.
         
        $this->validate($request, [
            'company' => 'required|regex:/^[a-zA-Z0-9. ]+$/u|min:3',
            'job_role' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'from_date'=>'required|date_format:Y-m-d|before:today',
            'to_date'=>'nullable|date_format:Y-m-d|after:from_date'       
        ],
        [
            'company.required'=>'Company Name Required',
            'company.min'=>'Company Name must be minimum 3 letter',
            'company.regex'=>'Company Name must be in Alpha Numeric only.',
            'job_role.required'=>'Job Role Required',
            'job_role.min'=>'Job Role must be minimum 3 letter',
            'job_role.regex'=>'Job Role must be in alphabets only.',
            'from_date.date_format'=>'From Date format must be DD-MM-YYYY',
            'from_date.required'=>'From Date Required',
            'from_date.before'=>'From Date must be before today',
            'to_date.date_format'=>'To Date format must be DD-MM-YYYY',             
            'to_date.before'=>'To Date must be after From Date'
        ]);                
        
        $data=CandidateProfessionalDetail::insert(['candidate_id'=>$request->candidate_id,'company_name'=>$request->company,'job_role'=>$request->job_role,'from_date'=>$request->from_date,'to_date'=>$request->to_date,'description'=>$request->description]);           
    
        if($data)
        {
            return $msg=1;
        }
        else{
            return $msg=2;
        }
    }
 
    public function delProf($id)
    {       
        
        # Delete Profession
        # input:id(profession id)
        # output: Redirect with success/error.
        

        $profession=CandidateProfessionalDetail::where('id','=',$id)->first();
        $profession->delete();
        return redirect()->back()->with('success','Professional  Dtails Deleted Successfully');
    }
    */
    public function addLanguage(Request $request)
    {
         
         # Add Language
         # input:candidate_id,language,read,write,speak
         # output: msg.
          
        $this->validate($request, [
            'language' => 'required|string'           
        ]);  
         //dd($request->all());
        $id=$request->candidate_id;
         
        if($request->read=="true")
        {
            $read='Read';
        }
        else{
            $read='';
        }
        if($request->write=="true")
        {
            $write='Write';
        }
        else{
            $write='';
        }
        if($request->speak=="true")
        {
            $speak='Speak';
        }
        else{
            $speak='';
        }
       
        $ability=$read.' '.$write.' '.$speak ;
       if(($read!='') || ($speak!='') || ($write!=''))
       {
        $data=CandidateOtherDetail::insert(['candidate_id'=>$id,'type'=>'language','value'=>$request->language,'description'=>$ability]);
        if($data)
        {
            return $msg=1;
        }
        else{
            return $msg="Something was wrong";
        }
       }
       else{
        return $msg="Please Select ability!";
       }
        
        
         
    }
    
    /*
    public function addSkills(Request $request)
    {
       
         # Add Skill
         # input:candidate_id,title,description
         # output: msg.
         
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string'           
        ]);  
        //dd($request->all());
        $id=$request->candidate_id;
       
        $data=CandidateOtherDetail::insert(['candidate_id'=>$id,'type'=>'skill','value'=>$request->title,'description'=>$request->description]);
        if($data)
        {
            return $msg=1;
        }
        else{
            return $msg=2;
        }
         
    }

    public function addHobbies(Request $request)
    {
        
         # Add Hobbies
         # input:candidate_id,title,description
         # output: msg.
         
        //dd($request->all());
        $this->validate($request, [
            'title' => 'required|string'   
        ]);  
        $id=$request->candidate_id;
       
        $data=CandidateOtherDetail::insert(['candidate_id'=>$id,'type'=>'hobby','value'=>$request->title,'description'=>$request->description]);
        if($data)
        {
            return $msg=1;
        }
        else{
            return $msg=2;
        }
         
    }    
   
    public function delOth($id)
    {         
        
         # Delete Other Details
         # Input: id (Other Details).
         # Output:Redirect with success/error.
         
        $others=CandidateOtherDetail::where('id','=',$id)->first();
               $others->delete();      
        $item=$others->type;
        return redirect()->back()->with('success',"$item Dtails Deleted Successfully");
    }
    public function uploadFile(Request $request)
    {      
        
        # Upload Photo and CV
         # input:candidate_id,photo,cv
         # Output:Redirect with success/error
         
            
        
        $candidate_id=$request->candidate_id;
        
            $this->validate($request,[
                'photo' => 'mimes:jpg,jpeg,png|max:1000',
                'cv' => 'mimes:doc,pdf|max:1000',
            ],
            [
                'photo.mimes' => 'Photo file type must be jpg/jpeg/png ',
                'photo.max' => 'Photo file size too large to upload max:1000',
                 
                'cv.mimes' => 'CV file type must be doc/pdf ',
                'cv.max' => 'CV file size too large to upload max:1000 ',
                
            ]);
             
            if(($request->photo!=null) && ($request->cv==null)){
                $photo_link = $request->file('photo')
                ->store('candidate');
                $data=CandidateDetail::where('id','=',$candidate_id)->update(['photo'=>$photo_link]);
                if($data){
                    return redirect('candidate_profile')->with('success',"Photo Uploaded Successfully");
                }
                else{
                    return redirect('candidate_profile')->with('error',"Something was wrong!");   
                }
            }

           elseif(($request->cv!=null) && ($request->photo==null)){
                $cv_scan_link = $request->file('cv')
                ->store('candidate');
                $data=CandidateDetail::where('id','=',$candidate_id)->update(['cv_scan'=>$cv_scan_link]);
                if($data){
                    return redirect('candidate_profile')->with('success',"CV Uploaded Successfully");
                }
                else{
                    return redirect('candidate_profile')->with('error',"Something was wrong!");   
                }
            }
            elseif(($request->photo!=null) && ($request->cv!=null)){
                $cv_scan_link = $request->file('cv')
                ->store('candidate');
                $photo_link = $request->file('photo')
                ->store('candidate');
                $data=CandidateDetail::where('id','=',$candidate_id)->update(['cv_scan'=>$cv_scan_link,'photo'=>$photo_link]);
                if($data){
                    return redirect('candidate_profile')->with('success',"Photo and CV Uploaded Successfully");
                }
                else{
                    return redirect('candidate_profile')->with('error',"Something was wrong!");   
                }
            }
            elseif(($request->photo==null) && ($request->cv==null)){
                return redirect('candidate_profile')->with('error',"Please select any one file!"); 
            }
                    
    }
    */
    public function businessReviewSubmit(Request $request)
    {
        /**
         * Submit Review to Business
         * input:review,comment,business_id
         * output:msg
         */
        $this->validate($request,[
            'review'=>'required|numeric',
            'comment'=>'required|string'
        ]);
        //dd($request->all());
        $data=BusinessReviewDetail::insert(['user_id'=>Auth::user()->id,'business_id'=>$request->business_id,'review'=>$request->review,'comment'=>$request->comment]);
        if($data)
        {
            return $msg=1;
        }
        else
        {
            return $msg=0;
        }
    } 

    public function ratingReviewList()
    {
        $user=Auth::user();
        if($user->account_type=='candidate')
        {
       // $fromMe=BusinessReviewDetail::where('user_id',$candidate->id)->get(['comment','review','business_id']);
        $toMe=DB::table('candidate_details as cd')
        ->join('business_details as bd','cd.business_id','=','bd.user_id')
        ->select('cd.review as review','cd.rating as rating','bd.business_name as companyName')
        ->where([['email',$user->email],['rating','!=',NULL],['review','!=',NULL]])->get();
        }
        else if($user->account_type=='business')
        {
            $toMe=DB::table('business_review_details as brd')
        ->join('business_details as bd','brd.business_id','=','bd.user_id')
        ->join('users as user','brd.user_id','=','user.id')
        ->select('brd.review as review','brd.comment as comment','user.first_name as firstName','user.last_name as lastName','bd.business_name as companyName')
        ->where('business_id',$user->id)->get();
        }
        else if($user->account_type=='hr')
        {
            $toMe=DB::table('business_review_details as brd')
        ->join('business_details as bd','brd.business_id','=','bd.user_id')
        ->join('users as user','brd.user_id','=','user.id')
        ->select('brd.review as review','brd.comment as comment','user.first_name as firstName','user.last_name as lastName','bd.business_name as companyName')
        ->where('business_id',$user->parent_id)->get();
        }
        else{
            return response()->json(['status' => true,'msg' => 'No Permission', 'data'=>0]);
        }

        $data= $toMe;
        // dd($data);
        return view('candidate.my_rating',compact('data'));
        // return response()->json(['status' => true,'msg' => 'ok', 'data'=>$data]);
        // dd($toMe);
        
    }
    
         
        
         
}
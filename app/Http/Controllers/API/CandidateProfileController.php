<?php

namespace App\Http\Controllers\API;

use \App\Http\Controllers\Controller;
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
        $id=Auth::user()->id;      
         
        // $candidate=CandidateDetail::where('user_id','=',$id)->first();
        $candidate=DB::table('candidate_details as cd')
        ->Leftjoin('countries', 'cd.country', '=', 'countries.id')
        ->Leftjoin('state', 'cd.state', '=', 'state.state_id')
        ->Leftjoin('city', 'cd.city', '=', 'city.id')
         
        // ->select('cd.*', 'state.*', 'city.*','countries.*','job_roles.*','emp.*','hr.*')
        ->select('cd.*', 'state.state_title as stateName', 'city.name as cityName','countries.nationality as countryName')
        ->where('cd.user_id','=',$id)
        ->first();

        //dd($candidate->id);
        $job_role=JobRole::orderBy('name','ASC')->get();
        $education_details=CandidateEducationDetail::where('candidate_id','=',$candidate->id)->get();
        $profession_details=CandidateProfessionalDetail::where('candidate_id','=',$candidate->id)->get();
        $skills=CandidateOtherDetail::where([['candidate_id','=',$candidate->id],['type','=','skill']])->get();
        $languages=CandidateOtherDetail::where([['candidate_id','=',$candidate->id],['type','=','language']])->get();
        $hobbies=CandidateOtherDetail::where([['candidate_id','=',$candidate->id],['type','=','hobby']])->get();
        $states=State::where('country_id','=',$candidate->country)->get();
        $cities=City::where('state_id','=',$candidate->state)->orderby('name','ASC')->get();

        $data=[
            'candidate'=>$candidate,
            'education_details'=>$education_details,
            'profession_details'=>$profession_details,
            'skills'=>$skills,
            'languages'=>$languages,
            'hobbies'=>$hobbies,
            'states'=>$states,
            'cities'=>$cities,
            'job_role'=>$job_role
        ];
        return response()->json([
            'status'=>true,           
            'data'=>$data,
            'msg'=>1
        ]);
        // return View::make('candidate.index',compact('candidate','education_details','profession_details','skills','languages','hobbies','states','cities','job_role'));
    }

    public function candidateUncheckOffer()
    {
        // $id=Auth::user()->id;       
         
        // $candidate=CandidateDetail::where('user_id','=',$id)->first();
        // $offerletters=OfferLetter::where('candidate_id','=',$candidate->id)->orderBy('id','DESC')->paginate(5);

        $email=Auth::user()->email;       
          
        $candidates=CandidateDetail::where([['email','=',$email],['user_id','=',0]])->orderBy('id','DESC')->pluck('id')->toArray();

        //dd($candidates);
        // foreach($candidates as $can){
        //     $ca_id=CandidateDetail::where('candidate_id','=',$can->id)->get();
        // }
       // $offerletters=OfferLetter::whereIn('candidate_id',$candidates)->where('is_checked','=',0)->get();

    //    $offerletters=DB::table('checked_offer_letters as col')        
    //     ->join('offer_letters as ol', 'col.offer_letter_id', '=', 'ol.id')
    //     ->join('candidate_details as cd', 'ol.candidate_id', '=', 'cd.id')        
    //     ->join('job_roles', 'ol.post', '=', 'job_roles.id')
    //     ->join('business_details as emp', 'ol.business_id', '=', 'emp.user_id')       
    //     ->select('col.*','cd.candidate_code as candidateCode','cd.photo as candidatePhoto','ol.place_of_joining as placeOfJoining','ol.joining_date as joiningDate','ol.time_of_joining as timeOfJoining','ol.annual_ctc as annualCtc','emp.business_name as companyName','emp.user_id as businessId','ol.is_accepted as offerStatus')
    //     ->where('col.user_id','=',Auth::user()->id)
    //     ->orderBy('id','DESC')
    //     ->get();

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
        ->where('ol.is_modify',0)
        
        // ->where('ol.is_checked','=',0)
        ->orderBy('ol.id','DESC')
        ->get();

    
       // dd($offerletters);
       return response()->json([
        'status'=>true,           
        'data'=>$offerletters,
        'msg'=>1
    ]);
        // return view('candidate.uncheck_offer_list',compact('offerletters'));
    }

    // public function isChecked(Request $request)
    // {
    //     $offerletters=OfferLetter::where('id','=',$request->offerletter_id)->update(['is_checked'=>1]);
    //     $create_data=CheckedOfferLetter::insert(['user_id'=>Auth::user()->id,'offer_letter_id'=>$request->offerletter_id]);
    //     if($create_data)
    //     {
    //         // return $msg=1;
    //         return response()->json(['status' => true, 'msg' => 'Offer Letter Verified Successfully.', 'data'=>1]);
            
    //     }
    //     else{
    //         // return $msg=0;
    //         return response()->json(['status' => false, 'msg' => 'Somthing was wrong.', 'data'=>0]);
    //     }

    // }

    // public function candidateOffer()
    // {
        
    //     // $offerletters=CheckedOfferLetter::where('user_id','=',Auth::user()->id)->get();
    //     $offerletters=DB::table('checked_offer_letters as col')
        
    //     ->join('offer_letters as ol', 'col.offer_letter_id', '=', 'ol.id')
    //     ->join('candidate_details as cd', 'ol.candidate_id', '=', 'cd.id')
        
    //     ->join('job_roles', 'ol.post', '=', 'job_roles.id')
    //     ->join('business_details as emp', 'ol.business_id', '=', 'emp.user_id')
       
    //     ->select('col.*','cd.candidate_code as candidateCode','cd.photo as candidatePhoto','ol.place_of_joining as placeOfJoining','ol.joining_date as joiningDate','ol.time_of_joining as timeOfJoining','ol.annual_ctc as annualCtc','emp.business_name as companyName','emp.user_id as businessId','ol.is_accepted as offerStatus')
    //     ->where('col.user_id','=',Auth::user()->id)
    //     ->orderBy('id','DESC')
    //     ->get();
    //     return response()->json([
    //         'status'=>true,           
    //         'data'=>$offerletters,
    //         'msg'=>1
    //     ]);
    //     // return view('candidate.offer_list',compact('offerletters'));
    // }

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

    public function addPersonal(Request $request)
    {
      // dd($request->all());
        $id=$request->candidate_id;
        $validator = Validator::make($request->all(),[
            'cname' => 'required|string|min:3',
            'email' => 'required|email|check_mail|unique:users,email,'.Auth::user()->id,
            'gender' => 'required|alpha',            
            'state' => 'required',
            'city' => 'required',
            'phone'=>'required|numeric|digits:10',
            'total_experience'=>'required',
            'job_role1'=>'required',
            'dob'=>'required|date_format:Y-m-d|before:today'
        ],
        [
            'cname.required'=>'Name Required',
            'cname.min'=>'Name must be minimum 3 letters',

            'total_experience.required'=>'Total Experience Required',
            'dob.required'=>'DOB Required',
            'dob.date_format'=>'DOB Date format must be DD-MM-YYYY',
            'dob.before'=>'DOB must be before today',

            'check_mail'=>'Invalid Email Id',
             
        ]); 

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }

        //dd($request->job_role1);
        $user=User::where('id',Auth::user()->id)->update(['first_name'=>$request->cname,'email'=>strtolower($request->email)]);

        $data=CandidateDetail::where('id','=',$id)->update(['name'=>$request->cname,'email'=>strtolower($request->email),'phone'=>$request->phone,'gender'=>$request->gender,'state'=>$request->state,'city'=>$request->city,'religion'=>$request->religion,'dob'=>$request->dob,'father_name'=>$request->fname,'mother_name'=>$request->mname,'spouse_name'=>$request->sname,'present_address'=>$request->present_address,'permanent_address'=>$request->permanent_address,'job_role'=>$request->job_role1,'total_experience'=>$request->total_experience]); 

        if($data)
        {
            // return $msg=1;
            return response()->json(['status' => true, 'msg' => 'Personal Details Updated Successfully', 'data'=>1]);
        }
        else{
            // return $msg=2;
            return response()->json(['status' => false, 'msg' => 'Something Was Wrong', 'data'=>0]);
        }
        
    }   

    public function addEducation(Request $request)
    {  
        $validator = Validator::make($request->all(), [
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
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }
        $data=CandidateEducationDetail::insert(['candidate_id'=>$request->candidate_id,'institute_name'=>$request->institute,'degree'=>$request->degree,'year_of_passing'=>$request->year_of_passing,'marks'=>$request->marks,'percentage'=>$request->percentage]);
            
        if($data)
        {
            // return $msg=1;
            return response()->json(['status' => true, 'msg' => 'Education Details Added Successfully', 'data'=>1]);
        }
        else{
            // return $msg=2;
            return response()->json(['status' => false, 'msg' => 'Something Was Wrong', 'data'=>0]);
        }
                 
    }

    public function delEdu($id)
    {
        $education=CandidateEducationDetail::where('id','=',$id)->first();
               $education->delete();
        
       
        
        return response()->json(['status' => true, 'msg' => 'Education Dtails Deleted Successfully', 'data'=>1]);

      
    }

    public function addProfession(Request $request)
    { 
        $validator=Validator::make($request->all(), [
            'company' => 'required|regex:/^[a-zA-Z0-9. ]+$/u|min:3',
            'job_role' => 'required|regex:/^[a-zA-Z ]+$/u|min:3',
            'from_date'=>'required|date_format:Y-m-d|before:today',
            'to_date'=>'nullable|date_format:Y-m-d|after:from_date'       
        ],
        [
            'company.required'=>'Company Name Required',
            'company.min'=>'Company Name must be minimum 3 letters',
            'company.regex'=>'Company Name must be in Alpha Numeric only.',

            'job_role.required'=>'Job Role Required',
            'job_role.min'=>'Job Role must be minimum 3 letters',
            'job_role.regex'=>'Job Role must be in alphabets only.',

            'from_date.date_format'=>'From Date format must be DD-MM-YYYY',
            'from_date.required'=>'From Date Required',
            'from_date.before'=>'From Date must be before today',

            'to_date.date_format'=>'To Date format must be DD-MM-YYYY',
             
            'to_date.before'=>'To Date must be after From Date'
        ]);      
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }     
        if(($request->cc=='no' && $request->to_date!=NULL) || ($request->cc=='yes' && $request->to_date==NULL)) 
        {
                $data=CandidateProfessionalDetail::insert(['candidate_id'=>$request->candidate_id,'company_name'=>$request->company,'job_role'=>$request->job_role,'from_date'=>$request->from_date,'to_date'=>$request->to_date,'description'=>$request->description,'current_company'=>$request->cc]);           
            
                if($data)
                {
                    // return $msg=1;
                    return response()->json(['status' => true, 'msg' => 'Professional Details Added Successfully', 'data'=>1]);
                }
                else{
                    // return $msg=2;
                    return response()->json(['status' => false, 'msg' => 'Something Was Wrong', 'data'=>0]);
                }
        }
        else{
            return response()->json(['status' => false, 'msg' => 'To Date Required (if not current company)', 'data'=>0]);
            
        }
    }

    public function delProf($id)
    {       
        $profession=CandidateProfessionalDetail::where('id','=',$id)->first();
        $profession->delete();
        // return redirect()->back()->with('success','Professional  Dtails Deleted Successfully');
        return response()->json(['status' => true, 'msg' => 'Professional  Dtails Deleted Successfully', 'data'=>1]);
    }

    public function addLanguage(Request $request)
    {
        $validator=validator::make($request->all(), [
            'language' => 'required|alpha'           
        ]);  
        if($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }  
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
            // return $msg=1;
            return response()->json(['status' => true, 'msg' => 'Language  Added Successfully', 'data'=>1]);
        }
        else{
            // return $msg="Something was wrong";
            return response()->json(['status' => false, 'msg' => 'Something was wrong', 'data'=>0]);
        }
       }
       else{
        // return $msg="Please Select ability!";
        return response()->json(['status' => false, 'msg' => 'Please Select ability!', 'data'=>0]);
       }
        
        
         
    }

    public function addSkills(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'nullable|string'           
        ]); 
            if($validator->fails()) {
                return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
            }   
        //dd($request->all());
        $id=$request->candidate_id;
       
        $data=CandidateOtherDetail::insert(['candidate_id'=>$id,'type'=>'skill','value'=>$request->title,'description'=>$request->description]);
        if($data)
        {
            return response()->json(['status' => true, 'msg' => 'Skill  Added Successfully', 'data'=>1]);
        }
        else{
            // return $msg=2;
            return response()->json(['status' => false, 'msg' => 'Something Was Wrong', 'data'=>0]);
        }
         
    }

    public function addHobbies(Request $request)
    {
        //dd($request->all());
        $validator=Validator::make($request->all(), [
            'title' => 'required|string'   
        ]); 
        if($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
        }   
        $id=$request->candidate_id;
       
        $data=CandidateOtherDetail::insert(['candidate_id'=>$id,'type'=>'hobby','value'=>$request->title,'description'=>$request->description]);
        if($data)
        {
            return response()->json(['status' => true, 'msg' => 'Hobby  Added Successfully', 'data'=>1]);
        }
        else{
            // return $msg=2;
            return response()->json(['status' => false, 'msg' => 'Something Was Wrong', 'data'=>0]);
        }
         
    }    
   
    public function delOth($id)
    {         
        $others=CandidateOtherDetail::where('id','=',$id)->first();
               $others->delete();      
        $item=$others->type;
        // return redirect()->back()->with('success',"$item Dtails Deleted Successfully");
        return response()->json(['status' => true, 'msg' => "$item Deleted Successfully", 'data'=>1]);
    }
    public function uploadFile(Request $request)
    {         
        //dd($request->cv);
        $candidate_id=$request->candidate_id;
        //dd($candidate_id);
        $validator = Validator::make($request->all(), [
                'photo' => 'nullable|mimes:jpg,jpeg|max:1024',
                'cv' => 'nullable|mimes:doc,pdf|max:1024'
            ],
            [
                  
                'photo.mimes'=>'Profile pic must be in jpeg or jpg',
                'photo.max' => 'Photo file size too large',
                'cv.mimes' => 'CV file type must be in doc/pdf',
                'cv.max' => 'CV file size too large to upload max:1000'
                
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'msg' => 'validation error', 'errors'=>$validator->errors()]);
                }

                $user = auth()->user();

            if(($request->photo!=null) && ($request->cv==null))
            {
                // $path = $user->profile->avatar;

                // Storage::delete($path);
                
         
                // $base64_image=$request->photo;
                    
                // $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64_image);
                // $type = explode(';', $base64_image)[0];
                // $type = explode('/', $type)[1]; // png or jpg etc
    
                // $imageName = str_random(10).'.'.$type;
                
                // Storage::disk('local')->put('candidate/'.$imageName, base64_decode($image));
    
    
                // $user->profile->update([
                //     'avatar' => $imageName
                // ]);
                // return response()->json(['status' => true,'msg' => 'Photo Uploaded Successfully', 'data'=>1]);


                $photo_link = $request->file('photo')
                ->store('candidate');

                $data=CandidateDetail::where('id','=',$candidate_id)->update(['photo'=>$photo_link]);
                if($data){
                    // return redirect('candidate_profile')->with('success',"Photo Uploaded Successfully");
                    return response()->json(['status' => true,'msg' => 'Photo Uploaded Successfully', 'data'=>1]);
                }
                else{
                    // return redirect('candidate_profile')->with('error',"Something was wrong!");   
                    return response()->json(['status' => false,'msg' => 'some wrong', 'data'=>0]);
                }
            }

           elseif(($request->cv!=null) && ($request->photo==null))
           {
                $cv_scan_link = $request->file('cv')
                ->store('candidate');

                $data=CandidateDetail::where('id','=',$candidate_id)->update(['cv_scan'=>$cv_scan_link]);
                if($data){
                    return response()->json(['status' => true,'msg' => 'CV Uploaded Successfully', 'data'=>1]);
                    // return redirect('candidate_profile')->with('success',"CV Uploaded Successfully");
                }
                else{
                    // return redirect('candidate_profile')->with('error',"Something was wrong!"); 
                    return response()->json(['status' => false,'msg' => 'Please select any one file!', 'data'=>0]);  

                }
            }
            elseif(($request->photo!=null) && ($request->cv!=null))
            {
                $cv_scan_link = $request->file('cv')
                ->store('candidate');

                $photo_link = $request->file('photo')
                ->store('candidate');

                $data=CandidateDetail::where('id','=',$candidate_id)->update(['cv_scan'=>$cv_scan_link,'photo'=>$photo_link]);
                if($data){
                    return response()->json(['status' => true,'msg' => 'Photo and CV Uploaded Successfully', 'data'=>1]);
                    // return redirect('candidate_profile')->with('success',"Photo and CV Uploaded Successfully");
                }
                else{
                    // return redirect('candidate_profile')->with('error',"Something was wrong!");   
                    return response()->json(['status' => false,'msg' => 'Something was wrong!', 'data'=>0]);
                }
            }
            elseif(($request->photo==null) && ($request->cv==null))
            {
                // return redirect('candidate_profile')->with('error',"Please select any one file!"); 
                return response()->json(['status' => false,'msg' => 'Please select any one file!', 'data'=>0]);
            }
            
        
    }

    public function businessReviewSubmit(Request $request)
    {
        $this->validate($request,[
            'review'=>'required|numeric',
            'comment'=>'required|string'
        ]);
        //dd($request->all());
        $data=BusinessReviewDetail::insert(['user_id'=>Auth::user()->id,'business_id'=>$request->business_id,'review'=>$request->review,'comment'=>$request->comment]);
        if($data)
        {
            // return $msg=1;
            return response()->json(['status' => true,'msg' => 'Review Submitted Successfully', 'data'=>1]);
        }
        else
        {
            // return $msg=0;
            return response()->json(['status' => false,'msg' => 'Something was wrong!', 'data'=>0]);
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

        $data=[
            'toMe'=> $toMe,
            //'fromMe'=>$fromMe
        ];
        return response()->json(['status' => true,'msg' => 'ok', 'data'=>$data]);
        // dd($toMe);
        
    }

    

    
}
@extends('admin.layouts.app')

@section('content')
  

                @if(session('success'))                                     
                    <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
                @endif
                @if(session('error'))                                
                    <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
                @endif
<div class="pxp-dashboard-content-details">

    <div class="edu-history-sec">
        <h5 class="mb-4">Basic Details</h5>
        {{--<button  data-bs-toggle="modal" class="add_details" data-bs-target="#basicModal">Edit <i class="fa fa-pencil"></i></button>--}}
        <a href="{{url('basicdetails')}}/{{base64_encode($candidate->id)}}" class="add_details">Edit <i class="fa fa-pencil"></i></a>
            <div class="edu-history">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
                        <p class="mb-0 sm_font">Name - <strong>{{$candidate->name}}</strong></p>
                        <p class="mb-0 sm_font">Email - <strong>{{$candidate->email}}</strong></p>
                        <p class="mb-0 sm_font">Phone - <strong>+91 {{$candidate->phone}}</strong></p>
                        <p class="mb-0 sm_font">Gender - <strong>{{$candidate->gender}}</strong></p>
                        <p class="mb-0 sm_font">DOB - <strong>{{date('d-m-Y', strtotime($candidate->dob))}}</strong></p>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                        <p class="mb-0 sm_font">Religion - <strong>{{$candidate->religion}}</strong></p>
                        <p class="mb-0 sm_font">Nationality - <strong>{{$candidate->countryDetails->nationality}}</strong></p>
                        <p class="mb-0 sm_font">State - <strong>@if($candidate->state != null){{$candidate->stateDetails->state_title}}@endif</strong></p>
                       <p class="mb-0 sm_font">City - <strong>@if($candidate->city != Null) {{$candidate->cityDetails->name}}@endif<strong></p>
                    </div>
                </div>
                <!-- <ul class="action_job">
                    
                    <li><span>Delete</span><a href="#"><i class="fa fa-trash-o"></i></a></li>
                </ul> -->
            </div>
    </div>
<hr>
    <div class="edu-history-sec">
        <h5 class="mb-4">Education Details</h5>
        <a href="{{url('educationdetails')}}/{{base64_encode($candidate->id)}}" class="add_details">Add <i class="fa fa-plus"></i></a>

        {{--<button data-bs-toggle="modal" class="add_details" data-bs-target="#educationModal">Add <i class="fa fa-plus"></i></button>--}}
        @foreach($education_details as $ed)
        <div class="edu-history">
            <i class="fa fa-graduation-cap"></i>
            <div class="edu-hisinfo">
                <h3>{{ $ed->degree}}</h3>
                <i>{{ $ed->year_of_passing}}</i>
                <span>{{ $ed->institute_name}}<i>{{ $ed->degree}}</i></span>
               {{-- <p>{{ $ed->marks}},{{ $ed->percentage}}</p>--}}
            </div>
            {{--<ul class="action_job">             
                <li><span>Delete</span><a href="{{route('delEdu',['id'=>$ed->id])}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a></li>
            </ul>--}}
        </div>
        @endforeach   
    </div>

<hr>

    <div class="edu-history-sec">
        <h5 class="mb-4"> Work Experience</h5>
        <a href="{{url('professionaldetails')}}/{{base64_encode($candidate->id)}}" class="add_details">Add <i class="fa fa-plus"></i></a>
        {{--<button  data-bs-toggle="modal" class="add_details" data-bs-target="#professionalModal">Add <i class="fa fa-plus"></i></button>--}}
            @foreach($profession_details as $p)     
            <div class="edu-history style2">
            <i></i>
                <div class="edu-hisinfo">
                    <h3>{{ $p->job_role}} <span>{{ $p->company_name}}</span></h3>
                    <i>{{ $p->from_date}} To {{ $p->to_date}}</i>
                    <p>{{ $p->description}}</p>
                </div>
                {{--<ul class="action_job">
                    
                <li><span>Delete</span><a href="{{route('delProf',['id'=>$p->id])}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a></li>
                </ul>--}}
            </div>
            @endforeach
    </div>



<!-- <h5 class="mb-4">Professional Details</h5>
<div class="edu-history-sec">
  <div class="edu-history style2">
      <i></i>
      <div class="edu-hisinfo">
          <h3>Perfect Attendance Programs</h3>
          <i>2008 - 2012</i>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin a ipsum tellus. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
      </div>
      <ul class="action_job">
          <li><span>Edit</span><button  data-bs-toggle="modal" data-bs-target="#ProfessionalModal"><i class="fa fa-pencil"></i></button></li>
          <li><span>Delete</span><a href="#"><i class="fa fa-trash-o"></i></a></li>
      </ul>
  </div>
  <div class="edu-history style2">
      <i></i>
      <div class="edu-hisinfo">
          <h3>Perfect Attendance Programs</h3>
          <i>2008 - 2012</i>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin a ipsum tellus. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
      </div>
      <ul class="action_job">
          <li><span>Edit</span><button  data-bs-toggle="modal" data-bs-target="#ProfessionalModal"><i class="fa fa-pencil"></i></button></li>
          <li><span>Delete</span><a href="#"><i class="fa fa-trash-o"></i></a></li>
      </ul>
  </div>
</div> -->

<hr>

<div class="edu-history-sec">
    <h5 class="mb-4"> Languages Known </h5>
    
    <button  data-bs-toggle="modal" class="add_details" data-bs-target="#languageModal">Add <i class="fa fa-plus"></i></button>
    @foreach($languages as $language)  
        <div class="edu-history style2">
        <i></i>
            <div class="edu-hisinfo">
            
                <h3>  {{$language->value}} <span> ( {{$language->description}} ) </span> </h3>                    
            </div>
            <ul class="action_job">                    
            <li><span>Delete</span><a href="{{route('delOth',['id'=>$language->id])}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a></li>
            </ul>
        </div>
        @endforeach
</div>
<hr>
{{--<div class="edu-history-sec">
    <h5 class="mb-4">Skills</h5>
   
    <button  data-bs-toggle="modal" class="add_details" data-bs-target="#skillModal">Add <i class="fa fa-plus"></i></button>
    @foreach($skills as $skill)
        <div class="edu-history style2">
            <i></i>
            <div class="edu-hisinfo">
                <h3>{{$skill->value}} :<span>{{$skill->description}}</span></h3>                    
            </div>
            <ul class="action_job">                    
            <li><span>Delete</span><a href="{{route('delOth',['id'=>$skill->id])}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a></li>
            </ul>
        </div>
        @endforeach
</div>
<hr>
<div class="edu-history-sec">
    <h5 class="mb-4">Hobbies</h5>
    <button  data-bs-toggle="modal" class="add_details" data-bs-target="#hobbyModal">Add <i class="fa fa-plus"></i></button>
    @foreach($hobbies as $hobby)
    <div class="edu-history style2">
        <i></i>
        <div class="edu-hisinfo">
            <h3>{{$hobby->value}}<span>{{$hobby->description}}</span></h3>                    
        </div>
        <ul class="action_job">                    
        <li><span>Delete</span><a href="{{route('delOth',['id'=>$hobby->id])}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a></li>
        </ul>
    </div>
    @endforeach
</div>
<hr>--}}

    <div class="edu-history-sec">
        <h5 class="mb-4">Other's Details</h5>

        <a href="{{url('othersdetails')}}/{{base64_encode($candidate->id)}}" class="add_details">Edit <i class="fa fa-pencil"></i></a>
        @php
            $otherTypes=DB::table('others_types_masters')->orderBy('id','ASC')->get();
        @endphp
             
        @foreach($otherTypes as $ot)
        <div class="edu-history style2">
            <i></i>
            <div class="edu-hisinfo">
                <h3>
                    {{$ot->title}} : 
                    @foreach($other_details as $p)  
                        @if($p->type==$ot->name)     
                        <button type="button" class="btn btn-outline-success btn-sm mt-1 mb-1">{{ $p->value}} </button>
                        @endif                                                          
                    @endforeach 

                </h3>                    
            </div>            
        </div>
        @endforeach
       
    </div>
<hr>
<div class="edu-history-sec">
        <h5 class="mb-4">Upload Document</h5>

        <a href="{{url('upload_document')}}/{{base64_encode($candidate->id)}}" class="add_details">Add <i class="fa fa-plus"></i></a>
</div>
<hr>

    <div class="edu-history-sec">   
        <div class="text-center mt-4">
            <a href="{{url('candidate_view')}}/{{base64_encode($candidate->id)}}" class="btn rounded-pill pxp-section-cta btn-sm " target="_blank">Resume View</a> 
        </div>   
            {{--<div class="edu-history">
                <form method="post" enctype="multipart/form-data" action="{{url('upload_file')}}">
                    @csrf
                <div class="row">
                   
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <input type="hidden" name="candidate_id" value="{{$candidate->id}}">
                            <div class="mb-3">
                                <label class="form-label">Photo (jpg/jpeg)</label>
                                <div class="custom-file">
                                    <input type="file" id="photo" name="photo" class="custom-file-input" >
                                    <label class="custom-file-label" for="photo">Choose file</label>
                                </div>
                                 
                                @if ($errors->has('photo'))
                                    <label class="text-danger">{{ $errors->first('photo') }}</label>
                                @endif
                                
                            </div> 
                            
                        </div>
                   
                        <div class="col-md-4 col-sm-4 col-lg-4">                        
                            <div class="mb-3">
                                <label class="form-label">CV (doc/pdf)</label>
                                <div class="custom-file">
                                    <input type="file" id="cv" name="cv" class="custom-file-input" >
                                    <label class="custom-file-label" for="cv">Choose file</label>
                                </div>
                                 
                                @if ($errors->has('cv'))
                                    <label class="text-danger">{{ $errors->first('cv') }}</label>
                                @endif
                                
                            </div>
                            
                        </div>     
                        <div class="col-md-4 col-sm-4 col-lg-4">                        
                            <div class="mb-3">
                                <label class="form-label">&nbsp; </label>
                                <button  class="btn rounded-pill pxp-section-cta btn-sm btn-block">Upload</button>
                            </div>
                        </div>     
                                  
                </div>
                </form> 
                <div class="row">
                     
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <img src="{{ ($candidate->photo!='')?(url('images/'.$candidate->photo)):(url('new/images/noimage.png')) }}" alt="No Image" style="width: 100px; height: 100px;"/>
                        </div>
                   
                        <div class="col-md-4 col-sm-4 col-lg-4 text-center"> 
                            @if($candidate->cv_scan!='')
                            <a href="{{ (url('images/'.$candidate->cv_scan))}}" ><i class="fa fa-file-text-o text-dark fa-5x mb-2"></i></a>
                            @else
                            No Data
                            @endif
                        </div>     
                        <!-- <i class="fa fa-file-pdf-o text-danger fa-5x mb-2"></i> -->
                                   
                </div>
                
            </div>--}}
            
    </div>


    <!--Education Details-->
<div class="modal fade" id="educationModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetForm();"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Education Details</h4>
                
                <form method="post" enctype="multipart/form-data" id="modelForm">
                    @csrf
                    <input type="hidden" id="candidate_id" value="{{$candidate->id}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Institute Name *</label>
                                <input type="text"  name="institute"  id="institute" class="form-control" placeholder="Enter Institute Name" value="" >
                                <p class="error_institute qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Degree *</label>
                                <input type="text" name="degree" id="degree" class="form-control" placeholder="Enter Degree" value="" >
                                <p class="error_degree qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Year Of Passing *</label>
                                <input type="text" name="year_of_passing"  id="year_of_passing" class="form-control" placeholder="Enter Year Of Passing" value="" >
                                <p class="error_year_of_passing qerr"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Marks / Grade *</label>
                                <input type="text" name="marks" id="marks" class="form-control" placeholder="Enter Full Marks Obtained" value="" >
                                <p class="error_marks qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Percentage / CGPA *</label>
                                <input type="text" name="percentage" id="percentage" class="form-control" placeholder="Enter Percentage" value="" >
                                <p class="error_percentage qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">&nbsp; </label>
                            <button type="button" id="btn1" class="btn rounded-pill pxp-section-cta btn-block" onClick="education();">Save</button>
                            <p class="statusMsg"></p>
                        </div>
                    </div>
                    
                </form>
            </div>
            </div>
        </div>
</div>
    <!--Education Details-->
    <!--Professional Details-->
<div class="modal fade" id="professionalModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetForm();"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Work Experience</h4>
                
                <form method="post" enctype="multipart/form-data" id="modelForm">
                    @csrf
                    <input type="hidden" id="candidate_id" value="{{$candidate->id}}">
                    <div class="mb-3">
                        <label class="form-label">company *</label>
                        <input type="text"  id="company" class="form-control" placeholder="Enter Company Name" value="" >
                        <p class="error_company qerr"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Job Role *</label>
                        <input type="text" id="job_role" class="form-control" placeholder="Enter Job Role" value="" >
                        <p class="error_job_role qerr"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">From *</label>
                        <input type="date" id="from_date" class="form-control"  value="" >
                        <p class="error_from_date qerr"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">To *</label>
                        <input type="date" id="to_date" class="form-control" value="" >
                        <p class="error_to_date qerr"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description </label>
                        <input type="text" id="description" class="form-control"  value="" >
                        <p class="error_description qerr"></p>
                    </div>
                                            
                    <button type="button" id="btn2" onClick="profession();" class="btn rounded-pill pxp-section-cta btn-block">Save</button>
                    <p class="statusMsg"></p>
                </form>
            </div>
            </div>
        </div>
</div>
    <!--Professional Details-->
    <!--Language Details-->
<div class="modal fade" id="languageModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick=resetForm();></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Language Details</h4>
              
                <form method="post" action="" id="modelForm">
                @csrf
                    <input type="hidden" id="candidate_id" value="{{$candidate->id}}">                    
                    
                    <div class="mb-3">
                        <label class="form-label">Language *</label>
                        @php
                            $languages=DB::table('languages')->get();
                            @endphp
                        <select  id="language" name="language"  required>
                            <option value='' selected>Select</option>                           
                            @foreach($languages as $lang)
                            <option value="{{$lang->name}}"  >{{$lang->name}}</option>
                            @endforeach
                           
                        </select>
                        <p class="error_language qerr"></p>
                    </div>
                    <label class="form-label">Ability *</label>
                    <div class="mb-3">
                   
                        <label class="form-label" for="read"  >Read</label>
                        <input type="checkbox" id="read" name="read"    >

                        <label class="form-label" for="write">Write</label>
                        <input type="checkbox" id="write" name="write"  >

                        <label class="form-label" for="speak">Speak</label>
                        <input type="checkbox" id="speak" name="speak" >
                        
                    </div>
                     
                    
                        
                    <button type="button" id="btn4" class="btn rounded-pill pxp-section-cta btn-block" onClick="addLanguage();">Save</button>
                    <p class="statusMsg"></p>
                </form>
            </div>
            </div>
        </div>
</div>
    <!--Language Details-->
    <!--Basic Details-->
<div class="modal fade" id="basicModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetForm();"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Basic Details</h4>
                
                <form method="post" action="" id="basicForm">
                @csrf
                    <input type="hidden" id="candidate_id" value="{{$candidate->id}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text"  id="cname" class="form-control" placeholder="Enter Full Name" value="{{$candidate->name}}" readonly>
                                <p class="error_cname qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="text" id="email" class="form-control" placeholder="Enter Email" value="{{$candidate->email}}" readonly>
                                <p class="error_email qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Phone *</label>
                                <input type="text" id="phone" class="form-control" placeholder="Enter Phone" value="{{$candidate->phone}}" readonly>
                                <p class="error_phone qerr"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Father Name </label>
                                <input type="text" id="fname" class="form-control" placeholder="Enter Father Name" value="{{$candidate->fathers_name}}" >
                                <p class="error_fname qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Mother Name </label>
                                <input type="text" id="mname" class="form-control" placeholder="Enter Mother Name" value="{{$candidate->mothers_name}}" >
                                <p class="error_mname qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Spouse Name </label>
                                <input type="text" id="sname" class="form-control" placeholder="Enter Spouse Name" value="{{$candidate->spouse_name}}" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Present Address </label>
                                <input type="text" id="present_address" class="form-control" placeholder="Enter Present Address" value="{{$candidate->present_address}}" >
                                <p class="error_present_address qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Permanent Address </label>
                                <input type="text" id="permanent_address" class="form-control" placeholder="Enter Permanent Address" value="{{$candidate->permanent_address}}" >
                                <p class="error_permanent_address qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Gender *</label>
                                <select  id="gender" name="gender"  required>
                                    <option value='' selected>Select</option>
                                    <option value='male' {{$candidate->gender=='male'? "selected":""}}>MALE</option>
                                    <option value='female' {{$candidate->gender=='female'? "selected":""}}>FEMALE</option>
                                    <option value='other' {{$candidate->gender=='other'? "selected":""}}>OTHER</option>
                                </select>
                                <p class="error_gender qerr"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">State *</label>
                                <select  id="state" name="state"  onchange="getCity(this.value)" required>
                                    <option value='' >Select state</option>
                                    @foreach($states as $s)
                                    <option value='{{$s->state_id}}' {{$candidate->state==$s->state_id?"selected":""}}>{{$s->state_title}}</option>
                                    @endforeach
                                </select>
                                <p class="error_state qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">City *</label>
                                <select  id="city" name="city"  required>
                                    @isset($cities)
                                    @foreach($cities as $city)
                                    <option value='{{$city->id}}' {{$city->id==$candidate->city?"selected":""}}>{{$city->name}}</option>
                                    @endforeach  
                                    @endif                              
                                </select>
                                <p class="error_city qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Religion </label>
                                <input type="text" id="religion" name="religion" class="form-control" value="{{$candidate->religion}}">
                                <p class="error_religion qerr"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">DOB * </label>
                                <input type="date" id="dob" name="dob" class="form-control" value="{{$candidate->dob}}">
                                <p class="error_religion qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Job Role *</label>
                                <select  id="job_role1" name="job_role1"  required>
                                    <option  value="">Select Job Role</option>
                                @foreach($job_role as $role)
                                <option  value="{{$role->id}}" {{$role->id==$candidate->job_role?"selected":""}}>{{$role->name}}</option>                                
                                @endforeach
                                </select>
                                 
                                    
                                <p class="error_job_role1 qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Total Experience(In Year) *</label>
                                <select  id="total_experience" name="total_experience"  required>
                                    <option value="Fresher">Fresher (Less Then 1 Year)</option>
                                    @for($i=1;$i<=30;$i++)
                                    <option value="{{$i}}" {{$i==$candidate->total_experience?"selected":""}}>{{$i}} Year</option>
                                    @endfor
                                </select>
                                
                                    
                                <p class="error_total_experience qerr"></p>
                            </div>
                        </div>
                        
                        
                    </div>
                    <button type="button" id="btn3" class="btn rounded-pill pxp-section-cta btn-block" onClick="personal();">Save</button>
                    <p class="statusMsg"></p>
                </form>
            </div>
            </div>
        </div>
</div>
    <!--Basic Details-->
    <!--Skills Details-->
<div class="modal fade" id="skillModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetForm();"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Skill Details</h4>
                
                <form method="post" action="" id="modelForm">
                @csrf
                    <input type="hidden" id="candidate_id" value="{{$candidate->id}}">                    
                    
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text"  id="title" name="title" class="form-control" required>
                        <p class="error_title qerr"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea  id="description" name="description" class="form-control" rows = "4" cols = "40" required>  </textarea>
                        <p class="error_description qerr"></p>
                    </div>
                        
                    <button type="button" id="btn5" class="btn rounded-pill pxp-section-cta btn-block" onClick="addSkills();">Save</button>
                    <p class="statusMsg"></p>
                </form>
            </div>
            </div>
        </div>
</div>
    <!--skills Details-->
    <!--Hobbies Details-->
<div class="modal fade" id="hobbyModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetForm();"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Hobby Details</h4>
                
                <form method="post" action=""  id="modelForm">
                @csrf
                    <input type="hidden" id="candidate_id" value="{{$candidate->id}}">                    
                    
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text"  id="title_h" name="title_h" class="form-control" required>
                        <p class="error_title qerr"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description </label>
                        <textarea  id="description_h" name="description_h" class="form-control" rows = "4" cols = "40" >  </textarea>
                        <p class="error_description qerr"></p>
                    </div>
                    <button type="button" id="btn6" class="btn rounded-pill pxp-section-cta btn-block" onClick="addHobbies();">Save</button>
                    <p class="statusMsg"></p>
                </form>
            </div>
            </div>
        </div>
</div>
    <!--Hobbies Details-->

</div>
@endsection
@push('js')
<script>
   function resetForm(){
//    /$('#modelForm').reset();
   $("#modelForm")[0].reset();
    //$('input[type="text"],textarea,select').val('');
    $('.qerr').html('');
    $('.statusMsg').html('');
   }
     function clearData(){
        var element = jQuery('#city');
  
        if(element[0].selectize){
            element[0].selectize.destroy();
        }
    }
function getCity(state_id)
{
    var state_id=state_id;
    console.log(state_id);
    var $select = $($('#city')).selectize();
    var selectize = $select[0].selectize;
    selectize.renderCache = {};
        selectize.clearOptions();
        selectize.clear();
        selectize.refreshOptions(true);
    $.ajax({
        type:'GET',
        url:"{{url('get_city')}}",
        data:'state_id='+state_id,
        success: function(response) {
            selectize.clearOptions();
                selectize.clear();
            $.each(response,function (i, city){
                 
                selectize.addOption({value: city.id, text: city.name}); 
            });
            selectize.refreshOptions();  
             
        }
    });
}

function education()
{
    $('.qerr').html('');
    $('.statusMsg').html('');
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var candidate_id=$('#candidate_id').val();
    var institute=$('#institute').val();
    var degree=$('#degree').val();
    var year_of_passing=$('#year_of_passing').val();
    var marks=$('#marks').val();
    var percentage=$('#percentage').val();

    $.ajax({
        type:"POST",
        url:"{{url('/add_education')}}",
        data:'_token='+token+'&candidate_id='+candidate_id+'&institute='+institute+'&degree='+degree+'&year_of_passing='+year_of_passing+'&marks='+marks+'&percentage='+percentage,
        success:function(education_response)
        {
            if(education_response==1)
            {
                $('#btn1').hide();
                $('.statusMsg').html('<span style="color:green;">Education Details Added Successfully!.</span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 3000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</span>');
            }
        },
        error: function (education_reject) {
           
                if( education_reject.status === 422 ) {
                    //console.log(reject);
                    var edu_resp = $.parseJSON(education_reject.responseText);
                    $.each(edu_resp.errors, function (edu_key, edu_val) {
                        console.log(edu_key,edu_val);
                        $('.error_'+edu_key).html(edu_val[0]).css("color","red","display","show");
                        $( edu_key ).text(edu_val[0]);
                    });
                }
            }
            
    });    
}
function profession()
{
    $('.qerr').html('');
    $('.statusMsg').html('');
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var candidate_id=$('#candidate_id').val();
    var company=$('#company').val();
    var job_role=$('#job_role').val();
    var from_date=$('#from_date').val();
    var to_date=$('#to_date').val();
    var description=$('#description').val();

    $.ajax({
        type:"POST",
        url:"{{url('/add_profession')}}",
        data:'_token='+token+'&candidate_id='+candidate_id+'&company='+company+'&job_role='+job_role+'&from_date='+from_date+'&to_date='+to_date+'&description='+description,
        success:function(prof_response)
        {
            if(prof_response==1)
            {
                $('#btn2').hide();
                $('.statusMsg').html('<span style="color:green;">Work Experience Added Successfully!. </span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 3000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</span>');
            }
        },
        error: function (prof_reject) {
           
                if( prof_reject.status === 422 ) {
                    //console.log(reject);
                    var prof_resp = $.parseJSON(prof_reject.responseText);
                    $.each(prof_resp.errors, function (key, val) {
                        console.log(key,val);
                        $('.error_'+key).html(val[0]).css("color","red","display","show");
                        $( key ).text(val[0]);
                    });
                }
            }
    });    
}
function personal()
{
    $('.qerr').html('');
    $('.statusMsg').html('');
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var candidate_id=$('#candidate_id').val();
    var cname=$('#cname').val();
    var email=$('#email').val();
    var phone=$('#phone').val();
    var fname=$('#fname').val();
    var mname=$('#mname').val();
    var sname=$('#sname').val();
    var present_address=$('#present_address').val();
    var permanent_address=$('#permanent_address').val();
    var gender=$('#gender').val();
    var dob=$('#dob').val();
    var state=$('#state').val();
    var city=$('#city').val();
    var religion=$('#religion').val();
    var job_role1=$('#job_role1').val();
    var total_experience=$('#total_experience').val();

    $.ajax({
        type:"POST",
        url:"{{url('/add_personal')}}",
        data:'_token='+token+'&candidate_id='+candidate_id+'&cname='+cname+'&email='+email+'&phone='+phone+'&fname='+fname+'&mname='+mname+'&sname='+sname+'&present_address='+present_address+'&permanent_address='+permanent_address+'&gender='+gender+'&state='+state+'&city='+city+'&religion='+religion+'&job_role1='+job_role1+'&total_experience='+total_experience+'&dob='+dob,
        success:function(personal_response)
        {
            if(personal_response==1)
            {
                $('#btn3').hide();
                clearData();
                $('.statusMsg').html('<span style="color:green;">Details Updated Successfully!. </span>Redirecting.....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 3000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</span>');
            }
        },
        error: function (personal_reject) {
           
                if( personal_reject.status === 422 ) {
                    //console.log(reject);
                    var personal_resp = $.parseJSON(personal_reject.responseText);
                    $.each(personal_resp.errors, function (key, val) {
                        console.log(key,val);
                        $('.error_'+key).html(val[0]).css("color","red","display","show");
                        $( key ).text(val[0]);
                    });
                }
            }
    });    
}
function addLanguage()
{
    $('.qerr').html('');
    $('.statusMsg').html('');
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var candidate_id=$('#candidate_id').val();
    var language=$('#language').val();
    var read=$('#read').is(':checked');
    var write=$('#write').is(':checked');
    var speak=$('#speak').is(':checked');
    

    $.ajax({
        type:"POST",
        url:"{{url('/add_language')}}",
        data:'_token='+token+'&candidate_id='+candidate_id+'&language='+language+'&read='+read+'&write='+write+'&speak='+speak,
        success:function(lang_response)
        {
            if(lang_response==1)
            {
                $('#btn4').hide();
                $('.statusMsg').html('<span style="color:green;">Language Addded Successfully!.</span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 3000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">'+lang_response+'</span>');
            }
        },
        error: function (lang_reject) {
           
                if( lang_reject.status === 422 ) {
                    //console.log(reject);
                    var lang_resp = $.parseJSON(lang_reject.responseText);
                    $.each(lang_resp.errors, function (key, val) {
                        console.log(key,val);
                        $('.error_'+key).html(val[0]).css("color","red","display","show");
                        $( key ).text(val[0]);
                    });
                }
            }
    });    
}
function addSkills()
{

    $('.qerr').html('');
    $('.statusMsg').html('');
    
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var candidate_id=$('#candidate_id').val();
    var title=$('#title').val();
    var description=$('textarea#description').val();
     
    

    $.ajax({
        type:"POST",
        url:"{{url('/add_skills')}}",
        data:'_token='+token+'&candidate_id='+candidate_id+'&title='+title+'&description='+description,
        success:function(skil_response)
        {
            if(skil_response==1)
            {
                $('#btn5').hide();
                $('.statusMsg').html('<span style="color:green;">Skills Addded Successfully!.</span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 3000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</span>');
            }
        },
        error: function (skil_reject) {
           
                if( skil_reject.status === 422 ) {
                    //console.log(reject);
                    var skil_resp = $.parseJSON(skil_reject.responseText);
                    $.each(skil_resp.errors, function (skill_key, skill_val) {
                        console.log(skill_key,skill_val);
                        $('.error_'+skill_key).html(skill_val[0]).css("color","red","display","show");
                        $( skill_key ).text(skill_val[0]);
                    });
                }
            }
    });    
}
function addHobbies()
{
    $('.qerr').html('');
    $('.statusMsg').html('');
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var candidate_id=$('#candidate_id').val();
    var title=$('#title_h').val();      
    var description=$('textarea#description_h').val();
     
    

    $.ajax({
        type:"POST",
        url:"{{url('/add_hobbies')}}",
        data:'_token='+token+'&candidate_id='+candidate_id+'&title='+title+'&description='+description,
        success:function(hob_response)
        {
            if(hob_response==1)
            {
                $('#btn6').hide();
                $('.statusMsg').html('<span style="color:green;">Hobby Addded Successfully!.</span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 3000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</span>');
            }
        },
        error: function (hob_reject) {
           
                if( hob_reject.status === 422 ) {
                    //console.log(reject);
                    var hob_resp = $.parseJSON(hob_reject.responseText);
                    $.each(hob_resp.errors, function (hob_key, hob_val) {
                        console.log(hob_key,hob_val);
                        $('.error_'+hob_key).html(hob_val[0]).css("color","red","display","show");
                        $( hob_key ).text(hob_val[0]);
                    });
                }
            }
    });    
}
</script>
@endpush
   
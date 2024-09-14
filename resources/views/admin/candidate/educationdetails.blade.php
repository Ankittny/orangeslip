@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
    <div class="pxp-dashboard-content-details">

        <div class="d-flex justify-content-between">
            <h4 class="text-themecolor">Education Details ({{$candidate->candidate_code}})</h4>
            @if(Auth::user()->account_type=='candidate')
            <a href="{{url('candidate_profile')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
            @else
            <a href="{{url('edit_candidate')}}/{{base64_encode($candidate->id)}}"><i class="fa fa-long-arrow-left"></i> Back</a>
            @endif
        </div>    
        <div class="alert alert-info">
            <div class="row">
                <div class="col-md-4">
                    <strong>Name: {{$candidate->name}}</strong>
                </div>
                <div class="col-md-5" >
                    <strong>Email: {{$candidate->email}}</strong>
                </div>
                <div class="col-md-3 text-right">
                    <strong>Phone: {{$candidate->phone}}</strong>
                </div>                
            </div>        
        </div>                       

        <form method="post" action="{{url('educationdetails')}}/{{base64_encode($candidate->id)}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="candidate_id" value="{{$candidate->id}}">     
            <div class="row">
                
                 <div class="col-md-3">
                    <div class="mb-3">
                                    <label  class="form-label">Education Type *</label>
                                    <select   id="education_type" name="education_type" onchange="getcourse(this.value);" required>
                                        <option value=''>Select</option>
                                        @foreach($educationTypes as $education)
                                        <option value="{{$education->id}}">{{$education->education}}</option>
                                        @endforeach
                                    </select>
                    </div>
                    @if($errors->has('education_type'))
                        <label class="text-danger">{{ $errors->first('education_type') }}</label>
                    @endif
                </div> 
                <div class="col-md-3">
                    <div class="mb-3">
                                    <label  class="form-label courselabel">Course *</label>
                                    <select   id="course" name="degree" onchange="addCourse(this.value);">
                                        
                                    </select>
                                    <input type="text" name="addcourse" class="form-control addcourse" style="display:none;">     
                    </div>
                    @if($errors->has('course'))
                        <label class="text-danger">{{ $errors->first('course') }}</label>
                    @endif
                </div> 
                <div class="col-md-3 specialization">
                   
                    <div class="mb-3 " >
                                    <label  class="form-label">Specialization </label>
                                    
                                    <input type="text"   id="specialization" name="specialization" class="form-control " >
                                        
                                    
                                         
                    </div>
                    @if($errors->has('specialization'))
                        <label class="text-danger">{{ $errors->first('specialization') }}</label>
                    @endif
                </div> 
                <div class="col-md-3">
                    <div class="mb-3">
                                    <label  class="form-label">Institute Name *</label>
                                    <input type="text" id="institute" name="institute" class="form-control" value="{{old('institute')}}" required>
                    </div>
                    @if($errors->has('institute'))
                        <label class="text-danger">{{ $errors->first('institute') }}</label>
                    @endif
                </div>
                {{--<div class="col-md-4">
                    <div class="mb-3">
                                    <label  class="form-label">Degree *</label>
                                    <input type="text" id="degree" name="degree" class="form-control" value="{{old('degree')}}" required>
                    </div>
                    @if($errors->has('degree'))
                        <label class="text-danger">{{ $errors->first('degree') }}</label>
                    @endif
                </div>--}}
                
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                                    <label  class="form-label">Year of Passing </label>
                                    <input type="numeric" id="year_of_passing" name="year_of_passing" class="form-control" value="{{old('year_of_passing')}}">
                    </div>
                    @if($errors->has('year_of_passing'))
                        <label class="text-danger">{{ $errors->first('year_of_passing') }}</label>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                                    <label  class="form-label">Marks / Grade </label>
                                    <input type="text" id="marks" name="marks" class="form-control" value="{{old('marks')}}" >
                    </div>
                    @if($errors->has('marks'))
                        <label class="text-danger">{{ $errors->first('marks') }}</label>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                                    <label  class="form-label">Percentage / CGPA </label>
                                    <input type="text" id="percentage" name="percentage" class="form-control" value="{{old('percentage')}}" >
                    </div>
                    @if($errors->has('percentage'))
                        <label class="text-danger">{{ $errors->first('percentage') }}</label>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Marksheet (jpg/jpeg)</label>
                        <div class="custom-file">
                            <input type="file" id="doc_file" name="doc_file" class="custom-file-input">
                            <label class="custom-file-label" for="doc_file">Choose file</label>
                        </div>
                    </div>
                    @if($errors->has('doc_file'))
                        <label class="text-danger">{{ $errors->first('doc_file') }}</label>
                    @endif
                </div>
            </div>
            <div class="row">
                
                <div class="col-md-12 text-center">
                    <button class="btn rounded-pill pxp-section-cta">Add</button>
                </div>
                
            </div>
             
        </form>
        <hr>
        <div class="table-responvive">
            <table class="table footable ">  
                <thead>
                    <tr>
                        <th>#</th>                        
                        <th>Education Type</th>                        
                        <th>Course/Board</th>
                        <th>Specialization</th>
                        <th>Institute Name</th>
                        <th>Year Of Passing</th>
                        <th>Marks</th>
                        <th>Percentage</th>
                        <th>File</th>
                        
                        <th>Action</th>
                    </tr> 
                </thead>
                <tbody>                                   
                    @foreach($educations as $c=>$ed)
                    @php
                    //dd($ed);
                    @endphp
                    <tr>
                        <td>                                                  
                            {{ $c+1}}
                        </td>
                        <td>                                                  
                           {{$ed->education_type?$ed->educationType->education:''}}
                        </td>
                        <td>                                                  
                            {{ $ed->degree?$ed->course->course_name:''}}
                        </td>
                        <td>                                                  
                            {{ $ed->specialization}}
                        </td>
                        <td>                                                  
                            {{ $ed->institute_name}}
                        </td>
                        <td>                                                  
                            {{ $ed->year_of_passing}}
                        </td>
                        <td>                                                  
                            {{ $ed->marks}}
                        </td>
                        <td>                                                  
                            {{ $ed->percentage > 0 ? $ed->percentage:''}}
                        </td>
                        <td> 
                        @if($ed->doc_file!='')
                            <a href="{{ (url('images/'.$ed->doc_file))}}" class="text-danger">Download</a>
                        @endif                                                 
                             
                        </td>
                        
                        
                        <td><a href="{{url('deleteeducation')}}/{{$ed->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></td>
                    </tr>
                    @endforeach 
                </tbody>                                     
            </table>
        </div>
    </div>
@endsection
@push('js')
<script>
     
     function getcourse(education_master_id)
    {
        var education_master_id=education_master_id;
        if((education_master_id==4) || (education_master_id==5)){
            $('.courselabel').html('Board *');
            $('.specialization').hide();
        }else{
            $('.courselabel').html('Course *');
            $('.specialization').show();
        }
        var $select = $('#course');//$($('#city')).selectize();
        var selectize = $select[0].selectize;
        selectize.renderCache = {};
        selectize.clearOptions();
        selectize.clear();
        selectize.refreshOptions(true);
        $.ajax({
            type:'GET',
            url:"{{url('api/app/all_course')}}",
            data:'education_master_id='+education_master_id,
            dataType: 'json',
            success: function(response) {
                selectize.clearOptions();
                selectize.clear();
                $.each(response.data,function (i, course){
                    selectize.addOption({value: course.id, text: course.course_name });                  
                });
                selectize.refreshOptions(true);              
              
            }
        });
    }
     function addCourse(course_id)
    {
        var course_id=course_id;
        if((course_id==9999) ){
            
            $('.addcourse').show();
            
        }else{
             
            
            $('.addcourse').hide();
        }
       
    }
    //  function getspecialization(course_master_id)
    // {
    //     var course_master_id=course_master_id;
        
        
    //     var $select = $('#specialization');//$($('#city')).selectize();
    //     var selectize = $select[0].selectize;
    //     selectize.renderCache = {};
    //     selectize.clearOptions();
    //     selectize.clear();
    //     selectize.refreshOptions(true);
    //     $.ajax({
    //         type:'GET',
    //         url:"{{url('api/app/all_specialization')}}",
    //         data:'course_master_id='+course_master_id,
    //         dataType: 'json',
    //         success: function(response) {
    //             console.log(response);
    //             selectize.clearOptions();
    //             selectize.clear();
    //             $.each(response.data,function (i, specialization){
    //                 selectize.addOption({value: specialization.id, text: specialization.name });                  
    //             });
    //             selectize.refreshOptions(true);              
                 
    //         }
    //     });
    // }
</script>
@endpush
     
<!doctype html>
<html lang="en" class="pxp-root">
<base href="{{ url('/')}}">  
@include('layouts.htmlheader')


<body style="background-color: var(--pxpMainColorLight);">
    

    <div class="pxp-dashboard-content1">
{{--@extends('admin.layouts.app')

@section('content')--}}


<div class="pt-5 pb-5">
<div class="container" style="max-width: 800px; margin: auto;">
        
<div class="pxp-dashboard-content-details l-r-t-p0 overflow-hidden" >
    <div class="Candidate_details_bg" style="background-image:url('new/images/cover-photo-1.png');">                    
    </div>
    <div class="Candidate_detailsbox">
    
        <img class="dp" src="{{ ($candidate->photo!='')?(url('images/'.$candidate->photo)):(url('/new/images/noimage.png')) }}" alt="">
       
        
        <h5 class="mt-2 mb-3">{{strtoupper($candidate->name)}}</h5>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6">
            @if($candidate->dob!=null)
            <p class="mb-0 ">DOB - <strong>{{$candidate->dob}}</strong></p>
            @endif
            @if($candidate->gender!=null)
            <p class="mb-0 ">Gender - <strong>{{$candidate->gender}}</strong></p>
            @endif
            @if($candidate->present_address!=null)
            <p class="mb-0 ">Address - <strong>{{$candidate->present_address}}</strong></p>
            @endif
            @if($candidate->country!=null)
            <p class="mb-0 ">Nationality - <strong>@isset($candidate->country){{$candidate->countryDetails->nationality}}@endif</strong></p>
            @endif
            
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6">
                <p class="mb-0">Email - <strong>{{$candidate->email}}</strong></p>
                <p class="mb-0 ">Phone - <strong>{{$candidate->phone}}</strong></p>
                @if($candidate->phone2!=null)
                <p class="mb-0 ">Alternate Phone - <strong>{{$candidate->phone2}}</strong></p>
                @endif
                {{--<p class="mb-0 ">Religion - <strong>@isset($candidate->religion){{$candidate->religion}}@endif</strong></p>                
                <p class="mb-0 sm_font">State - @isset($candidate->state)<strong>{{$candidate->stateDetails->state_title}}</strong>@endif</p>
                <p class="mb-0 ">City - @isset($candidate->city)<strong>{{$candidate->cityDetails->name}}<strong>@endif</p>--}}
            </div>
        </div>
        
         
        @if($education_details->count() > 0)
        <h6 class="under_line mt-3 mb-3">Educational Details</h6>
        <div class="table-responvive">
            <table class="Educational_table"> 
                <tbody>
                    <tr>
                        <td>Course/Board</td>
                        <td>Specialization</td>
                        <td>Institute Name</td>
                        <td>Marks / Grade </td>
                        <td>Percentage / CGPA</td>
                        <td><span style="display:block; width:100px">Year</span></td>
                    </tr>
                    @foreach($education_details as $ed)
                    <tr>
                        <td>{{ $ed->course?$ed->course->course_name:''}}</td>
                        <td>{{ $ed->specialization}}</td>
                        <td>{{$ed->institute_name}}</td>
                        <td>{{$ed->marks}}</td>
                        <td>{{$ed->percentage > 0 ? $ed->percentage:''}}</td>
                        <td>{{$ed->year_of_passing}}</td>                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @if($profession_details->count() > 0)
            <h6 class="under_line mt-3 mb-3">Professional Details</h6>
            <h4 class="under_line mt-3 mb-3">Work Experience : {{$candidate->total_experience}}</h6>
            <div class="table-responvive">
            @foreach($profession_details as $p)
                @if($p->current_company=='yes')
                <p class="mb-0"><strong>Current Employer : </strong>{{$p->company_name}}</p>
                <p class="mb-0"><strong>Joining Date : </strong>{{$p->from_date}}</p>
                <p class="mb-0"><strong>Current Salary: </strong>{{$p->current_salary}}</p>
                <p class="mb-0"><strong>Current Location: </strong>{{$p->current_location}} </p>
                <br/>            
                @else
                <p class="mb-0"><strong>Previous Employer : </strong>{{$p->company_name}}</p>
                <p class="mb-0"><strong>Joining Date : </strong>{{$p->from_date}}</p>
                <p class="mb-0"><strong>Till Date: </strong>{{$p->to_date}}</p>
                <br/>
                
                @endif
            @endforeach
                
           </div>
        @endif
             
        <!-- <h6 class="under_line mt-3 mb-3">Independent Courses</h6>
            <ul class="Courses_list">
                <li>
                    <span>HTML &amp; CSS for Beginners – Web Fundamentals</span> – Codecademy.com
                </li>
                <li>
                    <span>Python – Fundamentals and Dynamic Programming </span> - Codecademy.com
                </li>
                <li>
                    <span>JavaScript – Programming Basics, JS Apps and Build Games </span> - Codecademy.com
                </li>
                <li>
                    <span>CS101: Introduction to Computer Science - Building a Web Crawler</span> - Udacity.com
                </li>
                <li>
                    <span>CS50x – Introduction to Computer Science I</span> – edX.org &amp; Harvard University
                </li>
                <li>
                    <span>Calculus One</span> - Ohio State University &amp; Coursera.org
                </li>
                <li>
                    <span>Introduction to Finance</span> - Coursera.org &amp; University of Michigan
                </li>
            </ul> -->
        @php
            $otherTypes=DB::table('others_types_masters')->orderBy('id','ASC')->get();
        @endphp
        @if($other_details->count() > 0)
        @foreach($otherTypes as $ot)   
            <h6 class="under_line mt-3">{{$ot->title}}</h6>           
           
            @foreach($other_details as $p)  
                @if($p->type==$ot->name)                                              
                
                <button type="button" class="btn btn-outline-success btn-sm mt-1 mb-1">{{ $p->value}} </button>
                @endif                                                          
            @endforeach 

        @endforeach
        @endif
         @if($languages->count() > 0)
            <h6 class="under_line mt-3 mb-3">Languages Known</h6>
            <ul class="Courses_list">
            @foreach($languages as $language)
                <li>
                    {{$language->value}} : {{$language->description}}
                </li>
            @endforeach 
            </ul>
        @endif
{{--
        <h6 class="under_line mt-3 mb-3">Personal Information</h6>
            <ul class="Courses_list">
               
                <li>
                    <span>Languages Known:</span> @foreach($languages as $language){{$language->value}} ({{$language->description}}), @endforeach
                </li>
                <li>
                    <span>Hobbies:</span>@foreach($hobbies as $hobby){{$hobby->value}}, @endforeach
                </li> 
            </ul>--}}
        {{--
        <h6 class="under_line mt-3 mb-3">Other Information</h6>
            <ul class="Courses_list">
                <li>
                    <span>Expected Salary:</span> {{$candidate->expected_salary}}
                </li>
                <li>
                    <span>Area of Interest:</span> {{$candidate->area_of_interest}}
                </li>
                <li>
                    <span>Joining Date:</span> {{$candidate->joining_date_prefer}}
                </li>
            </ul>
            --}}
            <div class="text-end">

                {{-- @if($candidate->offer_letter==null)
                <a href="{{url('generateofferletter')}}/{{base64_encode($candidate->id)}}" class="btn rounded-pill pxp-section-cta">Generate Offer Letter</a>
                @endif--}}
                
                <button type="button" id="printBtn" class="btn rounded-pill pxp-section-cta">Print</button>
                
            </div>
    </div>
</div>
</div>
</div>


    @include('layouts.script')
    <script>
    $('#printBtn').click(function() {
    window.print();
});
</script>


    </body>
</html>
{{--@endsection
@push('js')
<script>
//     $('#printBtn').click(function() {
//     window.print();
// });
</script>

@endpush--}}

   
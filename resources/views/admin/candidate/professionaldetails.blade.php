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
            <h4 class="text-themecolor">Professional Details ({{$candidate->candidate_code}})</h4>
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
                <div class="col-md-5">
                    <strong>Email: {{$candidate->email}}</strong>
                </div>
                <div class="col-md-3 text-right">
                    <strong>Phone: {{$candidate->phone}}</strong>
                </div>                
            </div>        
        </div>        
                                
        <form method="post" action="{{url('professionaldetails')}}/{{base64_encode($candidate->id)}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="candidate_id" value="{{$candidate->id}}">
            <div class="row">
                <div class="col-md-6" id="curCom">
                
                    <div class="mb-3">
                        <div><label  class="form-label">Is This Your Current Company?*</label></div>
                        <input type="radio" name="cc" id="cc_yes" value="yes" class="btn1 form-check-input" {{old('cc')? old('cc')=='yes'?"checked":'' :'checked'}}> 
                        <label class="form-check-label" for="cc_yes">Yes</label>
                        <input type="radio" name="cc" value="no" id="cc_no" class="btn2 form-check-input" {{old('cc')=='no'?"checked":''}}>
                        <label class="form-check-label" for="cc_no">No</label>
                         
                    </div>
                     
                </div>
            </div>
            <div class="row">
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label  class="form-label">Employer Name *</label>
                        <input type="text" id="company" name="company" class="form-control" value="{{old('company')}}" required>
                    </div>
                    @if($errors->has('company'))
                        <label class="text-danger">{{ $errors->first('company') }}</label>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label  class="form-label">Designation *</label>
                        <input type="text" id="job_role" name="job_role" class="form-control" value="{{old('job_role')}}" required>
                    </div>
                    @if($errors->has('job_role'))
                        <label class="text-danger">{{ $errors->first('job_role') }}</label>
                    @endif
                </div>      
                <div class="col-md-4">
                    <div class="mb-3">
                        <label  class="form-label">Joining Date *</label>
                        <input type="date" id="from_date" name="from_date" class="form-control" value="{{old('from_date')}}" required>
                    </div>
                    @if($errors->has('from_date'))
                        <label class="text-danger">{{ $errors->first('from_date') }}</label>
                    @endif
                </div>          
            </div>
            <div class="row">
                
                <div class="col-md-3" id="toDate">
                    <div class="mb-3">                         
                        <label  class="form-label">Worked till *</label>
                        <input type="date" id="to_date" name="to_date" class="form-control" value="{{old('to_date')}}" >
                         
                    </div>
                    @if($errors->has('to_date'))
                        <label class="text-danger">{{ $errors->first('to_date') }}</label>
                    @endif
                </div>
                <div class="col-md-3 cur_salary" >
                    <div class="mb-3">
                        <label  class="form-label" for="cur_salary">Current Salary (Annual)(<i class="fa fa-inr"></i>)</label>
                        <input type="text" id="cur_salary" name="cur_salary" class="form-control" value="{{old('cur_salary')}}" >
                    </div>     
                    @if($errors->has('cur_salary'))
                        <label class="text-danger">{{ $errors->first('cur_salary') }}</label>
                    @endif                
                </div>
                <div class="col-md-3 cur_location" >
                    <div class="mb-3">
                        <label  class="form-label" for="cur_location">Current Location </label>
                        <input type="text" id="cur_location" name="cur_location" class="form-control" value="{{old('cur_location')}}" >
                    </div>     
                    @if($errors->has('cur_location'))
                        <label class="text-danger">{{ $errors->first('cur_location') }}</label>
                    @endif                
                </div>
                 
                <div class="col-md-3">
                    <div class="mb-3">
                        <label  class="form-label">Job Profile </label>
                        <textarea id="description" name="description" class="form-control" >{{old('description')}} </textarea>
                    </div>        
                    @if($errors->has('description'))
                        <label class="text-danger">{{ $errors->first('description') }}</label>
                    @endif             
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label  class="form-label">&nbsp;</label>
                        <button class="btn rounded-pill pxp-section-cta btn-block">Add</button>
                    </div>                     
                </div>
            </div>
        </form>
        <hr>
        <div class="table-responvive">
            <table class="table footable"> 
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employer Name</th>
                        <th>Designation</th>
                        <th>Joining Date</th>
                        <th>Worked Till</th>
                        <th>Annual Salary (<i class="fa fa-inr"></i>)</th>
                        <th>Location</th>
                        <th>Job Profile</th>
                        
                        <th>Action</th>
                    </tr>
                </thead>  
                <tbody>                                    
                    @foreach($profession as $c=>$p)
                    <tr>
                        <td>{{ $c+1}}</td>
                        <td>{{ $p->company_name}}</td>
                        <td>{{ $p->job_role}}</td>
                        <td>{{ $p->from_date}}</td>
                        <td>{{ $p->current_company=='yes' ? "Current Employer" : $p->to_date}}</td>
                        <td> {{ round($p->current_salary,4)}}</td>                                          
                        <td>{{ $p->current_location}}</td> 
                        <td>{{ $p->description}}</td>                                          
                                                                 
                        
                        <td><a href="{{url('deleteprofession')}}/{{$p->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>  </td>
                    </tr>
                    @endforeach
                </tbody>                                      
            </table>
        </div>
    </div>
 
   

@endsection
@push('js')
<script>
    $(document).ready(function(){
        
        var is_cc=$('input[name="cc"]:checked').val();
        
       console.log(is_cc); 
       checkCurrent(is_cc);
       
         
        
    });

    $(".btn1").click(function(){
        var is_cc=$('input[name="cc"]:checked').val();
        checkCurrent(is_cc);
            
    });
    $(".btn2").click(function(){
        var is_cc=$('input[name="cc"]:checked').val();
        checkCurrent(is_cc);
    });

    function checkCurrent(is_cc){
        // alert(is_cc);
            if(is_cc=='yes'){
                $("#toDate").hide();
                $(".cur_salary").show();        
                $(".cur_location").show();
            }
            else{
                    $("#toDate").show();
                    $(".cur_salary").hide();        
                    $(".cur_location").hide();
            }
       }
</script>
@endpush
    

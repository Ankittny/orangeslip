@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
 
<div class="pxp-dashboard-content-details">
                <h1>Candidate Registration</h1>
                <p class="pxp-text-light">Basic Details Of Candidate</p>
                
                <form method="post" action="{{url('registration')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password" class="form-label">Name *</label>
                                <input type="text" id="cname" name="cname"  class="form-control to-upper" placeholder="Enter Full Name" value="{{ old('cname') }}" required>
                            </div>
                            @if($errors->has('cname'))
                                <label class="text-danger">{{ $errors->first('cname') }}</label>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">Email *</label>
                                <input type="email" id="email" name="email"  class="form-control to-upper" placeholder="Enter Email" value="{{ old('email') }}" required>
                            </div>
                            @if($errors->has('email'))
                                <label class="text-danger">{{ $errors->first('email') }}</label>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">Country *</label>
                                <select   name="country" id="country"  required>                                                                    
                                    @foreach($country as $c)
                                    <option value="{{$c->id}}"  {{old('country',69)==$c->id ? 'selected':''}} > {{$c->name}} (+{{$c->calling_code}})</option>
                                    @endforeach                                            
                                </select> 
                            </div>
                            @if($errors->has('country'))
                                <label class="text-danger">{{ $errors->first('country') }}</label>
                            @endif
                        </div>
                    
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password" class="form-label">Phone *</label>
                                 
                                <input type="text" id="phone" name="phone" class="form-control" maxlength="10" placeholder="Enter Phone Number" value="{{ old('phone') }}" required>
                             
                            </div>
                            @if($errors->has('phone'))
                                <label class="text-danger">{{ $errors->first('phone') }}</label>
                            @endif
                        </div>
                       
                    </div>
                    <div class="row">
                     
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password" class="form-label">Gender *</label>
                                <select  id="gender" name="gender"  required>
                                    <option value='' >Select</option>
                                    <option value='male' {{ old('gender') == 'male' ? "selected" : ""}}>MALE</option>
                                    <option value='female' {{ old('gender') == 'female' ? "selected" : ""}}>FEMALE</option>
                                    <option value='other' {{ old('gender') == 'other' ? "selected" : ""}}>OTHER</option>
                                </select>
                            </div>
                            @if($errors->has('gender'))
                                <label class="text-danger">{{ $errors->first('gender') }}</label>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">State *</label>
                                <select  id="state" name="state"  onchange="getCity(this.value)"  required>
                                    <option value=''>Select state</option>
                                    @foreach($states as $s)
                                    <option value='{{$s->state_id}}' {{ old('state') == $s->state_id ? "selected" : ""}}>{{$s->state_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('state'))
                                <label class="text-danger">{{ $errors->first('state') }}</label>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">City *</label>
                                <select  id="city" name="city"  placeholder="Select City" required>
                                                                       
                                </select>
                            </div>
                            @if($errors->has('city'))
                                <label class="text-danger">{{ $errors->first('city') }}</label>
                            @endif
                        </div>
                    </div>
                     
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="" class="form-label">Upload CV (pdf)</label>

                                <div class="custom-file">
                                    <input type="file" id="cv_scan" name="cv_scan" class="custom-file-input" >
                                    <label class="custom-file-label" for="cv_scan">Choose file</label>
                                </div>

                               
                            </div>
                            @if($errors->has('cv_scan'))
                                <label class="text-danger">{{ $errors->first('cv_scan') }}</label>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label  class="form-label">Job Role *</label>
                                <select  id="job_role" name="job_role" required>
                                    <option value="">Select Job Role</option>
                                @foreach($job_role as $role)
                                <option  value="{{$role->id}}" {{ old('job_role') == $role->id ? "selected" : ""}}>{{$role->name}}</option>                                
                                @endforeach
                                </select>
                            </div>
                            @if($errors->has('job_role'))
                                <label class="text-danger">{{ $errors->first('job_role') }}</label>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label  class="form-label">Total Experience (in Year) *</label>
                                <select  id="total_experience" name="total_experience"  required>
                                    <option value="Fresher">Fresher (Less Then 1 Year)</option>
                                    @for($i=1;$i<=30;$i++)
                                    <option value="{{$i}}" {{ old('total_experience') == $i ? "selected" : ""}}>{{$i}} Year</option>
                                    @endfor
                                </select>
                            </div>
                            @if($errors->has('total_experience'))
                                <label class="text-danger">{{ $errors->first('total_experience') }}</label>
                            @endif
                        </div>                         
                    </div>
                    <div class="row">
                    <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">DOB *</label>
                                <input type="date" id="dob" name="dob" class="form-control" value="{{old('dob')}}" required>
                            </div>
                            @if($errors->has('dob'))
                                <label class="text-danger">{{ $errors->first('dob') }}</label>
                            @endif
                        </div>
                        @isset($all_business)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">Business *</label>
                                <select  id="business_id" name="business_id"  onchange="getHr(this.value)"  required>
                                    <option value="" selected>Select business</option>
                                    @foreach($all_business as $business)
                                    <option value='{{$business->id}}' {{ old('business_id') == $business->id ? "selected" : ""}}>{{$business->business->business_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('business_id'))
                                <label class="text-danger">{{ $errors->first('business_id') }}</label>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">HR *</label>
                                <select   id="hr_id" name="hr_id"  required>
                                             
                               
                                </select>
                            </div>                           
                        </div>
                       
                        @endif
                        @isset($all_hr)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">HR *</label>
                                <select  name="hr_id"  required>
                                    <option value='' >Select HR</option>
                                    @foreach($all_hr as $hr)
                                    <option value='{{$hr->user_id}}' {{ old('hr_id') == $hr->user_id ? "selected" : ""}}>{{$hr->user->first_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('hr_id'))
                                <label class="text-danger">{{ $errors->first('hr_id') }}</label>
                            @endif
                        </div>
                        @endif
                        
                    </div>
                     

                    <div class="mt-2 mt-lg-3 text-center">
                        <button class="btn rounded-pill pxp-section-cta">Save</button>
                    </div>
                </form>
            </div>

@endsection
@push('js')
<script>
    $( document ).ready(function() {
  
    var state_id=$('#state').find(":selected").val();
    var business_id=$('#business_id').find(":selected").val();
   
   var selected_city_id='{{old("city")}}';
    getCity(state_id,selected_city_id);
   var selected_hr_id='{{old("hr_id")}}';
   getHr(business_id,selected_hr_id);
    
});

    function getCity(state_id,selected_city_id=null)
    {
        var state_id=state_id;
         
        var $select = $('#city');//$($('#city')).selectize();
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
                    selectize.addOption({value: city.id, text: city.name });                  
                });
                selectize.refreshOptions(true); 
                if(selected_city_id!=null){
                    selectize.setValue(selected_city_id);
                }             
                // console.log(response);
            }
        });
    }

    function getHr(business_id,selected_hr_id=null)
    {
        var business_id=business_id;
        //console.log(business_id);
        var $select = $($('#hr_id')).selectize();
        var selectize = $select[0].selectize;
        selectize.renderCache = {};
        selectize.clearOptions();
        selectize.clear();
        selectize.refreshOptions(true);
        $.ajax({
            type:'GET',
            url:"{{url('get_hr')}}",
            data:'business_id='+business_id,
            success: function(response) {
                selectize.clearOptions();
                selectize.clear();
                $.each(response,function (i, hr){
                    selectize.addOption({value: hr.user_id, text: hr.first_name});
                    
                });
                selectize.refreshOptions(); 
                if(selected_hr_id!=null){
                    selectize.setValue(selected_hr_id);
                } 
               // console.log(response);
            }
        });
    }

</script>
@endpush
@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif

            <div class="pxp-dashboard-content-details">
                <h1>Create Offer Letter</h1>
                <p class="pxp-text-light">Basic Details Of Candidate</p>
                
                <form method="post" action="{{url('regenerate_offer_letter')}}/{{base64_encode($offer_letter->id)}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password" class="form-label">Name *</label>
                                <input type="text" id="cname" name="cname" class="form-control" placeholder="Enter Full Name" value="@isset($candidate){{$candidate->name}}@else{{ old('cname')}}@endif" required>
                            </div>
                            @if($errors->has('cname'))
                                <label class="text-danger">{{ $errors->first('cname') }}</label>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">Email *</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email" value="@isset($candidate){{$candidate->email}}@else{{ old('email')}}@endif" required>
                            </div>
                            @if($errors->has('email'))
                                <label class="text-danger">{{ $errors->first('email') }}</label>
                            @endif
                        </div>
                    
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password" class="form-label">Country *</label>
                                
                                <select   name="country" id="country"  required>                                                                    
                                    @foreach($country as $c)
                                    <option value="{{$c->id}}" @isset($candidate){{$c->id==$candidate->country ? "selected" : " " }}@else{{$c->id==old('country') ? "selected" : " " }}  @endif> {{$c->name}} (+{{$c->calling_code}})</option>
                                    @endforeach                                            
                                </select> 
                            </div>
                            @if($errors->has('phone'))
                                <label class="text-danger">{{ $errors->first('phone') }}</label>
                            @endif
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone *</label>
                                
                                <input type="text" id="phone" name="phone" class="form-control" maxlength="10" placeholder="Enter Phone Number" value="@isset($candidate){{$candidate->phone}}@else{{ old('phone')}}@endif" required>
                             
                            </div>
                            @if($errors->has('phone'))
                                <label class="text-danger">{{ $errors->first('phone') }}</label>
                            @endif
                        </div>

                        
                       
                    </div>
                    <div class="row">                     
                    <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password" class="form-label">Gender *</label>
                                <select  id="gender" name="gender"  required>
                                    <option value='' selected>Select</option>
                                    <option value='male' @isset($candidate){{$candidate->gender=='male'? "selected" : ""}}@else{{ old('gender')=="male" ? "selected" : "" }}@endif >MALE</option>
                                    <option value='female' @isset($candidate){{$candidate->gender=='female'? "selected" : ""}}@else{{ old('gender')=="female" ? "selected" : "" }}@endif>FEMALE</option>
                                    <option value='other' @isset($candidate){{$candidate->gender=='other'? "selected" : ""}}@else{{ old('gender')=="other" ? "selected" : "" }}@endif>OTHER</option>
                                </select>
                            </div>
                            @if($errors->has('gender'))
                                <label class="text-danger">{{ $errors->first('gender') }}</label>
                            @endif
                        </div>
                       
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">State *</label>
                               
                                <select  id="state" name="state"  onchange="getCity(this.value)" required>
                                <option value=''>Select state</option>
                                    @foreach($states as $s)
                                    <option value='{{$s->state_id}}' @isset($candidate){{$candidate->state==$s->state_id? "selected" : ""}} @else  {{ old('state') == $c->id ? 'selected' : '' }} @endif>{{$s->state_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('state'))
                                <label class="text-danger">{{ $errors->first('state') }}</label>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">City *</label>
                                
                                <select  id="city" name="city"  required>
                                    @foreach($city as $cty)
                                    <option value='{{$cty->id}}' @isset($candidate){{$candidate->city==$cty->id? "selected" : ""}} @else {{ old('city', $user->profile->country) == $c->id ? 'selected' : '' }} @endif>{{$cty->name}}</option>
                                    @endforeach
                                                               
                                </select>
                            </div>
                                @if($errors->has('city'))
                                    <label class="text-danger">{{ $errors->first('city') }}</label>
                                @endif
                        </div>

                             
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">Total Experience (in Year) *</label>
                                 
                                <select  id="total_experience" name="total_experience"  required>
                                    <option value="Fresher">Fresher (Less Then 1 Year)</option>
                                    @for($i=1;$i<=30;$i++)
                                    <option value="{{$i}}" @isset($candidate){{$candidate->total_experience==$i ? "selected" : ""}}@else{{ old('total_experience')==$i ? "selected" : "" }}@endif>{{$i}} Year</option>
                                    @endfor
                                </select>
                            </div>
                            @if($errors->has('total_experience'))
                                <label class="text-danger">{{ $errors->first('total_experience') }}</label>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">DOB *</label>
                                <input type="date" id="dob" name="dob" class="form-control" value="{{old('dob', $candidate->dob)}}" required>
                            </div>
                            @if($errors->has('dob'))
                                <label class="text-danger">{{ $errors->first('dob') }}</label>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">Upload CV (doc/pdf)</label>
                                <div class="custom-file">
                                    <input type="file" id="cv_scan" name="cv_scan" class="custom-file-input" >
                                    <label class="custom-file-label" for="cv_scan">Choose file</label>
                                </div>
                                
                                
                            </div>
                            @if($errors->has('cv_scan'))
                                <label class="text-danger">{{ $errors->first('cv_scan') }}</label>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="permanent_address" class="form-label">Permanent Address</label>
                                <textarea rows="5" id="permanent_address" name="permanent_address" class="form-control" >{{old('permanent_address', $candidate->permanent_address)}}</textarea>
                            </div>
                        </div>  
                    </div>                    
                   
                    

                     
               
                <p class="pxp-text-light">Offer Details</p>
                <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Job Role *</label>
                                 
                                <select  id="job_role" name="job_role"  required>
                                    <option >Select Job Role</option>
                                @foreach($job_role as $role)
                                <option  value="{{$role->id}}" @isset($candidate){{$candidate->job_role==$role->id ? "selected" : ""}}@else{{ old('job_role')==$role->id ? "selected" : "" }}@endif>{{$role->name}}</option>                                
                                @endforeach
                                </select>
                            </div>
                            @if($errors->has('job_role'))
                                <label class="text-danger">{{ $errors->first('job_role') }}</label>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Place Of Joining *</label>
                                <input type="text" id="place_of_joining" name="place_of_joining" class="form-control" placeholder="Enter Place Of Joining" value="@isset($offer_letter){{$offer_letter->place_of_joining}}@else{{ old('place_of_joining')}}@endif" required>
                            </div>
                            @if($errors->has('place_of_joining'))
                                <label class="text-danger">{{ $errors->first('place_of_joining') }}</label>
                            @endif
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Joining Date *</label>
                                <input type="date"  class="form-control" id="joining_date" name="joining_date" value="{{old('joining_date', $offer_letter->joining_date)}}" required>   
                            </div>
                            @if($errors->has('joining_date'))
                                <label class="text-danger">{{ $errors->first('joining_date') }}</label>
                            @endif
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Time Of Joining *</label>
                               
                                <input type="time"  id="time_of_joining" name="time_of_joining" class="form-control" placeholder="Enter Time Of Joining" value="{{old('time_of_joining', $offer_letter->time_of_joining)}}" required>
                            </div>
                            @if($errors->has('time_of_joining'))
                                <label class="text-danger">{{ $errors->first('time_of_joining') }}</label>
                            @endif
                        </div>

                        
                    
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Annual CTC (<i class="fa fa-inr"></i>)*</label>
                                <input type="number"  id="annual_ctc" name="annual_ctc" class="form-control" value="{{old('annual_ctc', $offer_letter->annual_ctc)}}" placeholder="Enter Annual CTC" required>
                            </div>
                            @if($errors->has('annual_ctc'))
                                <label class="text-danger">{{ $errors->first('annual_ctc') }}</label>
                            @endif
                        </div>
                        
                  
                        {{--<div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Offer Letter </label>
                                <input type="file" id="offer_letter" name="offer_letter" class="form-control" >
                            </div>
                            @if($errors->has('offer_letter'))
                                <span class="alert-danger">{{ $errors->first('offer_letter') }}</span>
                            @endif
                        </div>
                        <div>
                            <legend>Salary Breakup:</legend>
                            <label  class="form-label">Earnings</label>
                            <div class="table-responvive">
                                <table class="table table-bordered" id="dynamicEarning"> 
                                    
                                
                                    
                                    @isset($old_earning)
                                    @foreach($old_earning as $key=>$oldEr)
                                    <tr>
                                        <td width="40%">
                                        @if($key==0)
                                            <label class="form-label">Components</label>
                                            @endif
                                            <select  name="salary[earning][0][component]"  required>
                                                <option value="" selected>Select</option>
                                                @foreach($earnings as $earning)    
                                                <option value="{{$earning->component}}" {{$earning->component==$oldEr->component ? "selected" : ""}}>{{$earning->component}}</option>
                                                @endforeach
                                            </select>                                        
                                            
                                        </td>
                                        <td width="40%">
                                        @if($key==0)
                                            <label class="form-label">Amount (<i class="fa fa-inr"></i>)</label>
                                            @endif
                                            <input type="number" name="salary[earning][0][amount]" min="0" class="form-control" value="{{$oldEr->amount}}" required>
                                            
                                        </td>
                                        <td width="20%">
                                            
                                            @if($key==0)
                                            <label>&nbsp;</label>
                                            <button type="button" name="add" id="dynamic-earning" class="btn btn-block btn btn-outline-success"><i class="fa fa-plus"></i>Add more</button>
                                            @else
                                            <button type="button" class="btn btn-outline-danger remove-earning-field">Delete</button>
                                            @endif

                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td width="40%">
                                            <label class="form-label">Components</label>
                                            <select  name="salary[earning][0][component]"  required>
                                                <option value="" selected>Select</option>
                                                @foreach($earnings as $earning)    
                                                <option value="{{$earning->component}}" >{{$earning->component}}</option>
                                                @endforeach
                                            </select>                                        
                                            
                                        </td>
                                        <td width="40%">
                                            <label class="form-label">Amount (<i class="fa fa-inr"></i>)</label>
                                            <input type="number" name="salary[earning][0][amount]" min="0" class="form-control" required>
                                            
                                        </td>
                                        <td width="20%">
                                        <label>&nbsp;</label>
                                            <button type="button" name="add" id="dynamic-earning" class="btn btn-block btn btn-outline-success"><i class="fa fa-plus"></i>Add more</button>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>

                            <div class="table-responvive">
                                <table class="table table-bordered" id="dynamicDeduction"> 
                                    
                                    <label class="form-label">Deductions</label>
                                    @isset($old_deduction)
                                    @foreach($old_deduction as $keyd=>$oldDed)
                                    <tr>
                                        <td width="40%">
                                        @if($keyd==0)
                                            <label class="form-label">Components</label>
                                            @endif
                                            <select   name="salary[deduction][0][component]"  required>
                                                <option value="" selected>Select</option>
                                                @foreach($deductions as $deduction)    
                                                <option value="{{$deduction->component}}" {{$deduction->component==$oldDed->component ? "selected" : ""}}>{{$deduction->component}}</option>
                                                @endforeach
                                            </select>
                                            
                                            
                                        </td>
                                        <td width="40%">
                                        @if($keyd==0)
                                            <label class="form-label">Amount (<i class="fa fa-inr"></i>)</label>
                                            @endif
                                            <input type="number" name="salary[deduction][0][amount]" min="0" class="form-control" value="{{$oldDed->amount}}" required>
                                            
                                        </td>
                                        <td width="20%">
                                        @if($keyd==0)
                                        <label>&nbsp;</label>
                                            <button type="button" name="add" id="dynamic-deduction" class="btn btn-block btn btn-outline-success"><i class="fa fa-plus"></i>Add more</button>
                                            @else
                                            <button type="button" class="btn btn-outline-danger remove-earning-field">Delete</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td width="40%">
                                            <label class="form-label">Components</label>
                                            <select   name="salary[deduction][0][component]"  required>
                                                <option value="" selected>Select</option>
                                                @foreach($deductions as $deduction)    
                                                <option value="{{$deduction->component}}">{{$deduction->component}}</option>
                                                @endforeach
                                            </select>
                                            
                                            
                                        </td>
                                        <td width="40%">
                                            <label class="form-label">Amount (<i class="fa fa-inr"></i>)</label>
                                            <input type="number" name="salary[deduction][0][amount]" min="0" class="form-control" required>
                                            
                                        </td>
                                        <td width="20%">
                                        <label>&nbsp;</label>
                                            <button type="button" name="add" id="dynamic-deduction" class="btn btn-block btn btn-outline-success"><i class="fa fa-plus"></i>Add more</button>
                                        </td>
                                    </tr>
                                    @endif
                                </table>                               
                            </div>
                        </div>--}}
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="mb-3">
                                <label for="pxp-candidate-new-password" class="form-label">Offer Letter Template</label>
                                <select  id="tmpl" name="tmpl" >
                                    <option value='' selected>Select</option>
                                    <option value='1'>Template 1</option>
                                    <option value='2'>Template 2</option>
                                    <option value='3'>Template 3</option>
                                </select>
                        </div>
                    </div>       -->
                    <div class="row">
                        @isset($all_business)
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">Business *</label>
                                <select  id="business_id" name="business_id"   onchange="getHr(this.value)"  required>
                                    <option  value="">Select business</option>
                                    @foreach($all_business as $business)
                                    <option value='{{$business->id}}' {{old('business_id', $offer_letter->business_id)==$business->id ? 'selected':''}}>{{$business->business->business_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('business_id'))
                                <label class="text-danger">{{ $errors->first('business_id') }}</label>
                            @endif
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">HR *</label>
                                <select   id="hr_id" name="hr_id"  required>
                                    <option value='' selected>Select HR</option>
                                    
                                </select>
                            </div>
                           
                        </div>
                       
                        @endif
                        @isset($all_hr)
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password-repeat" class="form-label">HR *</label>
                                <select   id="hr_id" name="hr_id"  required>
                                    <option value='' selected>Select HR</option>
                                    @foreach($all_hr as $hr)
                                    <option value='{{$hr->id}}' {{old('hr_id', $offer_letter->hr_id)==$hr->id ? 'selected':''}}>{{$hr->first_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('hr_id'))
                                <label class="text-danger">{{ $errors->first('hr_id') }}</label>
                            @endif
                        </div>
                        @endif
                    </div>                     
                         
                    <div class="mt-3 mt-lg-3">
                        
                        <button class="btn rounded-pill pxp-section-cta">Save</button>
                    </div>
                </form>
            </div>

@endsection
@push('script')
<script>
    
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
                console.log(response);
            }
        });
    }


    function getHr(business_id)
    {
        var business_id=business_id;
        //console.log(business_id);
        var $select = $($('#hr_id')).selectize();
        var selectize = $select[0].selectize;
        $.ajax({
            type:'GET',
            url:"{{url('get_hr')}}",
            data:'business_id='+business_id,
            success: function(response) {
                var trHTML = '';
                $.each(response,function (i, hr){
                    selectize.addOption({value: hr.id, text: hr.first_name});
                    
                });
                selectize.refreshOptions(); 
               // console.log(response);
            }
        });
    }
    

</script>
 
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script> -->
<script type="text/javascript">
    var i = 0;
    //$(document).on("click",".dynamic-ar",function(){
   $("#dynamic-earning").click(function () {
        ++i;
        $("#dynamicEarning").append('<tr><td width="40%"><select  name="salary[earning]['+ i +'][component]"  class="form-control" required> <option value="" selected>Select</option>@foreach($earnings as $earning)<option value="{{$earning->component}}">{{$earning->component}}</option>@endforeach</select></td><td width="40%"><input type="number" min="0" name="salary[earning]['+ i +'][amount]" class="form-control" required></td><td width="20%"><button type="button" class="btn btn-outline-danger remove-earning-field">Delete</button></td></tr>'
        );
    });
    $(document).on('click', '.remove-earning-field', function () {
        $(this).parents('tr').remove();
    });
 
    
    var j = 0;
    //$(document).on("click",".dynamic-ar",function(){
   $("#dynamic-deduction").click(function () {
        ++j;
        $("#dynamicDeduction").append('<tr><td width="40%"><select  name="salary[deduction]['+ j +'][component]" class="form-control" required> <option value="" selected>Select</option>@foreach($deductions as $deduction)<option value="{{$deduction->component}}">{{$deduction->component}}</option>@endforeach</select></td><td width="40%"><input type="number" min="0" name="salary[deduction]['+ j +'][amount]" class="form-control" required></td><td width="20%"><button type="button" class="btn btn-outline-danger remove-deduction-field">Delete</button></td></tr>'
        );
    });
    $(document).on('click', '.remove-deduction-field', function () {
        $(this).parents('tr').remove();
    });
</script>
@endpush
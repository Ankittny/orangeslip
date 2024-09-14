@extends('admin.layouts.app')
@section('content')
<div class="pxp-dashboard-content-details">
    <h1>Create Business</h1>
    <p class="pxp-text-light">Add new Business.</p>

    <form action="{{ route('business.store') }}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" name="enroll_id" value="@isset($company) {{$company->id}} @endif">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="business_name" class="form-label {{ $errors->has('business_name')?' has-error':' has-feedback' }}">Business Name *</label>
                    <input type="text" id="business_name" name="business_name" class="form-control" placeholder="Enter Business Name" value="@isset($company){{$company->business_name}}@else{{ old('business_name')}}@endif" required>
                    @if ($errors->has('business_name'))
                        <label class="text-danger">{{ $errors->first('business_name') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label {{ $errors->has('email')?' has-error':' has-feedback' }}">Business Email *</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Business Email" value="@isset($company){{$company->email}}@else{{ old('email')}}@endif" required>
                    @if ($errors->has('email'))
                        <label class="text-danger">{{ $errors->first('email') }}</label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="gst" class="form-label {{ $errors->has('gst')?' has-error':' has-feedback' }}">GST NO. *</label>
                    <input type="text" id="gst" name="gst" class="form-control gst" placeholder="Enter GST No." value="@isset($company){{$company->gst}}@else{{ old('gst')}}@endif" required>
                    @if ($errors->has('gst'))
                        <label class="text-danger">{{ $errors->first('gst') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="pan" class="form-label {{ $errors->has('pan')?' has-error':' has-feedback' }}">PAN NO. *</label>
                    <input type="text" id="pan" name="pan" class="form-control pan" placeholder="Enter PAN NO." value="@isset($company){{$company->pan}}@else{{ old('pan')}}@endif" required>
                    @if ($errors->has('pan'))
                        <label class="text-danger">{{ $errors->first('pan') }}</label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="owner_first_name {{ $errors->has('owner_first_name')?' has-error':' has-feedback' }}" class="form-label">Contact Person First Name *</label>
                    <input type="text" id="owner_first_name" name="owner_first_name" class="form-control" placeholder="Enter Contact Person First Name" value="@isset($company){{$company->owner_first_name}}@else{{ old('owner_first_name')}}@endif" required>
                    @if ($errors->has('owner_first_name'))
                        <label class="text-danger">{{ $errors->first('owner_first_name') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="owner_last_name {{ $errors->has('owner_last_name')?' has-error':' has-feedback' }}" class="form-label">Contact Person Last Name * </label>
                    <input type="text" id="owner_last_name" name="owner_last_name" class="form-control" placeholder="Enter Contact Person Last Name" value="@isset($company){{$company->owner_last_name}}@else{{ old('owner_last_name')}}@endif" required>
                    @if ($errors->has('owner_last_name'))
                        <label class="text-danger">{{ $errors->first('owner_last_name') }}</label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label  class="form-label">Country *</label>
                     
                    <select   name="country" id="country"  required>                                                                    
                        @foreach($country as $c)
                        <option value="{{$c->id}}" @isset($company) {{$c->id==$company->country?"selected":"" }}@else {{$c->id==69?"selected":""}} @endif > {{$c->name}} (+{{$c->calling_code}})</option>
                        @endforeach                                            
                    </select>    
                     
                </div>
                @if ($errors->has('country'))
                        <label class="text-danger">{{ $errors->first('country') }}</label>
                    @endif
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label  class="form-label">Mobile Number *</label>
                     
                        <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Enter Mobile No" value="@isset($company){{$company->mobile_no}}@else{{ old('mobile_no')}}@endif" required>
                     
                </div>
                @if ($errors->has('mobile_no'))
                        <label class="text-danger">{{ $errors->first('mobile_no') }}</label>
                    @endif
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label  class="form-label">No Of Employee *</label>
                    <select id="no_of_employee" name="no_of_employee"   required>

                        @foreach($allRange as $range)
                        <option value="{{$range->id}}" @isset($company){{$company->no_of_employee==$range->id ? "selected":""}}@else{{ $range->id==old('no_of_employee')?"selected" :""}}@endif>{{$range->range_start}} - {{$range->range_end}}</option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('no_of_employee'))
                        <label class="text-danger">{{ $errors->first('no_of_employee') }}</label>
                    @endif
            </div>            
           
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="referral_code" class="form-label">Referral Code </label>
                    <input type="text" id="referral_code" name="referral_code" class="form-control" placeholder="Enter Referral Code" value="@isset($company){{old('referral_code', $company->referral_code)}}@endif">
                </div>
                   
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="registration_date" class="form-label">Registration Date </label>
                    <input type="date" id="registration_date" name="registration_date" class="form-control" placeholder="Enter Registration Date" value="{{ old('registration_date')}}">
                </div>
                   
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="business_address" class="form-label">Business Address</label>
                    <input type="text" id="business_address" name="business_address" class="form-control" placeholder="Enter Business Address" value="{{ old('business_address')}}">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="registration_doc" class="form-label">Registration Doc (jpg/jpeg/png) *</label>
                    <div class="custom-file">
                        <input type="file" id="registration_doc" name="registration_doc" class="custom-file-input" placeholder="Enter Registration Doc" required>
                        <label class="custom-file-label" for="registration_doc">Choose file</label>
                    </div>
                   
                </div>
                    @if ($errors->has('registration_doc'))
                        <label class="text-danger">{{ $errors->first('registration_doc') }}</label>
                    @endif
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="business_logo" class="form-label">Business Logo (jpg/jpeg/png) *</label>
                    <div class="custom-file">
                    <input type="file" id="business_logo" name="business_logo" class="custom-file-input" placeholder="Enter Business Logo" required>
                        <label class="custom-file-label" for="business_logo">Choose file</label>
                    </div>
                    @if ($errors->has('business_logo'))
                        <label class="text-danger">{{ $errors->first('business_logo') }}</label>
                    @endif
                   
                </div>
            </div>
        </div>
       
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="registration_doc" class="form-label">Status</label>
                    <select id="status" name="status" >
                       
                        <option value="2">Inactive</option>
                        <option value="1" selected>Active</option>
                    </select>
                </div>
                 
            </div>
        </div>
        <div class="table-responsive">  
        <label for="document">Contact Person Details</label> 
            <table class="table" id="dynamicAddRemove"> 
                               
                                    <tr>
                                        <td>
                                            <label for="document">Name </label>
                                            <input type="text" name="addMoreInputFields[0][name]" class="form-control">
                                        </td>
                                        <td>
                                            <label for="document">Designation </label>
                                            <input type="text" name="addMoreInputFields[0][desg]" class="form-control">
                                        </td>
                                        <td>
                                            <label for="document">Contact No </label>
                                            <input type="text" name="addMoreInputFields[0][phone]" class="form-control">
                                        </td>
                                        <td>
                                            <label for="document">Email </label>
                                            <input type="text" name="addMoreInputFields[0][email]" class="form-control">
                                        </td>
                                        <td >
                                            <label for="document">&nbsp;</label>
                                            <div style="width:130px">
                                            <button type="button" name="add" id="dynamic-ar" class="btn btn-block btn btn-outline-success"><i class="fa fa-plus"></i> Add more</button>
                                            </div>
                                        </td>
                                    </tr>
                                        
                                </table>
            </div>
        <div class="mt-2 text-center">
            <button class="btn rounded-pill pxp-section-cta">Save</button>
        </div>
    </form>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function(){     
        
        $(".pan").change(function () {      
        var inputvalues = $(this).val();      
          var regex = /[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/;    
          if(!regex.test(inputvalues)){  
            alert("invalid PAN no");        
          $(".pan").val("");    
          $(".pan").focus(); 
         
          return regex.test(inputvalues);    
          }    
        });      
            
        });    
    $(document).ready(function(){     
        
        $(".gst").change(function () {    
                var inputvalues = $(this).val();    
                var gstinformat = new RegExp('^[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}[1-9A-Za-z]{1}[1-9A-Za-z]{1}$');    
                if (gstinformat.test(inputvalues)) {    
                    return true;    
                } else {    
                    alert('Please Enter Valid GSTIN Number');    
                    $(".gst").val('');    
                    $(".gst").focus();    
                }    
            });          

            
        });    
        
</script>
<script type="text/javascript">
    var i = 0;
    //$(document).on("click",".dynamic-ar",function(){
   $("#dynamic-ar").click(function () {
        ++i;
        $("#dynamicAddRemove").append('<tr><td><input type="text" name="addMoreInputFields['+i+'][name]" class="form-control" required></td><td><input type="text" name="addMoreInputFields['+i+'][desg]" class="form-control" required></td><td><input type="text" name="addMoreInputFields['+i+'][phone]" class="form-control" required>  </td><td><input type="text" name="addMoreInputFields['+i+'][email]" class="form-control" required></td>  <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td></tr>'
            );
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('tr').remove();
    });
</script>
@endpush
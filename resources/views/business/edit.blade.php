@extends('admin.layouts.app')
@section('content')
<div class="pxp-dashboard-content-details">
    
    <div class="d-flex justify-content-between">
        <h4 class="text-themecolor">Edit Business</h4>
        <a href="{{url('business')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
    </div>
     

    <form action="{{route('updateBusiness',[$employer->id])}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="business_name" class="form-label {{ $errors->has('business_name')?' has-error':' has-feedback' }}">Business Name *</label>
                    <input type="text" id="business_name" name="business_name" class="form-control" placeholder="Enter Business Name" value="{{old('business_name',$employer->business->business_name)}}" required>
                    @if ($errors->has('business_name'))
                        <label class="text-danger">{{ $errors->first('business_name') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email {{ $errors->has('email')?' has-error':' has-feedback' }}" class="form-label">Business Email *</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Business Email" value="{{$employer->email}}" required readonly>
                    @if ($errors->has('email'))
                        <label class="text-danger">{{ $errors->first('email') }}</label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="gst" class="form-label {{ $errors->has('gst')?' has-error':' has-feedback' }}">GST No. *</label>
                    <input type="text" id="gst" name="gst" class="form-control gst" placeholder="Enter GST No." value="{{old('gst',$employer->business->gst)}}" required>
                    @if ($errors->has('gst'))
                        <label class="text-danger">{{ $errors->first('gst') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="pan {{ $errors->has('pan')?' has-error':' has-feedback' }}" class="form-label">PAN No. *</label>
                    <input type="text" id="pan" name="pan" class="form-control pan" placeholder="Enter PAN No." value="{{old('pan',$employer->business->pan)}}" required>
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
                    <input type="text" id="owner_first_name" name="owner_first_name" class="form-control" placeholder="Enter Contact Person First Name" value="{{old('owner_first_name',$employer->first_name)}}" required>
                    @if ($errors->has('owner_first_name'))
                        <label class="text-danger">{{ $errors->first('owner_first_name') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="owner_last_name {{ $errors->has('owner_last_name')?' has-error':' has-feedback' }}" class="form-label">Contact Person Last Name * </label>
                    <input type="text" id="owner_last_name" name="owner_last_name" class="form-control" placeholder="Enter Contact Person Last Name" value="{{old('owner_last_name',$employer->last_name)}}" required>
                    @if ($errors->has('owner_last_name'))
                        <label class="text-danger">{{ $errors->first('owner_last_name') }}</label>
                    @endif
                </div>
            </div>
        </div>
       
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="country" class="form-label">Countyry *</label>
                    <select   name="country" id="country"  required>                                                                    
                        @foreach($country as $c)
                        <option value="{{$c->id}}" @isset($employer->profile->country){{$c->id==$employer->profile->country ? "selected" : " " }}@else{{$c->id==old('country') ? "selected" : " " }}  @endif> {{$c->name}} (+{{$c->calling_code}})</option>
                        @endforeach                                            
                    </select>
                </div>
                @if ($errors->has('country'))
                        <label class="text-danger">{{ $errors->first('country') }}</label>
                    @endif
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="mobile_no" class="form-label">Mobile Number *</label>
                    <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Enter Mobile No" value="@isset($employer){{$employer->profile->mobile_no}}@else{{old('mobile_no')}}@endif "  required>
                </div>
                @if ($errors->has('mobile_no'))
                        <label class="text-danger">{{ $errors->first('mobile_no') }}</label>
                    @endif
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="no_of_employee" class="form-label">No Of Employee *</label>
                     
                    <select id="no_of_employee" name="no_of_employee"   required>

                        @foreach($allRange as $range)
                        <option value="{{$range->id}}" @isset($company){{$employer->business->no_of_employee==$range->id ? "selected":""}}@else{{ $range->id==old('no_of_employee')?"selected" :""}}@endif>{{$range->range_start}} - {{$range->range_end}}</option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('no_of_employee'))
                        <label class="text-danger">{{ $errors->first('no_of_employee') }}</label>
                    @endif
            </div>            
           
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="registration_date" class="form-label">Registration Date</label>
                    <input type="date" id="registration_date" name="registration_date" class="form-control" placeholder="Enter Registration Date" value="@isset($employer){{$employer->business->registration_date}}@else{{old('registration_date')}}@endif">
                </div>
                @if ($errors->has('registration_date'))
                        <label class="text-danger">{{ $errors->first('registration_date') }}</label>
                    @endif
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="business_address" class="form-label">Business Address</label>
                    <input type="text" id="business_address" name="business_address" class="form-control" placeholder="Enter Business Address" value="@isset($employer){{$employer->business->business_address}}@else{{old('business_address')}}@endif">
                </div>
                @if ($errors->has('business_address'))
                        <label class="text-danger">{{ $errors->first('business_address') }}</label>
                    @endif
            </div>
        </div>
         
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="registration_doc" class="form-label">Registration Doc (jpg,jpeg)</label>
                    <div class="custom-file">
                        <input type="file" id="registration_doc" name="registration_doc" class="custom-file-input" placeholder="Enter Registration Doc">
                        <label class="custom-file-label" for="registration_doc">Choose file</label>
                    </div>
                    
                     
                </div>
                @if($errors->has('registration_doc'))
                <label class="text-danger">{{ $errors->first('registration_doc') }}</label>
                @endif
                @if($employer->business->registration_doc!='')
                <img class="dp" src="{{ (url('images/'.$employer->business->registration_doc))}}" alt="">
                @endif
                 
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="business_logo" class="form-label">Business Logo (jpg,jpeg)</label>
                    <div class="custom-file">
                        <input type="file" id="business_logo" name="business_logo" class="custom-file-input" placeholder="Enter Registration Doc">
                        <label class="custom-file-label" for="business_logo">Choose file</label>
                    </div>
                    
                </div>
                @if($errors->has('business_logo'))
                <label class="text-danger">{{ $errors->first('business_logo') }}</label>
                @endif
                @if($employer->business->logo!='')
                <img class="dp" src="{{ (url('images/'.$employer->business->logo))}}" alt="">
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="registration_doc" class="form-label">Status</label>
                    <select id="status" name="status" >
                       
                        <option value="1" {{old('status',$employer->business->status)==1?"selected":""}}>Active</option>
                        <option value="2"{{old('status',$employer->business->status)==2?"selected":""}}>Inactive</option>
                    </select>
                </div>
                 
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password" class="form-label">Password (left blank for no update)</label>
                    <input type="text" id="password" name="password" class="form-control" >
                </div>
                @if ($errors->has('password'))
                        <label class="text-danger">{{ $errors->first('password') }}</label>
                    @endif
            </div>
        </div>
        @php
        $data=json_decode($employer->business->contact_persons);
         $cd=count($data);
        @endphp
          
         
        
        <div class="table-responsive">
            <table class="table" id="dynamicAddRemove"> 
                <label for="document">Contact Person Details</label>
                @isset($data)
                    
                @foreach($data as $key=>$value)
                <tr>
                    <td>
                        <label for="document">Name *</label>
                        <input type="text" name="addMoreInputFields[{{$key}}][name]" class="form-control" value="{{$value->name}}" >
                    </td>
                    <td>
                        <label for="document">Designation *</label>
                        <input type="text" name="addMoreInputFields[{{$key}}][desg]" class="form-control"  value="{{$value->desg}}">
                    </td>
                    <td>
                        <label for="document">Contact No *</label>
                        <input type="text" name="addMoreInputFields[{{$key}}][phone]" class="form-control" value="{{$value->phone}}">                                            
                    </td>
                    <td>
                        <label for="document">Email *</label>
                        <input type="text" name="addMoreInputFields[{{$key}}][email]" class="form-control" value="{{$value->email}}">
                    </td>
                    @if($key > 0)
                    <td>
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-outline-danger remove-input-field">Delete</button>
                    </td>
                    @endif
                        
                </tr>
                @endforeach
                @else
                <tr>
                    <td>
                        <label for="document">Name *</label>
                        <input type="text" name="addMoreInputFields[0][name]" class="form-control" >
                    </td>
                    <td>
                        <label for="document">Designation *</label>
                        <input type="text" name="addMoreInputFields[0][desg]" class="form-control"  >
                    </td>
                    <td>
                        <label for="document">Contact No *</label>
                        <input type="text" name="addMoreInputFields[0][phone]" class="form-control" >
                    </td>
                </tr>
                @endif
                    <label>&nbsp;</label>
                    <button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary btn-block">Add more</button>
            </table>
        </div>
        <div class="mt-3 mt-lg-3">
            <button class="btn rounded-pill pxp-section-cta">Save</button>
        </div>
    </form>
</div>
@endsection
@push('js')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script> -->
<script type="text/javascript">
    var i = {{$cd}};
    //$(document).on("click",".dynamic-ar",function(){
   $("#dynamic-ar").click(function () {
        
        $("#dynamicAddRemove").append('<tr><td><input type="text" name="addMoreInputFields['+i+'][name]" class="form-control" required></td><td><input type="text" name="addMoreInputFields['+i+'][desg]" class="form-control" required></td><td><input type="text" name="addMoreInputFields['+i+'][phone]" class="form-control" required>  </td><td><input type="text" name="addMoreInputFields['+i+'][email]" class="form-control" required></td>  <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td></tr>'
            );
            ++i;
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('tr').remove();
    });
</script>
<script>
    $(document).ready(function(){     
        
        $(".pan").change(function () {      
        var inputvalues = $(this).val();      
          var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;    
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
                var gstinformat = new RegExp('^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[1-9A-Z]{1}[1-9A-Z]{1}$');    
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
@endpush
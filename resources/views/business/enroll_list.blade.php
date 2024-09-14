@extends('admin.layouts.app')
@section('content')


@php
//dd ($data);
@endphp

<div class="pxp-dashboard-content-details table-responsive custom_chk">

                <h5>Search By</h5>
                <div class="">
                 
		            <form name="search" method="get" action="{{url('enroll_list')}}">
     			    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label class="control-label">{{ __('Business Name') }}</label>
                            <input type="text" name="business_name"  id="business_name" class="form-control"  value="{{ isset($data['business_name']) ? $data['business_name'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="control-label">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ isset($data['email']) ? $data['email'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="control-label">{{ __('Mobile No') }}</label>
                            <input type="number" name="mobile_no" id="mobile_no"  class="form-control" value="{{ isset($data['mobile_no']) ? $data['mobile_no'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="control-label" >{{ __('Assign To') }}</label>
                            <select name="assign_to" id="assign_to" >
                                <option value=''>{{ __('Select Lead Staff') }}</option>
                                @foreach($leadStaff as $staff)
                                <option value="{{$staff->id}}" @isset($data['assign_to']){{  $data['assign_to'] == $staff->id ? "selected" : "" }}@endif>{{$staff->first_name}} {{$staff->first_name}}/{{$staff->email}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="control-label" >{{ __('Status') }}</label>
                            <select name="status" id="status" >
                                <option value=''>{{ __('Select') }}</option>
                                <option value="1" @isset($data['status']){{  $data['status'] == 1 ? "selected" : "" }}@endif>{{ __('Pending') }}</option>
                                <option value="2"  @isset($data['status']){{  $data['status'] == 2 ? "selected" : "" }}@endif>{{ __('Verified') }}</option>
                                <option value="3"  @isset($data['status']){{  $data['status'] == 3 ? "selected" : "" }}@endif>{{ __('Created') }}</option>
                                <option value="4"  @isset($data['status']){{  $data['status'] == 4 ? "selected" : "" }}@endif>{{ __('Rejected') }}</option>
                                <option value="5"  @isset($data['status']){{  $data['status'] == 4 ? "selected" : "" }}@endif>{{ __('Assigned') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="button" id="reset" class="btn rounded-pill btn-block pxp-section-cta" >{{ __('Reset') }}</button>
                        </div>
                    </div>
                    
                        
		            </form>
                </div>

<hr>

                <h5>Enroll Company List</h5>
                 
                    <button type="button" class="btn expBtn" >Export</button>
                                                           
                <table class="table footable align-middle">
                    <thead>
                        <tr>
                            <th>Business Name</th>
                            <th>Owner Name</th>
                            <th data-breakpoints="xs">Email</th>
                            <th data-breakpoints="xs sm">Phone No</th>
                            <th data-breakpoints="xs sm">Country</th>
                            <th data-breakpoints="xs sm">No Of Employee</th>
                            <th data-breakpoints="xs sm md lg">Date</th>
                            <th data-breakpoints="xs sm md lg">GST No.</th>
                            <th data-breakpoints="xs sm md lg">PAN No.</th>
                            <th data-breakpoints="xs sm md lg">Status</th>
                            <th data-breakpoints="xs sm md lg">Verifier</th>
                            <th data-breakpoints="xs sm md lg">Creator</th>
                            <th data-breakpoints="xs sm md lg">Assign To</th>
                            <th data-breakpoints="xs sm md lg">Follow Up</th>
                            <th data-breakpoints="xs sm md lg">Referral Code</th>
                            <th data-breakpoints="xs sm md lg">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrolls as $business)
                        <tr>
                       
                            <td>{{strtoupper($business->business_name)}}</td>
                            <td>{{strtoupper($business->owner_first_name.' '.$business->owner_last_name)}}</td>
                            <td>{{strtoupper($business->email)}}</td>
                            <td>{{$business->mobile_no}}</td>
                            <td>{{strtoupper($business->countryDetails->name)}}</td>
                            <td>{{$business->noOfEmp->range_start}} - {{$business->noOfEmp->range_end}}</td>
                            <td>{{date('d-m-Y', strtotime($business->created_at))}}</td>
                            <td>{{$business->gst}}</td>
                            <td>{{$business->pan}}</td>
                            <td>
                                @if($business->status==1)
                                    Pending                                
                                @elseif($business->status==2)
                                    Verified
                                @elseif($business->status==3)
                                    Created
                                @elseif($business->status==4)
                                    Rejected
                                @elseif($business->status==5)
                                    Assigned to Staff                                    
                                @endif           
                            </td>
                            <td>@if($business->verifier_id!=Null){{strtoupper($business->Verifier->first_name)}}@endif</td>
                            <td>@if($business->creator_id!=Null){{strtoupper($business->Creator->first_name)}}@endif</td>
                            <td>@if($business->lead_staff_id!=Null){{strtoupper($business->Agent->first_name)}} {{strtoupper($business->Agent->last_name)}} / {{strtoupper($business->Agent->email)}}@endif</td>
                            
                            <td>@php
                                 $fp_count=DB::table('lead_follow_up')->where('lead_id',$business->id)->count();
                                 if($fp_count>0)
                                 {
                                    echo 'Yes';
                                 }
                                 
                                 else
                                 {
                                    echo 'No';
                                 }
                                @endphp
                            </td>
                            <td>{{$business->referral_code}}</td>
                            <td>
                            @if($business->status==1 || $business->status==5)
                            <button type="button" class="btn btn-sm btn-rounded btn-success" id="btn1" onClick="approve({{$business->id}})">Verify</button>
                            <button type="button" class="btn btn-sm btn-rounded btn-danger" id="btn2" onClick="reject({{$business->id}})">Reject</button>
                            @elseif($business->status==2)
                            <form class="d-inline" name="search" method="get" action="{{route('business.create')}}">
                            <input type="hidden" name="enroll_id" value="{{$business->id}}">
                            <button type="submit" class="btn btn-sm btn-rounded btn-success" name="approve" value="true">{{ __('Create') }}</button>
                            </form>
                            @elseif($business->status==3)
                            @if(Auth::user()->account_type=='superadmin' || Auth::user()->account_type=='lead head')
                            <a href="{{route('business.edit',[$business->user_id])}} " class="btn btn-sm btn-rounded btn-warning">View Business</a>
                            @endif
                            @endif
                            @if(($business->status!=3) && ($business->status!=4))
                            <a href="{{url('follow_up')}}/{{$business->id}}" class="btn btn-sm btn-rounded btn-warning" target="_blank">Follow Up</a>
                            @endif
                            {{--<button type="button" class="btn btn-sm btn-rounded btn-danger" id="btn1" onClick="reject({{$business->id}})">Reject</button>--}}
                          
                            </td>
                        </tr>
                        @endforeach                        
                    </tbody>
                </table>
                {{$enrolls->links()}}
            </div>
@endsection
@push('js')
<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/enroll_list?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });
</script>
<script>

function approve(enroll){
//     const { value: accept } =  Swal.fire({
//   title: 'Are you sure this Enrollment has been Verified ?',
//   input: 'checkbox',
//   inputValue: 1,
//   inputPlaceholder:
//     'I agree with the terms and conditions',
//   confirmButtonText:
//     'Continue <i class="fa fa-arrow-right"></i>',
//   inputValidator: (result) => {
//     return !result && 'You need to agree with T&C'
//   }
// })

// if (accept) {
// //     Swal.fire('You agreed with T&C :)')
    
// //  // Swal.fire('Enrollment has been mark as Verified.')

// }


Swal.fire({
    title: 'Are you sure this Enrollment has been Verified ?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    
    inputValue: 1,
    confirmButtonColor: '#02B654',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, confirm it!'
}).then((result) => {
    
if (result.isConfirmed) {
   // alert(1);
     // $('#btn5').attr("disabled","disabled");
    //console.log(service_id);
    var enroll_id = enroll; 
    var response = 1; 
console.log(enroll_id);
    var token='{{ csrf_token() }}';
    //console.log(staff_id);
    $.ajax({
        type:'POST',
        url:"{{url('company_enroll_response')}}",
        data:'_token='+token+'&enroll_id='+enroll_id+'&response='+response,
        
        success:function(msg){
            
            if(msg == 1){
                $('#btn1').hide();
                $('#btn2').hide();
                
                $('.statusMsg').html('<span style="color:green;">Enrollment Verified Successfully.</p>');
                    setTimeout(function() 
                    {
                        location.reload();  //Refresh page
                    }, 1000);
            }else{
                $('.statusMsg').html('<span style="color:red;">Somthing was wrong</span>');
            }
            
        }
    });
    Swal.fire(
    'Confirmed!',
    'Enrollment has been mark as Verified.',
    'success'
    )
    }
})

}
function reject(enroll){

Swal.fire({
    // title: 'Are you sure this Enrollment has been Rejected ?',
    // text: "You won't be able to revert this!",
    // icon: 'warning',
    // showCancelButton: true,
    // confirmButtonColor: '#02B654',
    // cancelButtonColor: '#d33',
    // confirmButtonText: 'Yes, confirm it!'
    title: 'Enter Reject Reason',
  input: 'text',
  inputLabel: 'Reject Reason',
  inputValue: '',
  showCancelButton: true,
}).then((result) => {
if (result.isConfirmed) {
     // $('#btn5').attr("disabled","disabled");
    //console.log(service_id);
    var reason=result.value;
    var enroll_id = enroll; 
    var response = 2; 
console.log(enroll_id);
    var token='{{ csrf_token() }}';
    //console.log(staff_id);
    $.ajax({
        type:'POST',
        url:"{{url('company_enroll_response')}}",
        data:'_token='+token+'&enroll_id='+enroll_id+'&response='+response+'&reason='+reason,
        
        success:function(msg){
            
            if(msg == 2){
                $('#btn1').hide();
                
                $('.statusMsg').html('<span style="color:green;">Enrollment Rejected Successfully.</p>');
                    setTimeout(function() 
                    {
                        location.reload();  //Refresh page
                    }, 1000);
            }else{
                $('.statusMsg').html('<span style="color:red;">Somthing was wrong</span>');
            }
            
        }
    });
    Swal.fire(
    'Confirmed!',
    'Enrollment has been mark as Rejected.',
    'success'
    )
    }
})
/*
const { value: ipAddress } =  Swal.fire({
  title: 'Enter your IP address',
  input: 'text',
  inputLabel: 'Your IP address',
  inputValue: inputValue,
  showCancelButton: true,
  inputValidator: (value) => {
    if (!value) {
      return 'You need to write something!'
    }
  }
})

if (ipAddress) {
  Swal.fire(`Your IP address is ${ipAddress}`)
}
*/
}

</script>
@endpush
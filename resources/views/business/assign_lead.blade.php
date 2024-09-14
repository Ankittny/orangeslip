@extends('admin.layouts.app')
@section('content')


@php
//dd ($enrolls);
@endphp
@if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
<div class="pxp-dashboard-content-details table-responsive">

                <h5>Assign Enroll Company List</h5>
                @if((count($enrolls))>0)
                <label class="form-label">Select All</label>
                <input type="checkbox"  name="slAll" id="slAll"  >
                @endif
                <p class="statusMsg"></p> 
                <form  name="assign" enctype="multipart/form-data" method="post" action="{{url('assign_enroll_lead')}}">
                    @csrf
                         
                <table class="table footable align-middle">
                    <thead>
                        
                        <tr>
                            <th>Business Name</th>
                            <th>Owner Name</th>
                            <th data-breakpoints="xs">Email</th>
                            <th data-breakpoints="xs sm">Phone No</th>
                            <th data-breakpoints="xs sm">Country</th>
                            <th data-breakpoints="xs sm">No Of Employee</th>
                            <th data-breakpoints="xs sm">Status</th>
                            <th data-breakpoints="xs sm">Action</th>
                            <th data-breakpoints="xs sm md lg">Date</th>
                            <th data-breakpoints="xs sm md lg">GST</th>
                            <th data-breakpoints="xs sm md lg">PAN</th>    
                            <th data-breakpoints="xs sm md lg">Agent Name</th>    
                           

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
                                <td>
                              
                                    @if($business->is_assign==1)
                                        Assigned                                
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
                                <td>
                              
                                    @if($business->status==1 || $business->status==5)
                                       
                                    <input type="checkbox" class="slall form-controll" name="lead[]" value="{{$business->id}}">
                                    <label>Select</label>
                                    
                                    @endif            
                                </td>
                                

                                <td>{{date('d-m-Y', strtotime($business->created_at))}}</td>
                                
                                <td>{{$business->gst}}</td>
                                <td>{{$business->pan}}</td>
                                <td>
                              
                              @if($business->is_assign==1)
                                  {{$business->Agent->first_name.' '.$business->Agent->last_name}}                                
                               
                              
                              @endif            
                          </td>
                                
                                
                            </tr>
                            @endforeach
                                             
                    </tbody>
                </table>
                @if((count($enrolls))>0)
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label" >{{ __('select agent') }}</label>
                        <select name="agent" id="agent" >
                            <option value="">Select Lead Staff</option>
                            @foreach($allAgent as $agent)
                            <option value="{{$agent->id}}">{{$agent->first_name.' '.$agent->last_name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('agent'))
                            <label class="text-danger">{{ $errors->first('agent') }}</label>
                        @endif
                    </div>  
                    <div class="col-md-6">
                         <label >&nbsp;</label>
                        <button class="btn rounded-pill pxp-section-cta btn-block">Assign</button>
                    </div>
                </div>
                @endif
                        </form>
                 
            </div>
@endsection
@push('js')

<script>
$('#slAll').click(function(){
    if($(this).is(':checked')){
        $('.slall').prop('checked', true);
    } else {
        $('.slall').prop('checked', false);
    }
});
</script>
@endpush
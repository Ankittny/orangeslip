@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif

<div class="pxp-dashboard-content-details">
        
        <div class="d-flex justify-content-between">
            <h4 class="text-themecolor">Joining Details</h4>
            <a href="{{url('candidate_list')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
        </div>
                
                
                <form method="post" action="{{url('joiningdetails')}}/{{base64_encode($offer_letter->id)}}" enctype="multipart/form-data">
                    @csrf

                    
                    <div class="row">
                       {{-- <div class="col-md-3">
                            <div class="mb-3">
                                 <label for="inputName">Is Selected </label>
                                <Select  class="form-control" id="is_selected" name="is_selected" required>
                                    <option value="" selected>Select </option>
                                    <option value="1" {{$candidate->is_selected==1?"selected":""}} >Yes</option>
                                    <option value="2" {{$candidate->is_selected==2?"selected":""}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="inputName">Joining Confirmed </label>
                                <Select  class="form-control" id="is_confirmed" name="is_confirmed" required>
                                    <option value="" selected>Select </option>
                                    <option value="1" {{$candidate->joining_confirmed==1?"selected":""}}>Yes</option>
                                    <option value="2" {{$candidate->joining_confirmed==2?"selected":""}}>No</option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="inputName">Joining Date </label>
                                <input type="date"  class="form-control" id="joining_date" name="joining_date" value="{{$candidate->joining_date}}">   
                            </div>
                        </div>--}}
                        
                         
                        <div class="row">
                        <p class="pxp-text-light"><u>Reschedule</u></p>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label >Previous Joining Date : </label>
                                <label >{{$reschedule->old_joining_date}} </label>
                                 
                            </div>
                            <div class="mb-3">
                                <label >Previous Joining Time : </label>
                                <label >{{$reschedule->old_joining_time}} </label>
                                 
                            </div>
                            
                            <div class="mb-3">
                                <label >New Joining Date : </label>
                                <label >{{$reschedule->new_joining_date}} </label>
                                 
                            </div>
                            <div class="mb-3">
                                <label >New Joining Time : </label>
                                <label >{{$reschedule->new_joining_time}} </label>
                                 
                            </div>
                            <div class="mb-3">
                                <label >Reason : </label>
                                <label >
                                    @php
                                    $res=DB::table('reschedule_reasons')->where('id',$reschedule->reason)->pluck('title')->first();
                                    @endphp
                                    {{$res}} 
                                </label>
                                 
                            </div>
                            <div class="mb-3">
                                <label >Response </label>
                                <select name="hr_response" id="hr_response" required>
                                    <option value="" selected> Select</option>
                                    <option value="1" > Approve</option>
                                    <option value="2" > Reject</option>
                                </select>
                                @if($errors->has('hr_response'))
                                <span class="alert-danger">{{ $errors->first('hr_response') }}</span>
                                @endif
                            </div>
                            <div class="mb-3" id="hr_rem">
                                <label >Remark</label>
                                <input name="hr_remark" type="text" class="form-control" >
                                   
                                @if($errors->has('hr_response'))
                                <span class="alert-danger">{{ $errors->first('hr_response') }}</span>
                                @endif
                            </div>
                       </div>
                        
                     
                        </div>
                    </div>
                   
                    <div class="mt-3 mt-lg-3">
                        <button class="btn rounded-pill pxp-section-cta">Submit</button>
                    </div>
                </form>
            </div>

@endsection
@push('js')
<script>

$(function() {
    $('#hr_rem').hide(); 
    $('#hr_response').change(function(){
        if($('#hr_response').val() == 2) {
            $('#hr_rem').show(); 
        } else {
            $('#hr_rem').hide(); 
        } 
    });
});
</script>
@endpush
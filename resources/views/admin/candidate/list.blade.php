
@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
            <div class="candidate_List_box">
                <h5>Search By</h5>
                <div class="candidate_List_box_inner">
                 
		            <form name="search" method="get" id="searchForm" action="{{url('candidate_list')}}">
     			    <div class="row">

                        <div class="col-md-3 mb-4">
                            <label class="control-label">{{ __('Name') }}</label>
                            <input type="text" name="cname" id="cname" class="form-control" value="{{ isset($searchData['cname']) ? $searchData['cname'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="control-label">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ isset($searchData['email']) ? $searchData['email'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="control-label">{{ __('Phone') }}</label>
                            <input type="number" name="phone" id="phone"  class="form-control" value="{{ isset($searchData['phone']) ? $searchData['phone'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="control-label" >{{ __('Assign To') }}</label>
                            <select name="assign_to"  id="assign_to" >
                                <option value="">Select HR</option>
                                @isset($allHr)
                                @foreach($allHr as $hr)
                                    <option value="{{$hr->id}}" @isset($searchData['assign_to']){{  $searchData['assign_to']== $hr->id ? "selected" : "" }}@endif>{{$hr->first_name}} {{$hr->last_name}}/{{$hr->email}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">                     

                        <div class="col-md-3 mb-4">
                            <label class="control-label">{{ __('State') }}</label>
                            <select  id="state" name="state"  onchange="getCity(this.value)" >
                                    <option value='' >Select state</option>
                                    @foreach($states as $s)
                                    <option value='{{$s->state_id}}'>{{$s->state_title}}</option>
                                    @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-4">
                            <label class="control-label">{{ __('City') }}</label>
                            <select  id="city" name="city"  >
                                                                       
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-4">
                            <label class="control-label" >{{ __('Status') }}</label>
                            <select name="status" id="status" >
                                <option value=''>{{ __('Select') }}</option>
                                <option value="1" >{{ __('Selected') }}</option>
                                <option value="2"  >{{ __('Pending') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="control-label" >&nbsp;</label>
                            <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" >&nbsp;</label>
                            <button type="button" class="btn rounded-pill btn-block pxp-section-cta" id="reset" value="true">{{ __('Clear') }}</button>
                             
                        </div>
                        
			        </div>
                        
		            </form>
                </div>
            </div>
           
    <div class="pxp-dashboard-content-details custom_chk">
        <h1>Candidate List</h1>

        <div class="d-flex justify-content-between mb-3">
            <div>
                <button type="button" class="btn expBtn sm_btn rounded-pill pxp-section-cta" >Export</button>
                @if(Auth::user()->account_type=='business')
                <button type="button" data-bs-toggle="modal"  data-bs-target="#reAllotModal"  class="btn noasnBtn sm_btn rounded-pill pxp-section-cta" >Assign To</button>
                @endif
            </div>
            <div>
                @if((Auth::user()->account_type=='business') )
                <input type="checkbox" name="slAll" id="slAll">
                <label class="form-label">Select All</label>
                @endif             
            </div>
        </div>

        <!-- <span data-href="/export-csv"  id="export" class="btn btn-success btn-sm" onclick ="exportTasks (event.target);">Export CSV</span> -->
        <p class="statusMsg"></p>
        @foreach($candidates as $candidate)
            <div class="candidate_List_box">
             
                <div class="candidate_List_box_inner">
                    <div class="row">
                        <div class="col-md-2 col-sm-3 col-lg-2 text-center">
                            <img class="candidate_List_img" src="{{ ($candidate->photo!='')?(url('images/'.$candidate->photo)):(url('/new/images/noimage.png')) }}" alt="">
                            {{$candidate->candidate_code}}
                        </div>
                        <div class="col-md-10 col-sm-9 col-lg-10">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="line_holder">
                                        <p class="d-item_one">Name</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($candidate->name)}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Email</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($candidate->email)}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Phone No</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$candidate->phone}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Gender</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($candidate->gender)}}</p>
                                    </div>
                                    
                                    @if($candidate->rating!=NULL)
                                    <div class="line_holder">
                                        <p class="d-item_one">Rating</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$candidate->rating}}</p>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="line_holder">
                                        <p class="d-item_one">Date</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$candidate->created_at}}</p>
                                    </div>

                                    <div class="line_holder">
                                        <p class="d-item_one">Added By</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">
                                            @if($candidate->hr_id==$candidate->added_by)
                                            {{strtoupper($candidate->hrDetails->first_name)}}(HR)
                                            @elseif($candidate->business_id==$candidate->added_by)
                                            {{strtoupper($candidate->businessDetails->business->business_name)}}(Business)
                                            @endif
                                        </p>
                                    </div>

                                    <div class="line_holder">
                                        <p class="d-item_one">Assign To</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">
                                            
                                            {{$candidate->assign_to?strtoupper($candidate->assignTo->first_name) : strtoupper($candidate->hrDetails->first_name)}}
                                             
                                        </p>
                                    </div>


                                    {{--<div class="line_holder">
                                        <p class="d-item_one">HR</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">@if($candidate->hr_id!=Null){{strtoupper($candidate->hrDetails->first_name)}}@endif</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Business</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">@if($candidate->business_id!=Null){{strtoupper($candidate->businessDetails->business->business_name)}}@endif</p>
                                    </div>
                                    --}}
                                    <div class="line_holder">
                                        <p class="d-item_one">Status</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">
                                            @if($candidate->status==0)
                                            Pending
                                            @elseif($candidate->status==1)
                                            Selected
                                            @elseif($candidate->status==2)
                                            Offer Letter Generated
                                            @elseif($candidate->status==31)
                                            Offer Accepted(Joining Confirmed)
                                            @elseif($candidate->status==32)
                                            Offer Rejected
                                            @elseif($candidate->status==4)
                                            Request For Reschedule
                                            @endif
                                        </p>
                                    </div>
                                    @if($candidate->rating!=NULL)
                                    <div class="line_holder">
                                        <p class="d-item_one">Review</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$candidate->review}}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if(Auth::user()->account_type=='superadmin')
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="text-center mt-3 text-primary">
                                    <p>{{strtoupper($candidate->businessDetails->business->business_name)}}</p>
                                </div>
                            </div>
                            @endif
                            
                           
                        </div>
                        
                    </div>
                </div>
                <div class="mt-3 candidate_List_btn">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            @if(Auth::user()->chkUserAccess(2))
                                <a href="{{url('edit_candidate')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Edit Candidate Details</a>
                            @endif

                            @if((Auth::user()->account_type=='hr') || (Auth::user()->account_type=='business'))
                            @if($candidate->status==0)
                            <button type="button" class="btn sm_btn rounded-pill btn-warning " id="btn1" onClick="isSelected({{$candidate->id}})">Select</button>
                                            
                            @elseif($candidate->status==1)
                            <button type="button" class="btn sm_btn rounded-pill btn-warning " id="btn1" onClick="isSelected({{$candidate->id}})">Deselect</button>
                            {{-- <a href="{{url('generateofferletter')}}/{{base64_encode($candidate->id)}}" class="btn rounded-pill sm_btn  pxp-section-cta">Generate Offer Letter old</a>--}}
                            <a href="{{url('create_offer_letter')}}?id={{base64_encode($candidate->id)}}" class="btn rounded-pill sm_btn  pxp-section-cta">Generate Offer Letter</a>
                            @elseif($candidate->status==4)
                            <a href="{{url('joiningdetails')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Approve Reschedule</a>
                            
                            @elseif(($candidate->status==31 || $candidate->status==32) && ($candidate->physical_joinig_point==null))
                            <button data-bs-toggle="modal" class="ratingbtn btn sm_btn rounded-pill pxp-section-cta" data-bs-target="#joiningModal"  data-id="{{$candidate->id}}">Physical Joining</button>

                            {{--<a href="{{url('reviewdetails')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Edit Review Details</a>--}}
                            @endif
                          
                            {{--@if($candidate->rating==NULL)
                            <button data-bs-toggle="modal" class="ratingbtn btn sm_btn rounded-pill pxp-section-cta" data-bs-target="#ratingModal"  data-id="{{$candidate->id}}">Rate & Review</button>
                            @endif--}}
                        
                            @endif
                        
                            @if($candidate->status > 1 )
                            <a href="{{url('offer_letter_list')}}?email={{$candidate->email}}" class="btn sm_btn rounded-pill btn-warning" >Offer Letters</a>
                            @endif
                            <a href="{{ route('candidateview',[base64_encode($candidate->id)]) }}" class="btn sm_btn rounded-pill btn-warning" target="_blank">Resume</a>
                            {{-- <a href="{{ route('disputeview',[base64_encode($candidate->id)]) }}" class="btn sm_btn rounded-pill pxp-section-cta1 btn-danger">Dispute</a>--}}
                            <a href="{{ route('candidateFollowUpList',[base64_encode($candidate->id)]) }}" class="btn sm_btn rounded-pill pxp-section-cta1 btn-primary">Follow Up</a>
                            <a href="{{ route('empily',[base64_encode($candidate->id)]) }}" target="_blank" class="btn sm_btn rounded-pill pxp-section-cta1 btn-success">EMPILY Score</a>
                            
                        
                        </div>
                        <div>
                            @if(Auth::user()->account_type=='business')
                            
                            <input type="checkbox"  name="sl"  value="{{$candidate->id}}">
                            <label class="form-label">Assign</label>
                            {{-- <button data-bs-toggle="modal" class="reAllotbtn btn sm_btn rounded-pill pxp-section-cta" data-bs-target="#reAllotModal"  data-id="{{$candidate->id}}">Assign To</button>--}}
                            @endif
                        </div>
                    </div>
                   
                </div>
               
            </div>

        @endforeach

        {{$candidates->links()}}
       
    </div>
<!--Physical Joining Details-->
<div class="modal fade" id="joiningModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetForm();"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Physical Joining</h4>
                
                <form method="post" enctype="multipart/form-data" id="modelForm">
                    @csrf
                    <input type="hidden" id="candidate_id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Rating *</label>
                                <select name="rate"  id="rate" >
                                    @foreach($physical_joining_attributes as $attr)
                                    <option value="{{$attr->max_point}}">{{$attr->name}} ({{$attr->max_point}} Point)</option>
                                   
                                    @endforeach
                                </select>
                                <p class="error_rate qerr"></p>
                            </div>
                        </div>                        
                        <div class="col-md-2">
                        <label class="form-label">&nbsp; </label>
                            <button type="button" id="btn1" class="btn rounded-pill pxp-section-cta btn-block" onClick="physicalJoining();">Save</button>
                            <p class="statusMsg"></p>
                        </div>
                        
                    </div>
                     
                    
                </form>
            </div>
            </div>
        </div>
</div>
<!--Rating Details-->
{{--<div class="modal fade" id="ratingModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetForm();"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Rating and Review</h4>
                
                <form method="post" enctype="multipart/form-data" id="modelForm">
                    @csrf
                    <input type="hidden" id="candidate_id" value="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Rating *</label>
                                <select name="rate"  id="rate" >
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <p class="error_rate qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Review *</label>
                                <input type="text" name="review" id="review" class="form-control" placeholder="Enter Review" >
                                <p class="error_review qerr"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <label class="form-label">&nbsp; </label>
                            <button type="button" id="btn1" class="btn rounded-pill pxp-section-cta btn-block" onClick="rating();">Save</button>
                            <p class="statusMsg"></p>
                        </div>
                        
                    </div>
                     
                    
                </form>
            </div>
            </div>
        </div>
</div>--}}
    <!--reAllotModal Details-->
    <div class="modal fade" id="reAllotModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetForm();"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Re Allotment</h4>
                
                <form method="post" enctype="multipart/form-data" id="modelForm">
                    @csrf
                    <input type="hidden" id="candidate_id1" value="">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">HR *</label>
                                <select name="hr1"  id="hr1" >
                                <option value="">Select HR</option>
                                @isset($allHr)
                                @foreach($allHr as $hr)
                                    <option value="{{$hr->id}}">{{$hr->first_name}} {{$hr->last_name}}/{{$hr->email}}</option>
                                @endforeach
                                @endif
                                </select>
                                <p class="error_hr_id qerr"></p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                        <label class="form-label">&nbsp; </label>
                            <button type="button" id="btn2" class="btn rounded-pill pxp-section-cta btn-block" onClick="reAllot();">Save</button>
                            
                        </div>
                        <p class="statusMsg"></p>
                        
                    </div>
                     
                    
                </form>
            </div>
            </div>
        </div>
</div>
 
@endsection
@push('js')

<script>

$('#slAll').click(function(){
    if($(this).is(':checked')){
        $('input[name="sl"]').prop('checked', true);
    } else {
        $('input[name="sl"]').prop('checked', false);
    }
});




    $(document).on("click",".reAllotbtn",function(){
    
    var can_id = $(this).data('id');//console.log(service_id);
    $('#candidate_id1').val(can_id);
    
    $('.qerr').html('');
    $('.statusMsg').html('');
    
   
        
});

function reAllot()
{
    $('.qerr').html('');
    $('.statusMsg').html('');
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    // var candidate_id=$('#candidate_id1').val();
    // var candidate_id=cid;
    var hr_id=$('#hr1').val();
    var candidate_id=[];
            $("input[name='sl']:checked").each(function(){
                candidate_id.push(this.value);
        });
        console.log(candidate_id);

    $.ajax({
        type:"POST",
        url:"{{url('/reallot_candidate')}}",
        // data:'_token='+token+'&candidate_id='+candidate_id+'&hr_id='+hr_id,
        data: {_token:token,candidate_id:candidate_id,hr_id:hr_id},
                dataType: 'JSON',
        success:function(allot_response)
        {
            if(allot_response==1)
            {
                $('#btn1').hide();
                $('.statusMsg').html('<span style="color:green;">Candidate Re-Allot Successfully!.</span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 1000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</span>');
            }
        },
        error: function (allot_reject) {
           
                if( allot_reject.status === 422 ) {
                    //console.log(reject);
                    var allot_resp = $.parseJSON(allot_reject.responseText);
                    $.each(allot_resp.errors, function (allot_key, allot_val) {
                        // console.log(allot_resp,allot_val);
                        $('.error_'+allot_key).html(allot_val[0]).css("color","red","display","show");
                        $( allot_key ).text(allot_val[0]);
                    });
                }
            }
            
    });    
}
    function physicalJoining()
{
    $('.qerr').html('');
    $('.statusMsg').html('');
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var candidate_id=$('#candidate_id').val();
    var rate=$('#rate').val();
    
    

    $.ajax({
        type:"POST",
        url:"{{url('/physical_joining_point_store')}}",
        data:'_token='+token+'&candidate_id='+candidate_id+'&rating='+rate,
        success:function(education_response)
        {
            if(education_response==1)
            {
                $('#btn1').hide();
                $('.statusMsg').html('<span style="color:green;">Review Added Successfully!.</span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 1000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</span>');
            }
        },
        error: function (education_reject) {
           
                if( education_reject.status === 422 ) {
                    //console.log(reject);
                    var edu_resp = $.parseJSON(education_reject.responseText);
                    $.each(edu_resp.errors, function (edu_key, edu_val) {
                        console.log(edu_key,edu_val);
                        $('.error_'+edu_key).html(edu_val[0]).css("color","red","display","show");
                        $( edu_key ).text(edu_val[0]);
                    });
                }
            }
            
    });    
}
/*
function rating()
{
    $('.qerr').html('');
    $('.statusMsg').html('');
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var candidate_id=$('#candidate_id').val();
    var rate=$('#rate').val();
    var review=$('#review').val();
    

    $.ajax({
        type:"POST",
        url:"{{url('/rating_review_store')}}",
        data:'_token='+token+'&candidate_id='+candidate_id+'&rating='+rate+'&review='+review,
        success:function(education_response)
        {
            if(education_response==1)
            {
                $('#btn1').hide();
                $('.statusMsg').html('<span style="color:green;">Review Added Successfully!.</span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 1000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</span>');
            }
        },
        error: function (education_reject) {
           
                if( education_reject.status === 422 ) {
                    //console.log(reject);
                    var edu_resp = $.parseJSON(education_reject.responseText);
                    $.each(edu_resp.errors, function (edu_key, edu_val) {
                        console.log(edu_key,edu_val);
                        $('.error_'+edu_key).html(edu_val[0]).css("color","red","display","show");
                        $( edu_key ).text(edu_val[0]);
                    });
                }
            }
            
    });    
}
*/
</script>

<script>
    $(document).on("click",".ratingbtn",function(){
    
    var can_id = $(this).data('id');//console.log(service_id);
    $('#candidate_id').val(can_id);
    
    $('.qerr').html('');
    $('.statusMsg').html('');
});

function isSelected(candidate){

    Swal.fire({
        title: 'Are you sure this candidate status has been changed ?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#02B654',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, confirm it!'
    }).then((result) => {
    if (result.isConfirmed) {
         // $('#btn5').attr("disabled","disabled");
        //console.log(service_id);
        var candidate_id = candidate; 
    
        var token='{{ csrf_token() }}';
        //console.log(staff_id);
        $.ajax({
            type:'POST',
            url:"{{url('is_selected')}}",
            data:'_token='+token+'&candidate_id='+candidate_id,
            
            success:function(msg){
                
                if(msg == 1){
                    $('#btn1').hide();
                    
                    $('.statusMsg').html('<span style="color:green;">Candidate Status Changed Successfully.</p>');
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
        'candidate status changed.',
        'success'
        )
        }
    })

//     if(confirm("Are you sure you want to delete this?")){
//          // $('#btn5').attr("disabled","disabled");
//     //console.log(service_id);
//     var candidate_id = candidate; 
   
//    var token='{{ csrf_token() }}';
// //console.log(staff_id);
//        $.ajax({
//            type:'POST',
//            url:"{{url('is_selected')}}",
//            data:'_token='+token+'&candidate_id='+candidate_id,
           
//            success:function(msg){
               
//                if(msg == 1){
//                    $('#btn1').hide();
                   
//                    $('.statusMsg').html('<span style="color:green;">Candidate Selected Successfully.</p>');
//                        setTimeout(function() 
//                        {
//                            location.reload();  //Refresh page
//                        }, 5000);
//                }else{
//                    $('.statusMsg').html('<span style="color:red;">Somthing was wrong</span>');
//                }
               
//            }
//        });
//     }
//     else{
//         return false;
//     }
   
     
}

function getCity(state_id)
{
        var state_id=state_id;
        console.log(state_id);
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
                    selectize.addOption({value: city.id, text: city.name});                  
                });
                selectize.refreshOptions(true);              
                console.log(response);
            }
        });
    }

</script>
<script>
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      
       

      window.location.href = _url;
   }
</script>
<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/candidate_list?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });


    $(document).ready(function(){
		$('.asnBtn').click(function() {
             
            var cid=[];
            $("input[name='sl']:checked").each(function(){
                cid.push(this.value);
        });
         
            console.log(cid);

        });
    });
</script>

@endpush
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
      
            <a href="{{url('candidate_list')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
        </div>
            <div class="candidate_List_box">

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
                                            <p class="d-item_two">{{$candidate->name}}</p>
                                        </div>
                                        <div class="line_holder">
                                            <p class="d-item_one">Email</p>
                                            <div class="dashboard_item_line"></div>
                                            <p class="d-item_two">{{$candidate->email}}</p>
                                        </div>
                                        <div class="line_holder">
                                            <p class="d-item_one">Phone No</p>
                                            <div class="dashboard_item_line"></div>
                                            <p class="d-item_two">{{$candidate->phone}}</p>
                                        </div>
                                        <div class="line_holder">
                                            <p class="d-item_one">Gender</p>
                                            <div class="dashboard_item_line"></div>
                                            <p class="d-item_two">{{$candidate->gender}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                        <div class="line_holder">
                                            <p class="d-item_one">Date</p>
                                            <div class="dashboard_item_line"></div>
                                            <p class="d-item_two">{{$candidate->created_at}}</p>
                                        </div>
                                        <div class="line_holder">
                                            <p class="d-item_one">HR</p>
                                            <div class="dashboard_item_line"></div>
                                            <p class="d-item_two">@if($candidate->hr_id!=Null){{$candidate->hrDetails->first_name}}@endif</p>
                                        </div>
                                        <div class="line_holder">
                                            <p class="d-item_one">Business</p>
                                            <div class="dashboard_item_line"></div>
                                            <p class="d-item_two">@if($candidate->business_id!=Null){{$candidate->businessDetails->business->business_name}}@endif</p>
                                        </div>
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
                                    </div>
                                </div>
                            
                                
                            
                            </div>
                        </div>
                    </div>
                    
                   
                  
                    <div class="mt-3 candidate_List_btn">
                        @if(Auth::user()->chkUserAccess(2))
                            <a href="{{url('basicdetails')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Edit Personal Details</a>
                            <a href="{{url('educationdetails')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Edit Education Details</a>
                            <a href="{{url('professionaldetails')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Edit Professional Details</a>
                            <a href="{{url('othersdetails')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Edit Other's Details</a>
                            <a href="{{url('upload_document')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Upload Document</a>
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
                        
                    {{-- @elseif($candidate->status==31 || $candidate->status==32)
                        <a href="{{url('reviewdetails')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Edit Review Details</a>--}}
                        @endif
                       <a href="{{url('professional-feedback')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Professional Feedback</a>
                        @endif
                    
                        @if($candidate->status > 1 )
                        <a href="{{url('offer_letter_list')}}?email={{$candidate->email}}" class="btn sm_btn rounded-pill btn-warning" >Offer Letters</a>
                        @endif
                        <a href="{{ route('candidateview',[base64_encode($candidate->id)]) }}" class="btn sm_btn rounded-pill btn-warning" target="_blank">Resume</a>
                    {{-- <a href="{{ route('disputeview',[base64_encode($candidate->id)]) }}" class="btn sm_btn rounded-pill pxp-section-cta1 btn-danger">Dispute</a>--}}
                        <a href="{{ route('candidateFollowUpList',[base64_encode($candidate->id)]) }}" class="btn sm_btn rounded-pill pxp-section-cta1 btn-primary">Follow Up</a>
                        
                    </div>
                
                </div>

            </div>
        </div>
           
     

       
 
@endsection
@push('js')
 

<script>

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
@endpush
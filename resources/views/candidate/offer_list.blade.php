@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif

           
    <div class="pxp-dashboard-content-details">
        <h1>Offer Letter List</h1>
         

        @if(empty($offerletters))
        <p>No Data</p>
        @endif
        @foreach($offerletters as $offerletter)
          <div class="candidate_List_box">
                <div class="candidate_List_box_inner">
                    <div class="row">
                        <div class="col-md-2 col-sm-3 col-lg-2 text-center">
                            <img class="candidate_List_img" src="{{ ($offerletter->offerLetter->candidateDetails->photo!='')?(url('images/'.$offerletter->offerLetter->candidateDetails->photo)):(url('/new/images/noimage.png')) }}" alt="">
                            {{$offerletter->offerLetter->candidateDetails->candidate_code}}
                        </div>
                        <div class="col-md-10 col-sm-9 col-lg-10">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="line_holder">
                                        <p class="d-item_one">Company Name</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$offerletter->offerLetter->businessDetails->business->business_name}}</p>
                                    </div>
                                    
                                    <div class="line_holder">
                                        <p class="d-item_one">Joining Date</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$offerletter->offerLetter->joining_date}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Place Of Joining</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$offerletter->offerLetter->place_of_joining}}</p>
                                    </div>
                                    
                                    
                                    
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="line_holder">
                                        <p class="d-item_one">Annual CTC</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$offerletter->offerLetter->annual_ctc}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Reporting Time</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$offerletter->offerLetter->time_of_joining}}</p>
                                    </div>
                                     
                                    <div class="line_holder">
                                        <p class="d-item_one">Status</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">
                                            @if($offerletter->offerLetter->is_accepted==0)
                                            Pending
                                            @elseif($offerletter->offerLetter->is_accepted==1)
                                            Offer Accepted(Joining Confirmed)
                                            @elseif($offerletter->offerLetter->is_accepted==2)
                                            Offer Rejected
                                            @elseif($offerletter->offerLetter->is_accepted==3)
                                            Request For Reschedule
                                            @endif
                                         </p>
                                    </div>
                                    
                                </div>
                                
                            </div>
                           
                            <a href="{{url('candidate_offer_letter')}}/{{base64_encode($offerletter->offer_letter_id)}}" class="btn sm_btn rounded-pill pxp-section-cta" target="_blank">Offer Letter </a>
                            
                            <button  data-bs-toggle="modal" class=" reviewBtn btn sm_btn rounded-pill  btn-warning" data-bs-target="#reviewModal" data-id="{{$offerletter->offerLetter->businessDetails->business->user_id}}">Submit Review </button>
                           
                        </div>
                    </div>
                </div>
                
                
            </div> 

             <!-- {{$offerletter->offerLetter->candidateDetails->name}}
            
            {{$offerletter->offerLetter->post}}
            {{$offerletter->created_at}} -->

        @endforeach

        
       
    </div>

       
 <!--Review Details-->
<div class="modal fade" id="reviewModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Give a Comment</h4>
                <p class="statusMsg"></p>
                <form method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="business_id" value="">
                    <div class="mb-3">
                        <label class="form-label">Rating * </label>
                        <select  id="review"  required>
                            <option value=""> Select</option>
                            <option value="1"> 1</option>
                            <option value="2"> 2</option>
                            <option value="3"> 3</option>
                            <option value="4"> 4</option>
                            <option value="5"> 5</option>
                        </select>
                        <p class="error_review"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comment *</label>
                        <textarea   id="comment" class="form-control" placeholder="Enter Your Comment" row="3" required></textarea>
                        <p class="error_comment"></p>
                    </div>
                   
                    
                        
                    <button type="button" id="reviewBtn" onClick="reviewForm();" class="btn rounded-pill pxp-section-cta btn-block">Submit</button>
                </form>
            </div>
            </div>
        </div>
</div>
    <!--Review Details-->
@endsection
@push('js')
<script>
 $(document).on("click",".reviewBtn",function(){
    
    var business_id = $(this).data('id');//console.log(service_id);
    $('#business_id').val(business_id);
});

function reviewForm()
{
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var review=$('#review').val();
    var comment=$('#comment').val();
    var business_id=$('#business_id').val();
     

    $.ajax({
        type:"POST",
        url:"{{url('/business_review_submit')}}",
        data:'_token='+token+'&review='+review+'&comment='+comment+'&business_id='+business_id,
        success:function(response)
        {
            if(response==1)
            {
                $('#reviewBtn').hide();
                $('.statusMsg').html('<span style="color:green;">Your Comment Submitted Successfully!.</p>');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 3000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">Something was wrong .</p>');
            }
        },
        error: function (reject) {
           
                if( reject.status === 422 ) {
                    //console.log(reject);
                    var resp = $.parseJSON(reject.responseText);
                    $.each(resp.errors, function (key, val) {
                        console.log(key,val);
                        $('.error_'+key).html(val[0]).css("color","red");
                        $( key ).text(val[0]);
                    });
                }
            }
    });    
}
</script>
@endpush
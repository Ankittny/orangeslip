
@if (session()->has('candidate')) 
 
<!doctype html>
<html lang="en" class="pxp-root">
<head>
    <meta charset="utf-8">
  
    <title>True CV</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('layouts.htmlheader')
    <style>
        *{padding: 0px; margin: 0px;}
        body{padding-top: 10px; color: #757474; font-family: 'Poppins', sans-serif;}
        .body_padding{padding: 0px 15px;}
        .offer_letter_page{max-width: 700px; min-height: 600px; position: relative; box-shadow: 0px 0px 10px -2px #ccc; padding: 30px 30px; padding-top: 100px; margin: 20px auto; overflow: hidden;}
        .design1{width: 350px; position: absolute; left: 0; top: 0;}
        .design2{width: 300px; position: absolute; right: 0; bottom: 0;}
        .text_right{text-align: right;}
        .company_text{font-weight: 700; font-size: 35px; color:#111}
        .company_logo{width: 200px;}
        .candidate_name{color: #2190bd; margin-top: 20px; font-weight: 500; text-transform: uppercase;}
        .candidate_designation{font-weight: 400; }
        .small_p{font-size: 13px; line-height: 22px;}
        .mb-40{margin-bottom: 40px;}
        .mb-20{margin-bottom: 20px;}
        .mb-10{margin-bottom: 10px;}
        .joining_date_time{font-size: 13px; margin-bottom: 4px; color: #111; font-weight: 500;}
        .d-flex {display: flex;}
        .flex-fill{ -ms-flex: 1 1 auto ;flex: 1 1 auto; margin: 0px 2px;}
        .earning_text{color: #000; margin-bottom: 10px; font-size: 19px; font-weight: 600;}
        .Earnings_table table th {padding: 10px 10px; color: #fff; background: #13588f; box-sizing: border-box; font-size: 14px; font-weight: 500; text-align: left;}
        .Earnings_table table {margin-bottom: 20px; width: 100%; border-spacing: 0; border-collapse: collapse; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .12), 0 1px 2px 0 rgba(0, 0, 0, .24);}
        .Earnings_table table tbody tr td {padding: 10px; color: #474646; font-size: 12px; border-bottom: 1px solid #ccc;}
        .Earnings_table table tfoot tr td {padding: 10px; font-size: 12px;  color: #000; font-weight: 600;}
        .mt-50{margin-top: 50px;}
        .Powered_logo{width: 200px;}
    </style>
   
</head>
<body>



         

        <div class="container text-end" style="max-width: 700px;">
        <br>
            <a class="btn btn-danger" href="{{url('candidate/logout')}}">Logout</a>
        </div>

    



    <div class="container mb-5 mt-3" style="max-width: 700px;">
       <h5 class="earning_text">All Offers :</h5>
       <div class="table-responvive">              
            <table class="table ">
                <tr>
                    <th>ID</th>
                    <th>Company</th>
                    <th>Job Role</th>
                    <th>Joinig Date</th>
                    <th>Reporting Time</th>
                    <th>Joinig Place</th>
                    <th>CTC</th>
                    <th>Status</th>
                    <th>Action</th>
                        
                </tr>
                @foreach($offer_letter as $old_offer)
                    @php
                    $busniess=DB::table('business_details')->where('user_id','=',$old_offer->business_id)->first();
                    @endphp
                <tr>
                    <td>{{$old_offer->id}}</td>
                    <td>{{$busniess->business_name}}</td>
                    <td>{{$old_offer->post}}</td>
                    <td>{{$old_offer->joining_date}}</td>
                    <td>{{$old_offer->time_of_joining}}</td>                    
                    <td>{{$old_offer->place_of_joining}}</td>                    
                    <td>{{$old_offer->annual_ctc}}</td>
                    
                    <td>                        
                        @if($old_offer->is_accepted==0)
                        Pending
                        @elseif($old_offer->is_accepted==1)
                        Accepted
                        @elseif($old_offer->is_accepted==2)
                        Rejected
                        @elseif($old_offer->is_accepted==3)
                        reschedule
                        @endif
                    </td>
                    <td>
                         <a href="{{url('offer_letter')}}/{{base64_encode($old_offer->id)}}" class="btn sm_btn rounded-pill pxp-section-cta" target="_blank">Offer Letter</a>
                         @if($old_offer->is_accepted==0)
                        
                            <button type="button" class="action_btn btn sm_btn rounded-pill btn-success"  data-code="1" data-id="{{$old_offer->id}}" name="approve">Accept</button>
                            <button type="button" class="action_btn btn sm_btn rounded-pill btn-danger" data-code="2" data-id="{{$old_offer->id}}" name="reject">Reject</button>
                            <button type="button" class="btn sm_btn rounded-pill btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$old_offer->id}}" name="reschedule"> Reschedule</button>           
                        </div>
                         @endif
                    </td>

                    
                    
                        
                </tr>
                @endforeach
                    
            </table>
        </div>
    </div>

    <!-- Modal -->
        
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="statusMsg"></p>
                    <form method="post">
                        <input type="hidden" id="letter_id" value="">
                        <div class="mb-3">
                            <label  class="form-label">Reason *</label>
                            <select  id="reason" name="reason" class="form-control"  required>
                                <option value="" Selected>Select</option>
                                @foreach($reasons as $res)
                                <option value="{{$res->name}}">{{$res->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label  class="form-label">Date *</label>
                            <input type="date" id="new_date" name="new_date" class="form-control"  required>
                        </div>
                        <div class="mb-3">
                            <label  class="form-label">Time *</label>
                            <input type="time" id="new_time" name="new_time" class="form-control"  required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" id="btn1" onclick="reschedule()" >Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@include('layouts.script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $("form").submit(function () {
        // prevent duplicate form submissions
        $(this).find(":submit").attr('disabled', 'disabled');
    });
 
    
 
    
    $('button[name=approve]').click(function () {
        var result = window.confirm('Are You Sure?');
        var lid = $(this).data('id');
       // console.log(result);
           if(result==true) {
			$('.action_btn').prop('disabled', true);
               var code = $(this).data('code');
               var data = {
                   _token: '{{ csrf_token() }}',
                   
                   code: code,
                   lid:lid
                };
               console.log(data);
			   $.ajax({
                   url: "{{url('offerletterresponse')}}/"+lid,
                   type:'post',
                   data:data,
                   success: function (result) {
                    if(result==1) {
                            alert('Offer Accepted Successfully');
                        } else {
                            alert('Offer Rejected Successfully');
                        }
                       location.reload();
                   },
                   error: function (result) {
                       alert('{{ __("some error occoured!") }}');
                   }
               })
           } else {
               alert('{{ __("Offer can not be approved without Accept") }}');
           }
       });

        $('button[name=reject]').click(function () {
    
           var result = window.prompt('{{ __("Enter Reason please!") }}');
           if(result) {
			$('.action_btn').prop('disabled', true);
               var code = $(this).data('code');
               var lid = $(this).data('id');
               
               var data = {
                   _token: '{{ csrf_token() }}',
                   reason: result,
                   code: code,
                   lid:lid
               };
               console.log(data);
			   $.ajax({
                   url: "{{url('offerletterresponse')}}/"+lid,
                 
                   type:'post',
                   data:data,
                   success: function (result) {
					    
                        if(result==1) {
                            alert('Offer Accepted Successfully');
                        } else {
                            alert('Offer Rejected Successfully');
                        }
                       location.reload();
                   },
                   error: function (result) {
                       alert('{{ __("some error occoured!") }}');
                   }
               })
           } else {
               alert('{{ __("Offer can not be Reject without Reason") }}');
           }
       });
       $('button[name=reschedule]').click(function () {
        
        var lid = $(this).data('id');
         $("#letter_id").val(lid);

       });
    
       function reschedule(){
            //console.log(1);
           // alert(1);
          //var result = window.confirm('Are You Sure?');
       // console.log(result);
         var new_date= $('#new_date').val();
         var reason= $('#reason').val();
         var new_time= $('#new_time').val();
         var lid=$('#letter_id').val();
          
         console.log(lid);
			$('.action_btn').prop('disabled', true);
               var code = 3;
               var data = {
                   _token: '{{ csrf_token() }}',
                  
                   new_date:new_date,
                   reason:reason,
                   new_time:new_time,
                   lid:lid,
                   code: code
                };
               console.log(data);
			   $.ajax({
                   url: "{{url('offerletterresponse')}}/"+lid,
                   type:'post',
                   data:data,
                   success: function (result) {
                    
                    if(result==3) {
                        $('#btn1').hide();
                        $('.statusMsg').html('<span style="color:green;">Reschedule Request Submitted .</p>');
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 5000);
                        }else{
                            $('.statusMsg').html('<span style="color:red;">'+result+'</span>');
                        }
                        
                   },
                    
               })
          
       }

</script>
</body>

</html>
 
  
        
	  


 
 
@endif

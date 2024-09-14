abcd
@if (session()->has('candidate_id')) 
 
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



        {{-- @php
        $business=DB::table('business_details')->where('user_id','=',$offer_letter->business_id)->first();
        @endphp --}}

        <div class="container text-end" style="max-width: 700px;">
        <br>
            <a class="btn btn-danger" href="{{url('candidate/logout')}}">Logout</a>
        </div>

    {{-- <div class="body_padding">
        <div class="offer_letter_page">
            <img class="design1" src="https://p4.bemychoice.com/new/images/design1.png" alt="">
            <img class="design2" src="https://p4.bemychoice.com/new/images/design2.png" alt="">
            <div class="text_right mb-10">
            
                <img class="Powered_logo" src="https://p4.bemychoice.com/new/images/logo.png" alt="">
            </div>
            <p class="mb-20 text_right">Date: {{date('d-m-Y', strtotime($offer_letter->updated_at))}}</p>
         
          
           <p class="mb-20">Dear <span>{{$candidate->name}},</span></p>

           <p class="small_p mb-20" style="text-align: justify;">We are pleased to offer you the job role of a <strong>{{$offer_letter->post}}</strong> and appoint you for the same position. The salary offered for the position as finalized by our management will be <strong>CTC- {{$offer_letter->annual_ctc}}</strong>, and your joining date and time will be <strong>{{date('d-m-Y', strtotime($offer_letter->joining_date))}} , {{$offer_letter->time_of_joining}}</strong>.  We are excitedly looking forward to working with you and seeing where you take our company in the coming years.</p>
          
           <p class="small_p mb-20" style="text-align: justify;">Please find the attached terms and conditions pertaining to your employment and familiarize yourself with all the clauses, perks and benefits that will be provided to you during the course of your employment. Kindly acknowledge and sign the appointment letter. Your appointment is subjected to the accuracy of the documents and testimonials provided by you and you being free from any contractual restrictions preventing you to take up this opportunity. As an employee of <strong>{{$business->business_name}}</strong> you will have access to our comprehensive benefit programs. Please contact us directly via phone or email in case of any question or confusions.</p>
        
                @php
                    $t_er_amt=0;
                    $t_er_amt_y=0;
                    $t_dd_amt=0;
                    $t_dd_amt_y=0;
                    $e=count((array)$earning);
                    $d=count((array)$deduction);

                @endphp
            <div class="d-flex mb-20 Earnings_table">
                <div class="flex-fill">
                    <h5 class="earning_text">Earnings</h5>
                    <div class="table-responvive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Components</th>
                                    <th>Monthly</th>
                                    <th>Annual</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                @foreach($earning as $er)
                                    <tr>
                                        <td>{{$er->component}}</td>
                                        <td>{{$er->amount}}</td>
                                        <td>{{$er->amount*12}}</td>
                                    </tr>
                                    @php
                                    $t_er_amt=$er->amount+$t_er_amt;
                                    $t_er_amt_y=($er->amount*12)+($t_er_amt_y);
                                    @endphp
                                @endforeach
                                    @php
                                    if($e<$d)
                                    {
                                        $b_row=$d-$e;
                                        for($i=0; $i<$b_row; $i++)
                                        {
                                    @endphp
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td> &nbsp;</td>
                                            <td>&nbsp; </td>
                                        </tr>
                                    @php
                                        }

                                    }
                                    @endphp
                            
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Total Earning Amount</td>
                                    <td>{{$t_er_amt}}</td>
                                    <td>{{$t_er_amt_y}}</td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
                <div class="flex-fill">
                    <h5 class="earning_text">Deductions</h5>
                    <div class="table-responvive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Components</th>
                                    <th>Monthly</th>
                                    <th>Annual</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($deduction as $dd)
                                <tr>
                                    <td>{{$dd->component}}</td>
                                    <td>{{$dd->amount}}</td>
                                    <td>{{$dd->amount*12}}</td>
                                </tr>
                                @php
                                $t_dd_amt=$dd->amount+$t_dd_amt;
                                $t_dd_amt_y=($dd->amount*12)+($t_dd_amt_y);
                                @endphp
                            @endforeach  
                                @php
                                    if($e>$d)
                                    {
                                    $b_row=$e-$d;
                                    for($i=0; $i<$b_row; $i++)
                                    {
                                @endphp
                                    <tr >
                                        <td > &nbsp;</td>
                                        <td > &nbsp;</td>
                                        <td > &nbsp;</td>
                                    </tr>
                                @php
                                    }

                                    }
                                @endphp
                            
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Total Deduction Amount</td>
                                    <td>{{$t_dd_amt}}</td>
                                    <td>{{$t_dd_amt_y}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


        <p class="mb-20">Sincerely,</p>
        <p class="mb-10">HR,</p>
        
        <h5 class="joining_date_time">{{$offer_letter->hrDetails->first_name}},</h5>
        <h5 class="joining_date_time"><span>{{$offer_letter->hrDetails->email}},</span></h5>
        <h5 class="joining_date_time"><span>{{$offer_letter->hrDetails->profile->mobile_no}}</span></h5>
        
        <div>
            <img class="company_logo" src="{{ ($business->logo!='')?(url('images/'.$business->logo)):(url('images/country_image/no_image.jpg')) }}" alt="">
        </div>
          
       
       
        </div>

    </div> 

    @if($offer_letter->is_accepted==0)
		<div style="padding:10px; color: #fff; font-size: 13px; background:#fff; text-align:center;">
            <button type="button" class="action_btn btn btn-success"  data-code="1" name="approve">Accept</button>
            <button type="button" class="action_btn btn btn-danger" data-code="2" name="reject">Reject</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"> Reschedule</button>           
		</div>
    @endif
 
    <div class="container mb-5 mt-3" style="max-width: 700px;">
       <h5 class="earning_text">All Offers :</h5>
        <div>               
            <table class="table ">
                <tr>
                    <th>Company</th>
                    <th>Job Role</th>
                    <th>Joinig Date</th>
                    <th>Joinig Place</th>
                    <th>CTC</th>
                        
                </tr>
                @foreach($offer_letter as $old_offer)
                    @php
                    $busniess=DB::table('business_details')->where('user_id','=',$old_offer->business_id)->first();
                    @endphp
                <tr>
                    <td>{{$busniess->business_name}}</td>
                    <td>{{$old_offer->post}}</td>
                    <td>{{$old_offer->joining_date}}</td>
                    <td>{{$old_offer->place_of_joining}}</td>
                    <td>{{$old_offer->annual_ctc}}</td>
                        
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

 <script>
$("form").submit(function () {
    // prevent duplicate form submissions
    $(this).find(":submit").attr('disabled', 'disabled');
});
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    

$('button[name=reject]').click(function () {
    
           var result = window.prompt('{{ __("Enter Reason please!") }}');
           if(result) {
			$('.action_btn').prop('disabled', true);
               var code = $(this).data('code');
               var lid = $(this).data('id');
               console.log(lid);
               var data = {
                   _token: '{{ csrf_token() }}',
                   reason: result,
                   code: code
               };
               console.log(data);
			   $.ajax({
                   url: "{{url('offerletterresponse')}}/",
                //    url: url + '/offerletterresponse/{{$candidate->id}}',
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

       $('button[name=approve]').click(function () {
        var result = window.confirm('Are You Sure?');
       // console.log(result);
           if(result==true) {
			$('.action_btn').prop('disabled', true);
               var code = $(this).data('code');
               var data = {
                   _token: '{{ csrf_token() }}',
                   
                   code: code
                };
               console.log(data);
			   $.ajax({
                   url: "{{url('offerletterresponse')}}",
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

       //$('button[name=reschedule]').click(function () {
        function reschedule(){
            //console.log(1);
           // alert(1);
          //var result = window.confirm('Are You Sure?');
       // console.log(result);
         var new_date= $('#new_date').val();
         var reason= $('#reason').val();
         var new_time= $('#new_time').val();
			$('.action_btn').prop('disabled', true);
               var code = 3;
               var data = {
                   _token: '{{ csrf_token() }}',
                  
                   new_date:new_date,
                   reason:reason,
                   new_time:new_time,
                   
                   code: code
                };
               console.log(data);
			   $.ajax({
                   url: "{{url('offerletterresponse')}}/{{$offer_letter->id}}",
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


</script> --}}


<div class="container mb-5 mt-3" style="max-width: 700px;">
       <h5 class="earning_text">All Offers :</h5>
        <div>               
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
                        
                            <button type="button" class="action_btn btn sm_btn rounded-pill btn-success"  data-code="1" name="approve">Accept</button>
                            <button type="button" class="action_btn btn sm_btn rounded-pill btn-danger" data-code="2" data-id="{{$old_offer->id}}" name="reject">Reject</button>
                            <button type="button" class="btn sm_btn rounded-pill btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"> Reschedule</button>           
                        </div>
                         @endif
                    </td>

                    
                    
                        
                </tr>
                @endforeach
                    
            </table>
        </div>
    </div>
    @include('layouts.script')
    <script>
$("form").submit(function () {
    // prevent duplicate form submissions
    $(this).find(":submit").attr('disabled', 'disabled');
});
</script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    $('button[name=approve]').click(function () {
        var result = window.confirm('Are You Sure?');
       // console.log(result);
           if(result==true) {
			$('.action_btn').prop('disabled', true);
               var code = $(this).data('code');
               var data = {
                   _token: '{{ csrf_token() }}',
                   
                   code: code
                };
               console.log(data);
			   $.ajax({
                   url: "{{url('offerletterresponse')}}",
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

    


</script>
</body>

</html>
 
  
        
	  


 
 
@endif

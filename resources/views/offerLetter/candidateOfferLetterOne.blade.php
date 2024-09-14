<!DOCTYPE html>
<html>
<head>
    <title>OFFER LETTER</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('layouts.htmlheader')
    <style>
        *{margin: 0px; padding: 0px;}
        body{font-family: 'Poppins', sans-serif; font-size: 14px;}
        .offer_letter_bg{background: #ebebed; margin:30px 10px;}
        .offer_letter_container{max-width: 800px; margin: auto; }
        .offer_letter_box{background: #fff; padding: 20px 40px; min-height: 800px;}
        .logo_box{border-bottom: 1.5px solid #000; margin-bottom: 20px; text-align: center; padding: 20px 10px; position: relative;}
        .logo_box::before{content: ""; background: #ee1c25; width: 300px; height: 4px; position: absolute; left: 0; right: 0; margin: auto; bottom: -3px;}
        .logo_box img{max-width: 230px; height: 100px;}
        .offer_letter_box h1{text-align: center; font-weight: 500; font-size: 27px;}
        .text-right{text-align: right;}
        .mt-4{margin-top: 20px;}
        .mb-4{margin-bottom: 20px;}
        .mb-3{margin-bottom: 15px;}
        .mb-3{margin-bottom: 15px;}
       .black_border{background: #333132; height: 30px;}
       .red_border{background: #ee1c25; height: 20px;}
       
       .d-flex {display: flex;}
        .flex-fill{ -ms-flex: 1 1 auto ;flex: 1 1 auto; margin: 0px 2px;}
        .earning_text{color: #000; margin-bottom: 10px; font-size: 19px; font-weight: 600;}
        table th {padding: 10px 10px; color: #fff; background: #13588f; box-sizing: border-box; font-size: 14px; font-weight: 500; text-align: left;}
        table {margin-bottom: 20px; width: 100%; border-spacing: 0; border-collapse: collapse; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .12), 0 1px 2px 0 rgba(0, 0, 0, .24);}
        table tbody tr td {padding: 10px; color: #474646; font-size: 13px; border-bottom: 1px solid #ccc;}
        table tfoot tr td {padding: 10px; font-size: 14px; color: #000;}


        @media only screen and (max-width:760px) {
            .offer_letter_box{ padding: 20px 20px;}
            .logo_box {padding: 10px 10px}
            .logo_box::before{width: 150px;}

        }
    </style>
</head>
        @php
        $business=DB::table('business_details')->where('user_id','=',$offer_letter->business_id)->first();
        @endphp
<body class="offer_letter_bg">
    <div class="offer_letter_container">
        <div class="offer_letter_box">
            <div class="logo_box">
                <img src="{{ ($business->logo!='')?(url('images/'.$business->logo)):(url('/new/images/logo.png')) }}" alt="" >
                <h4>{{$business->business_name}}</h4>
            </div>
            <h1>Offer Letter</h1>
            <p class="text-right mt-4">Date- {{date('d-m-Y', strtotime($offer_letter->updated_at))}}</p>

            <!-- <p class="mb-3"><strong>Subject :</strong> Offer Letter</p> -->
            <p>To,</p>
            <h3>{{$offer_letter->candidateDetails->name}}</h3>
            <p class="mb-4"><small>{{$offer_letter->jobRole->name}}</small></p>

            <p class="mb-3" style="text-align:justify" >We are pleased to offer you the job role of a <strong>{{$offer_letter->jobRole->name}}</strong> and appoint you for the same position. The salary offered for the position as finalized by our management will be <strong>CTC:  INR {{$offer_letter->annual_ctc}}</strong>, and your joining date and reporting time will be <strong>{{date('d-m-Y', strtotime($offer_letter->joining_date))}} , {{$offer_letter->time_of_joining}}</strong>.  We are excitedly looking forward to working with you and seeing where you take our company in the coming years.</p>
          
          <p style="text-align:justify" class="mb-4">Please find the attached terms and conditions pertaining to your employment and familiarize yourself with all the clauses, perks and benefits that will be provided to you during the course of your employment. Kindly acknowledge and sign the appointment letter. Your appointment is subjected to the accuracy of the documents and testimonials provided by you and you being free from any contractual restrictions preventing you to take up this opportunity. As an employee of <strong>{{$business->business_name}} ({{$offer_letter->place_of_joining}})</strong> you will have access to our comprehensive benefit programs. Please contact us directly via phone or email in case of any question or confusions.</p>

          @php
                    $t_er_amt=0;
                    $t_er_amt_y=0;
                    $t_dd_amt=0;
                    $t_dd_amt_y=0;
                    $e=count((array)$earning);
                    $d=count((array)$deduction);

                @endphp
            <div class="d-flex mb-20">
                <div class="flex-fill">
                    <h5 class="earning_text">Earnings</h5>
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
                <div class="flex-fill">
                    <h5 class="earning_text">Deductions</h5>
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

            <p class="mt-4 mb-4"><strong>Best Regards</strong></p>

            <p><strong>{{$offer_letter->hrDetails->first_name}}</strong></p>
            <p>{{$offer_letter->hrDetails->profile->mobile_no}} </p>            
            <p>{{$offer_letter->hrDetails->email}}</p>
            <p>HR </p>
            

            <p class="mt-4 text-right">Powered By <strong>{{env('APP_NAME')}}</strong></p>
            
        </div>
        <div class="black_border"></div>
        <div class="red_border"></div>
    </div>
    @if($offer_letter->is_accepted==0 && $offer_letter->is_modify==0)
        <div class="text-center mt-4 mb-4">
            <button type="button" class="action_btn btn sm_btn rounded-pill btn-success"  data-code="1" data-id="{{$offer_letter->id}}" name="approve">Accept</button>
            <button type="button" class="action_btn btn sm_btn rounded-pill btn-danger" data-code="2" data-id="{{$offer_letter->id}}" name="reject">Reject</button>
            <button type="button" class="btn sm_btn rounded-pill btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$offer_letter->id}}" name="reschedule"> Reschedule</button>
        </div>
    @endif
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
                            <select class="form-control" id="res_reason" name="res_reason"  required>
                            <!-- <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option> -->
                                @foreach($reasons as $res)
                                <option value='{{$res->id}}'>{{$res->title}}</option>
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
    @include('layouts.offerLetterScript')

</body>
</html> 
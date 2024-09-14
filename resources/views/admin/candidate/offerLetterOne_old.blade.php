<!doctype html>
<html lang="en" class="pxp-root">
<head>
    <meta charset="utf-8">
  
    <title>Recrueet</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *{padding: 0px; margin: 0px;}
        body{padding-top: 10px; color: #757474; font-family: 'Poppins', sans-serif;}
        .body_padding{padding: 0px 15px;}
        .offer_letter_page{max-width: 700px; border-bottom: 5px solid #2190bd; border-top: 5px solid #2190bd; min-height: 600px; position: relative; box-shadow: 0px 0px 10px -2px #ccc; padding: 30px 30px; padding-top: 100px; margin: 20px auto; overflow: hidden;}
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
        table th {padding: 10px 10px; color: #fff; background: #13588f; box-sizing: border-box; font-size: 14px; font-weight: 500; text-align: left;}
        table {margin-bottom: 20px; width: 100%; border-spacing: 0; border-collapse: collapse; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .12), 0 1px 2px 0 rgba(0, 0, 0, .24);}
        table tbody tr td {padding: 10px; color: #474646; font-size: 13px; border-bottom: 1px solid #ccc;}
        table tfoot tr td {padding: 10px; font-size: 14px; color: #000;}
        .mt-50{margin-top: 50px;}
        .Powered_logo{width: 200px;}
    </style>
</head>
<body>
        @php
        $business=DB::table('business_details')->where('user_id','=',$offer_letter->business_id)->first();
        @endphp
    <div class="body_padding">
        <div class="offer_letter_page">
            <img class="design1" src="https://p4.bemychoice.com/new/images/design1.png" alt="">
            <img class="design2" src="https://p4.bemychoice.com/new/images/design2.png" alt="">
            <div class="text_right mb-10">
                <img class="Powered_logo" src="https://p4.bemychoice.com/new/images/logo.png" alt="">
            </div>
            <p class="mb-20 text_right">Date: {{date('d-m-Y', strtotime($offer_letter->updated_at))}}</p>
         
          
           <p class="mb-20">Dear <span>{{$offer_letter->candidateDetails->name}},</span></p>

           <p class="small_p mb-20" style="text-align: justify;">We are pleased to offer you the job role of a <strong>{{$offer_letter->jobRole->name}}</strong> and appoint you for the same position. The salary offered for the position as finalized by our management will be <strong>CTC:  INR {{$offer_letter->annual_ctc}}</strong>, and your joining date and time will be <strong>{{date('d-m-Y', strtotime($offer_letter->joining_date))}} , {{$offer_letter->time_of_joining}}</strong>.  We are excitedly looking forward to working with you and seeing where you take our company in the coming years.</p>
          
           <p class="small_p mb-20" style="text-align: justify;">Please find the attached terms and conditions pertaining to your employment and familiarize yourself with all the clauses, perks and benefits that will be provided to you during the course of your employment. Kindly acknowledge and sign the appointment letter. Your appointment is subjected to the accuracy of the documents and testimonials provided by you and you being free from any contractual restrictions preventing you to take up this opportunity. As an employee of <strong>{{$business->business_name}} ({{$offer_letter->place_of_joining}})</strong> you will have access to our comprehensive benefit programs. Please contact us directly via phone or email in case of any question or confusions.</p>
        
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
                        

</body>

</html>
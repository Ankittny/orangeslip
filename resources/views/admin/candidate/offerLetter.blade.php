 

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Offer Letter</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  
</head>
<body style="background: #DBDBDB; font-family: Poppins">
	<div style="max-width:650px; margin:20px auto;">
		<div style="background:#020910; text-align:center;">
			<div style="background: #fff; padding: 20px ">
				<img style="width: 210px;" src="https://p4.bemychoice.com/new/images/logo.png" alt="">
			</div>
		</div>
        @php
        $busniess=DB::table('business_details')->where('user_id','=',$offer_letter->business_id)->first();
        @endphp
		<div style="background: #fff; ">
        <img style="width: 200px;" src="https://www.phishlabs.com/wp-content/uploads/2021/10/PhishLabs_by_HS-Logo-CMYK-Padding.png" alt="">
			{{--<img style="width: 200px;" src="{{ ($busniess->logo!='')?(url('images/'.$busniess->logo)):(url('images/country_image/no_image.jpg')) }}" alt="">
			<img style="width: 200px;" src="{{ ($candidate->photo!='')?(url('images/'.$candidate->photo)):(url('images/country_image/no_image.jpg')) }}" alt="">--}}
		</div>
		<div style="background:#fff; padding:20px;">
			<h4 style="text-align: center;"><b>OFFER LETTER</b></h4>
			<h4 style="text-align: right; margin:0px;"><b>{{date('d-m-Y', strtotime($offer_letter->updated_at))}}</b></h4>
			<div style="font-size: 14px; color: #606060;">
				<h4 style="margin:0px;"><b>To,</b></h4>
				<h4 style="margin:0px;"><b>{{$candidate->name}},</b></h4>
				<h4><b>Dear Mr. {{$candidate->name}},</b></h4>
				<p>Further to the interview attended by you at our  Office, we are pleased to offer you an appointment as <b>{{$offer_letter->post}}</b> at our <b>{{$offer_letter->place_of_joining}}</b> Office. </p>
			</div>
            <h4 style="margin:0px;"><b>Joining Date:{{date('d-m-Y', strtotime($offer_letter->joining_date))}}.</b></h4>
            <h4 style="margin:0px;"><b>Joining Time:{{$offer_letter->time_of_joining}}.</b></h4>
            <h4 style="margin:0px;"><b>Business Name:{{$busniess->business_name}}.</b></h4>
            
           
            
			
			
			<p style="font-size: 16px; margin-top: 30px; margin-bottom: 5px; background-color:#080674; color:#fff; padding:10px;"><strong>CTA STRUCTURE : {{$offer_letter->annual_ctc}}</strong></p>
            <div style="overflow:hidden">
                <div style="width:50%; float:left;">
                <p style="margin: 5px 0px;"><strong>Earnings</strong></p>
                <table style="border-spacing: 0; color: #666;">
                    <tr style="text-align: left; font-size: 14px;">
                        <th style="padding: 10px; border-bottom: 1px solid #ccc;">Components</th>
                        <th style="padding: 10px; border-bottom: 1px solid #ccc;">Monthly</th>
                        <th style="padding: 10px; border-bottom: 1px solid #ccc;">Annual</th>
                    </tr>
                     
                    @php
                    $t_er_amt=0;
                    $t_er_amt_y=0;
                    $t_dd_amt=0;
                    $t_dd_amt_y=0;
                    $e=count((array)$earning);
                    $d=count((array)$deduction);

                    @endphp
                    @isset($earning)
                       
                    @foreach($earning as $er)
                    <tr style="font-size: 14px;">
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;">{{$er->component}}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;">{{$er->amount}}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;">{{$er->amount*12}}</td>
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
                                        <tr style="font-size: 14px;">
                                            <td style="padding: 10px; border-bottom: 1px solid #ccc;">&nbsp;</td>
                                            <td style="padding: 10px; border-bottom: 1px solid #ccc;"> &nbsp;</td>
                                            <td style="padding: 10px; border-bottom: 1px solid #ccc;">&nbsp; </td>
                                        </tr>
                                    @php
                                        }

                                      }
                                    @endphp

                    <tr style="font-size: 14px;">
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><strong>Total Earning Amount</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><strong>{{$t_er_amt}}</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><strong>{{$t_er_amt_y}}</strong></td>
                    </tr> 
                    @endif
                        
                </table>
                </div>
                <div style="width:50%; float:left;">
                <p style="margin: 5px 0px;"><strong>Deductions</strong></p>
                <table style="border-spacing: 0; color: #666;">
                    <tr style="text-align: left; font-size: 14px;">
                        <th style="padding: 10px; border-bottom: 1px solid #ccc;">Components</th>
                        <th style="padding: 10px; border-bottom: 1px solid #ccc;">Monthly</th>
                        <th style="padding: 10px; border-bottom: 1px solid #ccc;">Annual</th>
                    </tr>
                    
                    @isset($deduction)
                   
                    @foreach($deduction as $dd)
                    <tr style="font-size: 14px;">
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;">{{$dd->component}}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;">{{$dd->amount}}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;">{{$dd->amount*12}}</td>
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
                                        <tr style="font-size: 14px;">
                                            <td style="padding: 10px; border-bottom: 1px solid #ccc;"> &nbsp;</td>
                                            <td style="padding: 10px; border-bottom: 1px solid #ccc;"> &nbsp;</td>
                                            <td style="padding: 10px; border-bottom: 1px solid #ccc;"> &nbsp;</td>
                                        </tr>
                                    @php
                                        }

                                      }
                                    @endphp
                    <tr style="font-size: 14px;">
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><strong>Total Deduction Amount</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><strong>{{$t_dd_amt}}</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ccc;"><strong>{{$t_dd_amt_y}}</strong></td>
                    </tr> 
                    @endif
                </table>
                </div>
               
              
            </div>
		</div>

		<div style="background:#fff; padding:20px;">
			<div style="font-size: 14px; color: #606060;">
				<p>*TDS will be calculated based on the I.T Act of 1961.</p>
				<p>**Payable Quarterly/Half Yearly based on your Target Achievement.</p>
				<p>***You are eligible for Conveyance allowance against fuel bills in original.</p>
			</div>
		</div>
		
		<div style="background:#080674; padding:20px; font-size: 14px;">
			<p style="text-align:left; color: #d1d1d1;"><b>Note:</b> The company reserves the right to modify your compensation structure to include any other reimbursable expenses as part of your fixed monthly compensation structure, in line with statutory obligations from time-to-time. In any case, all liability towards your personal income tax and professional tax continues to rest with you.</p>
		</div>
		<div style="padding:10px; color: #fff; font-size: 13px; background:#000; text-align:center;">
			© 2024 {{env('APP_NAME')}}. All Right Reserved.
		</div>
	
	
	</div>
    
    
</body>  
</html>
 
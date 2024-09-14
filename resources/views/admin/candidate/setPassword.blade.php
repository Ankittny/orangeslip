
@if (session()->has('candidate')) 
 
<!doctype html>
<html lang="en" class="pxp-root">
<head>
    <meta charset="utf-8">
  
    <title>Recrueet</title>
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
       <h5 class="earning_text">Set Password :</h5>
        <div>               
            <form method="post"  action="{{url('candidate/home')}}">
                @csrf
                <div class="form-floating mb-3">
                    <label for="password" class="form-label">Password</label>  
                    <input id="password" placeholder="Password" type="password" class="form-control" name="password" >
                                           
                </div>   
                @if($errors->has('password'))
                        <label class="text-danger">{{ $errors->first('password') }}</label>
                    @endif          
                <div class="form-floating mb-3">
                    <label for="password_confirm" class="form-label">Confirm Password</label>  
                    <input id="password_confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" >
                                                   
                </div>      
                @if($errors->has('password_confirmation'))
                        <label class="text-danger">{{ $errors->first('password_confirmation') }}</label>
                    @endif        
                       
                <button class="btn rounded-pill pxp-section-cta">Save</button>
            </form>
        </div>
    </div>

   
@include('layouts.script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
 
  
        
	  


 
 
@endif

<!DOCTYPE html>

<html lang="en">

<head>

  <title>OTP For Candidate Login</title>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body style="background: #DBDBDB; font-family: Helvetica Neue,Helvetica,Arial,sans-serif;">

	<div style="max-width:650px; margin:20px auto;">

		<div style="background:#e9f4ff; text-align:center;">

			<div style="padding: 20px;">

				<img style="width:30%;" src="{{ url('new/images/logo.png') }}" alt="Elite Wallet">

			</div>

		</div>

		<div style="background:#fff; padding:20px;">

			<h4><b>Dear,</b></h4>

			<div style="font-size: 14px; color: #606060;">

				<p>Candidate Your OTP for Login Orangeslip is :  </p>
				<p>{{$new_otp}}</p>

				<p>Thanks,</p>

				<p>{{env("APP_NAME")}}</p>

			</div>		

		</div>

	</div>

</body>  



</html>
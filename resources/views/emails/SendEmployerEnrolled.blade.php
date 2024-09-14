<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{env("APP_NAME")}}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&amp;display=swap">
</head>
<body style="background: #f6f6f6; color:#222; padding: 10px 10px; font-size: 13px; line-height: 22px;">


  
<div style="max-width: 700px; font-family: 'Poppins'; z-index: 11; background: #fff; margin: 10px auto; position: relative;">
   
    <div style="padding: 15px 0px; border-bottom: 1px solid #ccc; text-align: center;">
        <img style="width: 200px;" src="{{ url('new/images/logo.png') }}" alt="" />
    </div>
    <div style="padding: 50px 20px;">
        <h4 style="text-align: left; margin: 0px;">Hello,</h4>
        <p style="text-align: left; margin: 0px;">Welcome to the <strong>{{env("APP_NAME")}}</strong> platform! </p>
        <br>
        
        <p style="margin-bottom: 10px;">
			Congratulations on your successful enrollment in Orangeslip. You are just a step away to use the platform, as our team will follow up with further verifications. We kindly ask for your patience and will get in touch with you shortly.
		</p>
        
    </div>
    <div style="padding: 20px 20px; background: #002745; color:#fff;">
        <p style="text-align: center; margin: 0px;">Thanks for connecting with us.</p>
    </div>
</div>

</body>
</html>
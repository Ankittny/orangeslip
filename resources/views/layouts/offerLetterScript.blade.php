
    
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $("form").submit(function () {
        // prevent duplicate form submissions
        $(this).find(":submit").attr('disabled', 'disabled');
    });
 
    

        $('button[name=approve]').click(function () {
            var result = window.confirm('Are You Sure?');
            var lid = $(this).data('id');
            console.log(result);

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
                   url:"{{url('offerletterresponse')}}",
                   type:'post',
                   data:data,
                   success: function (result) {
                    if(result==1) {
                            alert('Offer Accepted Successfully');
                        } else {
                            alert('Offer Rejected Successfully');
                        }
                       window.location="/candidate_uncheck_offer";
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
                   url:"{{url('/offerletterresponse')}}",                 
                   type:'post',
                   data:data,
                   success: function (result) {
					    
                        if(result==1) {
                            alert('Offer Accepted Successfully');
                        } else {
                            alert('Offer Rejected Successfully');
                        }
                        window.location="/candidate_uncheck_offer";
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
            console.log(1);
           // alert(1);
          //var result = window.confirm('Are You Sure?');
       // console.log(result);
         var new_date= $('#new_date').val();
         var reason= $('#res_reason').val();
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
                   url:'{{url("/offerletterresponse")}}',
                   type:'post',
                   data:data,
                   success: function (result) {                    
                    if(result==3) {
                        $('#btn1').hide();
                        $('.statusMsg').html('<span style="color:green;">Reschedule Request Submitted .</p>');
                        window.location="/candidate_uncheck_offer";
                        }else{
                            $('.statusMsg').html('<span style="color:red;">'+result+'</span>');
                        }
                        
                   },
                    
               })
          
       }

</script>
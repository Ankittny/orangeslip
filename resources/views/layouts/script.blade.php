
<div class="pxp-preloader"><span>Loading...</span></div>


<div class="overlay">
    <div class="overlayDoor"></div>
    <div class="overlayContent">
      <img src="/new/images/logo.png" alt="" />
      <p>Loading.....</p>
    </div>
  </div>

<script src="/new/js/bootstrap.bundle.min.js"></script>
<script src="/new/js/owl.carousel.min.js"></script> 
<script src="/new/js/nav.js"></script>
<script src="/new/js/main.js"></script>
 <script src="/new/js/selectize.js"></script> 
 <script src="/new/js/footable.js"></script> 
 <script src="/new/js/jquery.magnific-popup.js"></script> 
 <script src="/new/js/validate.js"></script> 
 <!-- <script src="/new/js/masking-input.js"></script>  -->
 
{{--<script src="/new/js/bootbox.min.js"></script>--}}

<script>
    var element = $('.floating-chat');
    
    setTimeout(function () {
        element.addClass('enter');
    }, 1000);

    element.click(openElement);

    function openElement() {
        var textInput = element.find('.text-box');
        
        element.addClass('expand');
        element.find('.chat').addClass('enter');
        var strLength = textInput.val().length * 2;
        
        element.off('click', openElement);
        element.find('.header button').click(closeElement);
        element.find('.chat_hide').click(closeElement);
    
    }

    function closeElement() {
        element.find('.chat').removeClass('enter').hide();
        element.removeClass('expand');
        element.find('.header button').off('click', closeElement);
        element.find('.chat_hide').off('click', closeElement);
        
        setTimeout(function () {
            element.find('.chat').removeClass('enter').show()
            element.click(openElement);
        }, 500);
    }

    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
      
</script>


<script>
  $(window).bind('load', function() {
    $('.overlay, body').addClass('loaded');
    setTimeout(function() {
        $('.overlay').css({'display':'none'})
    }, 2000)
});

    var url='https://orangeslip.com';
    $(function() {
        $('.popup-video, .popup-vimeo').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });
    });
    // $('.btn').on('click',function(){
    //      $('.pxp-preloader').show();
    //     window.location=window.location.href;      
    // });

    $("form").submit(function(){
        $('.pxp-preloader').show();
    });
</script>
<script>
    $('#reset').on('click',function(){
         $('.pxp-preloader').show();
        window.location=window.location.href.split('?')[0];
        // $('#cname').val('');
        // $('#email').val('');
        // $('#phone').val('');
         
        // $("#state")[0].selectize.clear();
        // $("#city")[0].selectize.clear();
        // $("#status")[0].selectize.clear();
        // location.reload();
    });
</script>
<script>
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<script>
      $(document).ready(function(){
        $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
        input.attr("type", "text");
        } else {
        input.attr("type", "password");
        }
        });

    })

    jQuery(function($){
        $('.footable').footable();
    });
 
    var test= $("select").selectize();
</script>
<script>
$("form").submit(function () {
    // prevent duplicate form submissions
    $(this).find(":submit").attr('disabled', 'disabled');
});
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$("#supportFormBtn").click(function () {
    $(".supportResponse").html('');
    $(".supportResponse").hide();
    $("#support").show();
    document.getElementById('support').reset();
    $(".text-danger").html('');


});
$("#supportBtn").click(function () {
    $(".supportResponse").hide();
    $(".text-danger").html('');
     
     $.ajax({
            type: 'POST',
            url: "{{url('/support')}}",
            // "_token": "{{ csrf_token() }}"+$('#myForm').serialize(),
            data:$('#support').serialize() + "&_token={{ csrf_token() }}",
            success: function (data) {
                //console.log(data);
                var errors = '';      
                if(data.status==false){
                    var errors = data.errors;                    
                    $.each(errors, function (key, val) {
                        //console.log(val[0]);
                        $("#" + key + "_error").html(val[0]);
                    });
                     
                }else{

                    $("#support").hide();
                    $(".supportResponse").show();
                    // $(".supportResponse").html('Thank You!');
                }
            },
             
        });
});
</script>
@stack('script')

$(document).ready(function(){
   // ===== Scroll to Top ==== 
    $(window).scroll(function() {
        if ($(this).scrollTop() >= 50) {       
            $('#return-to-top').fadeIn(200);   
        } else {
            $('#return-to-top').fadeOut(200);  
        }
    });
    $('#return-to-top').click(function() {     
        $('body,html').animate({
            scrollTop : 0                     
        }, 500);
    });

    function updateScroll() {
        if ($(window).scrollTop() >= 50) {
            $("header").addClass('menu_fixed');
        } else {
            $("header").removeClass("menu_fixed");
        }
    }
    
    $(function() {
        $(window).scroll(updateScroll);
        updateScroll();
    });  

    $(function() {
        $(window).scroll(updateScroll);
        updateScroll();
    });


    $('.nav-link-item').on('click', function(){
        $(".menu-block").removeClass('active');
        $(".menu-overlay").removeClass('active');
    });		
    
   
   });

   $(".accordion_head").click(function(){
    if ($('.accordion_body').is(':visible')) {
        $(".accordion_body").slideUp(300);
        $(".plusminus").html('<i class="fa fa-plus"></i>');
    }
    if( $(this).next(".accordion_body").is(':visible')){
        $(this).next(".accordion_body").slideUp(300);
        $(this).children(".plusminus").html('<i class="fa fa-plus"></i>');
    }else {
        $(this).next(".accordion_body").slideDown(300); 
        $(this).children(".plusminus").html('<i class="fa fa-minus"></i>');
    }
});



//loader
$(window).bind('load', function() {
    $('.overlay, body').addClass('loaded');
    setTimeout(function() {
        $('.overlay').css({'display':'none'})
    }, 2000)
});
 //loader




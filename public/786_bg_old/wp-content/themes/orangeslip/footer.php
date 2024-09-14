<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

		</div><!-- .site-content -->

        <footer class="pxp-main-footer mt-70">
    <div class="pxp-main-footer-top pt-50 pb-50" style="background-color: #e9e9e9;">
        <div class="pxp-container">
            <div class="row">
                <div class="col-lg-6 col-xl-5 col-xxl-4 mb-4">
                    <div class="pxp-footer-logo">
                        <a href="https://orangeslip.com" class="pxp-animate">
                            <img src="https://orangeslip.com/new/images/logo.png" alt="" />
                        </a>
                    </div>
                    <div class="pxp-footer-section mt-3 mt-md-4">
                        <h3 class="mb-2">Call us</h3>
                        <div class="pxp-footer-phone">+91 8800-138-139</div>
                    </div>
                    <div class="mt-3 mt-md-4 pxp-footer-section">
                        <div class="pxp-footer-text">
                       <p class="mb-1"><strong><i class="fa fa-map-marker"></i></strong> A-62, DDA Shed, Okhla Phase 2 110020</p>
                        <p><strong><i class="fa fa-map-marker"></i></strong> B-95, B Block Sector 2, Noida Uttar Pradesh 201301</p>
                            <p class="mt-3"><strong>info@orangeslip.com</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-7 col-xxl-8">
                    <div class="row">
                        <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
                            <div class="pxp-footer-section">
                                <h3>For Candidates</h3>
                                <ul class="pxp-footer-list">
                                    <li><a href="https://orangeslip.com/login">Login</a></li>
                                    <li><a href="https://orangeslip.com/register">Register</a></li>
                                   
                                  
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
                            <div class="pxp-footer-section">
                                <h3>For Employers</h3>
                                <ul class="pxp-footer-list">
                                    <li><a href="#">Find Candidates</a></li>
                                    <li><a href="#">Company Dashboard</a></li>
                                    <li><a href="#">Post a Job</a></li>
                                    <li><a href="#">Manage Jobs</a></li>
                                    <li> <a href="#">Candidates EMPILY</a></li>
                                    <li><a href="https://orangeslip.com/enroll_company">Enroll Your Company</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
                            <div class="pxp-footer-section">
                                <h3>Quick Links</h3>
                                <ul class="pxp-footer-list">
                                    <li><a href="https://orangeslip.com/about-us">About Us</a></li>
                                    <li><a href="https://orangeslip.com/pricing">Pricing</a></li>
                                    <li><a href="https://orangeslip.com/blog/">Blog</a></li>
                                    <!-- <li><a href="{{route('faq')}}">Faq</a></li> -->
                                    <li><a href="https://orangeslip.com/terms-conditions">Terms and Conditions</a></li>
                                    <li><a href="https://orangeslip.com/privacy-policy">Privacy and Policy</a></li>
                                    <li><a href="https://orangeslip.com/cookie-policy">Cookie Policy</a></li>
                                    <li><a href="https://orangeslip.com/contact">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
                            <div class="pxp-footer-section">
                                <h3>Services</h3>
                                <ul class="pxp-footer-list">
                                    <li><a href="https://orangeslip.com/employee-onboarding-software">On-boarding Hiring Process</a></li>
                                    <li><a href="https://orangeslip.com/Employee-KYC-verification">Employee KYC Verification</a></li>
                                    <li><a href="https://orangeslip.com/resume-building-services">Resume Building Services</a></li>                                    
                                    <li><a href="https://orangeslip.com/EMPILY-score">Candidate EMPILY Score</a></li>
                                   
                                   
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pxp-main-footer-bottom">
        <div class="pxp-container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-auto">
                    <div class="pxp-footer-copyright pxp-text-light">© <script>document.write(new Date().getFullYear())</script> Orangeslip. All Right Reserved.</div>
                </div>
                <div class="col-lg-auto">
                    <div class="pxp-footer-social mt-3 mt-lg-0">
                        <ul class="list-unstyled">
                            <li><a target="_blank" href="https://www.facebook.com/profile.php?id=61557879646976"><span class="fa fa-facebook"></span></a></li>
                            <li><a target="_blank" href="https://twitter.com/Orange_slip"><img style="width: 17px;" src="new/images/twitter.png" alt=""></a></li>
                            <li><a target="_blank" href="https://www.instagram.com/orange_slip/"><span class="fa fa-instagram"></span></a></li>
                            <li><a target="_blank" href="https://www.linkedin.com/company/orange-slip"><span class="fa fa-linkedin"></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

    <div class="overlay">
        <div class="overlayDoor"></div>
        <div class="overlayContent">
        <img  src="<?php echo get_template_directory_uri() . '/assets/img/logo.png'; ?>" alt="">
    
            <p style="color: #fff;">Loading.....</p>
        </div>
    </div>
 

	</div><!-- .site-inner -->
</div><!-- .site -->





<script src="<?php bloginfo('template_url'); ?>/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php bloginfo('template_url'); ?>/assets/js/owl.carousel.min.js"></script> 
<script src="<?php bloginfo('template_url'); ?>/assets/js/nav.js"></script>
<script src="<?php bloginfo('template_url'); ?>/assets/js/main.js"></script>


   <!-- <script  src="<?php bloginfo('template_url'); ?>/assets/js/jquery.min.js"></script>
    <script   src="<?php bloginfo('template_url'); ?>/assets/js/bootstrap.min.js"></script>
    <script  src="<?php bloginfo('template_url'); ?>/assets/js/owl.carousel.js"></script>
    <script   src="<?php bloginfo('template_url'); ?>/assets/js/custome.js"></script> -->
    <!-- <script>
        $('.navbar-nav a').click(function() {
            $('html, body').animate({
                scrollTop: $($(this).attr('href')).offset().top
            }, 500);
            return false;
        });
    </script> -->

    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <!-- <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script> -->

    <!-- TODO: Add SDKs for Firebase products that you want to use
    https://firebase.google.com/docs/web/setup#available-libraries -->



<?php wp_footer(); ?>
</body>
</html>

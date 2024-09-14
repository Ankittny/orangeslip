<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
	<?php endif; ?>


    
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/css/bootstrap.min.css?<?php echo (int)(microtime(true)*1000); ?>">
    <link rel="stylesheet"  href="<?php bloginfo('template_url'); ?>/assets/css/animate.css?<?php echo (int)(microtime(true)*1000); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet"  href="<?php bloginfo('template_url'); ?>/assets/css/footable.css?<?php echo (int)(microtime(true)*1000); ?>">
    <link rel="stylesheet"  href="<?php bloginfo('template_url'); ?>/assets/css/magnific-popup.css?<?php echo (int)(microtime(true)*1000); ?>">
    <link rel="stylesheet"  href="<?php bloginfo('template_url'); ?>/assets/css/fmasking-input.css?<?php echo (int)(microtime(true)*1000); ?>">
    <link rel="stylesheet"  href="<?php bloginfo('template_url'); ?>/assets/css/owl.carousel.min.css?<?php echo (int)(microtime(true)*1000); ?>">
    <link rel="stylesheet"  href="<?php bloginfo('template_url'); ?>/assets/css/owl.theme.default.min.css?<?php echo (int)(microtime(true)*1000); ?>">
    <link rel="stylesheet"  href="<?php bloginfo('template_url'); ?>/assets/css/selectize.css?<?php echo (int)(microtime(true)*1000); ?>">
    <link rel="stylesheet"  href="<?php bloginfo('template_url'); ?>/assets/css/style.css?<?php echo (int)(microtime(true)*1000); ?>">
    <link rel="stylesheet"  href="<?php bloginfo('template_url'); ?>/assets/css/toastr.css?<?php echo (int)(microtime(true)*1000); ?>">
  <style>
    .pxp-nav > ul > li > ul a .menu_icon {
    filter: brightness(0) invert(0);
    width: 25px;
    margin-right: 5px;
  </style>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<div class="site-inner">
		<a class="skip-link screen-reader-text" href="#content">
			<?php
			/* translators: Hidden accessibility text. */
			_e( 'Skip to content', 'twentysixteen' );
			?>
		</a>

		<!-- .site-header -->



		<div id="content" class="site-content">
        <header class="pxp-header fixed-top pxp-has-border pxp-is-sticky">
    <div class="pxp-container">
        <div class="pxp-header-container">
            <div class="pxp-logo">
                <a href="https://orangeslip.com" class="pxp-animate">
                    <img src="https://orangeslip.com/new/images/logo.png" alt="">
                </a>
            </div>
            <nav class="pxp-nav dropdown-hover-all mobile_none">
                <ul>
                    <li>
                        <a style="font-size: 25px;" href="https://orangeslip.com"><i class="fa fa-home"></i></a>
                    </li>
                    <li><a href="https://orangeslip.com/about-us">About</a></li>
                    <li><a href="https://orangeslip.com/EMPILY-score">EMPILY</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="https://orangeslip.com/employee-onboarding-software"><img src="https://orangeslip.com/new/images/icon1.png" alt="" class="menu_icon">On-boarding Hiring Process</a></li>
                            <li><a class="dropdown-item" href="https://orangeslip.com/Employee-KYC-verification"><img src="https://orangeslip.com/new/images/icon2.png" alt="" class="menu_icon">Employee KYC Verification</a></li>
                            <li><a class="dropdown-item" href="https://orangeslip.com/resume-building-services"><img src="https://orangeslip.com/new/images/icon7.png" alt="" class="menu_icon">Resume Building Services</a></li>
                            <li><a class="dropdown-item" href="https://orangeslip.com/EMPILY-score"><img src="https://orangeslip.com/new/images/icon4.png" alt="" class="menu_icon">Candidate EMPILY Score</a></li>
                        </ul>
                    </li> 
                    <li><a href="https://orangeslip.com/pricing">Pricing</a></li>
                    <li><a href="https://orangeslip.com/blog">Blog</a></li>
                    <li><a href="https://orangeslip.com/contact">Contact Us</a></li>
                        
                    
                </ul>
            </nav>
            <div class="mobile_none width-100"></div>
            <nav class="pxp-user-nav pxp-on-light d-sm-flex mobile_none">
                                    <a class="gradient_btn" href="https://orangeslip.com/login">Log In</a>
                    <a class="gradient_btn ms-2" href="https://orangeslip.com/register">Sign up</a>
                            </nav>
            <!--mobile menu-->
            <div class="pxp-nav-trigger navbar d-xl-none flex-fill">
                <a role="button" data-bs-toggle="offcanvas" data-bs-target="#pxpMobileNav" aria-controls="pxpMobileNav">
                    <div class="pxp-line-1"></div>
                    <div class="pxp-line-2"></div>
                    <div class="pxp-line-3"></div>
                </a>
                <div class="offcanvas offcanvas-start pxp-nav-mobile-container" tabindex="-1" id="pxpMobileNav">
                    <div class="offcanvas-header">
                        <div class="pxp-logo">
                            <a href="https://orangeslip.com" class="pxp-animate">
                                <img src="https://orangeslip.com/new/images/logo.png" alt="">
                            </a>
                        </div>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <nav class="pxp-nav-mobile">
                            <ul class="navbar-nav justify-content-end flex-grow-1">
                                <li class="nav-item">
                                    <a href="https://orangeslip.com" class="nav-link">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://orangeslip.com/about-us" class="nav-link">About</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="https://orangeslip.com/EMPILY-score">EMPILY</a></li>
                                <li class="nav-item dropdown">
                                    <a role="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                                    <ul class="dropdown-menu">
                                    <li class="nav-item"><a href="https://orangeslip.com/employee-onboarding-software">On-boarding Hiring Process</a></li>
                                    <li class="nav-item"><a href="https://orangeslip.com/Employee-KYC-verification">Employee KYC Verification</a></li>
                                    <li class="nav-item"><a href="https://orangeslip.com/resume-building-services">Resume Building Services</a></li>
                                    <li class="nav-item"><a href="https://orangeslip.com/EMPILY-score">Candidate EMPILY Score</a></li>                                       
                                    </ul>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="https://orangeslip.com/pricing">Pricing</a></li>
                                <li class="nav-item">
                                    <a href="https://orangeslip.com/blog" class="nav-link">Blog</a>
                                </li>
                                <li class="nav-item"><a href="https://orangeslip.com/contact" class="nav-link">Contact Us</a></li>
                                
                                

                                <li>
                                    <hr>
                                </li>
                                	
                                <li class="nav-item pxp-on-light">
                                    <a class="gradient_btn btn-block" href="https://orangeslip.com/login">Log In</a>
                                </li>
                                <li class="nav-item pxp-on-light mt-2">
                                    <a class="gradient_btn btn-block" href="https://orangeslip.com/register">Sign Up</a>
                                </li>
                                                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!--mobile menu-->
        </div>
    </div>
</header>
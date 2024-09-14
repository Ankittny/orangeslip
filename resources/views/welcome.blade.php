

<!doctype html>
<html lang="en" class="pxp-root">
 
@include('layouts.htmlheader')
 
 
<style>
    .pxp-header.pxp-has-border{border-bottom:none}
    .pxp-on-light .pxp-nav-btn:hover{color: #000 !important;}
    .pxp-on-light .pxp-user-nav-trigger {color: #fff; border: 1px solid #fff;}
    .pxp-on-light .pxp-user-nav-trigger:hover {background-color: #fff; color: var(--pxpMainColorDark);}
    .pxp-nav-btn:hover {color: var(--pxpMainColorDark) !important; background-color: #fff !important;}
    .pxp-is-sticky .pxp-nav-btn:hover{color: #fff !important; background-color: var(--pxpMainColorDark) !important;}
    .pxp-is-sticky .pxp-user-nav-trigger:hover{background-color: var(--pxpTextColor) !important; color: #fff !important;}
    .pxp-is-sticky .pxp-user-nav-trigger{color: var(--pxpTextColor); border: 1px solid var(--pxpTextColor);}


    .pxp-is-sticky .pxp-on-light .pxp-user-nav-name {color: #000;}
 </style>


    <body>
        
    
    <!-- <script type="text/javascript">toastr.info('Processing...', { fadeAway: 1000 });</script>  -->
        @include('layouts.homeheader')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
        @if (session('status'))
        <script type="text/javascript">toastr.success("{{session('status')}}")</script>                         
        @endif
        <section class="pxp-hero vh-100" style="background-color: var(--pxpMainColorLight);">
            <div class="pxp-hero-caption">
                <div class="pxp-container">
                    <div class="row pxp-pl-80 justify-content-between">
                        <div class="col-md-6 col-lg-6 col-sm-6">
                            <div class="pt-2s mt-3">
                                <h1>Hire Orangeslip to   <br><span style="color: var(--pxpprimaryColor);">Find Ideal</span> Candidate</h1>
                                <div class="pxp-hero-subtitle mt-3 mt-lg-4">Orangeslip is the reliable one-stop destination for any firm in terms of the seamless employee onboarding hiring process, employee KYC verifications, etc. Many businesses these days frequently inquire about the legitimacy and job qualifications of candidates. And, with our services, those queries will be no more. Powered by Blockchain, this platform guarantees to give you a comprehensive review, including the employee's personal details evaluation, KYC verification, and EMPILY score. Thus, no need to stress out about the candidate's job credentials anymore while hiring. </div>
                                <div class="pxp-info-caption-cta mt-4">
                                    <a href="{{route ('enrollCompanyView')}}" class="btn rounded-pill pxp-section-cta">Enroll Your Company<span class="fa fa-angle-right"></span></a>
                                    <a href="https://calendly.com/orangeslip/onboarding-software-demo" target="_blank" class="btn rounded-pill pxp-section-cta ms-2">Book Demo<span class="fa fa-angle-right"></span></a>
                                    {{--<a  data-bs-toggle="modal" data-bs-target="#book_demo_modal" href="#" class="btn rounded-pill pxp-section-cta ms-2">Book Demo<span class="fa fa-angle-right"></span></a>--}}
                                    
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-5 col-lg-5 col-sm-6 position-relative mobile_none">
                            <div class="pxp-hero-cards-container pxp-animate-cards pxp-mouse-move" data-speed="160">
                                <div class="pxp-hero-card pxp-cover pxp-cover-top" style="background-image: url(new/images/banner_img.jpg);"></div>
                                <div class="pxp-hero-card-dark"></div>
                                <div class="pxp-hero-card-light"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="pxp-hero-right-bg-card pxp-has-animation mobile_none"></div>
        </section>

        <!-- <div class="modal fade" id="book_demo_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="pxp-contact-us-form">
                            <h2 class="pxp-section-h2 text-center">Contact Us</h2>
                            <form class="mt-4" action="{{route('contactUs')}}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="contact-us-name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="contact_us_name" name="contact_us_name" placeholder="Enter your name">
                                    @if ($errors->has('contact_us_name'))
                                        <label class="text-danger">{{ $errors->first('contact_us_name') }}</label>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="contact-us-email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="contact_us_email" name="contact_us_email" placeholder="Enter your email address">
                                    @if ($errors->has('contact_us_email'))
                                        <label class="text-danger">{{ $errors->first('contact_us_email') }}</label>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="contact-us-message" class="form-label">Message</label>
                                    <textarea class="form-control" id="contact_us_message" name="contact_us_message" placeholder="Type your message here..."></textarea>
                                    @if ($errors->has('contact_us_message'))
                                        <label class="text-danger">{{ $errors->first('contact_us_message') }}</label>
                                    @endif
                                </div>
                                <button type="submit" class="btn rounded-pill pxp-section-cta btn-block d-block">Send Message</button>
                                
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div> -->
        

        <section class="mt-60">
            <div class="pxp-container">
                <p>Our advanced technology and expertise will follow the procedure and help you find a perfect candidate, isn't it great! Also, with this platform you can generate an offer letter to a desired candidate. So, by enrolling on our platform, you can put an end to your concerns about finding the right applicant.</p>
                <p>With Orangeslip's assistance, improve your employee onboarding process of hiring without a hassle. All you need to do is register your company on our platform. Our site is built using safety measures, guaranteeing that all databases are secure and don't get interfered with by any third party.</p>
                <p>This platform can act as an intermediary for both the employer and the applicant. In addition to any firm's request for a candidate's profile to be verified, applicants can share their opinion on a company here. Orangeslip can be a reliable option for all the companies out there to get genuine verified details of an applicant. In this way, we can be a trusted service provider in the onboarding candidate hiring process.</p>
            </div>
        </section>

        <section class="mt-60">
            <div class="pxp-container">
                <div class="heading-container">
                    <h2 class="pxp-section-h2 text-center">Orangeslip can be the Mediator Between Businesses and Candidates.</h2>
                    <p class="pxp-text-light text-center">Utilize Orangeslip's most reliable database, which integrates with Blockchain, to hire verified candidates. </p>
                </div>
                <div class="row justify-content-evenly mt-4 mt-md-5 pxp-animate-in pxp-animate-in-top">
                    
                    <div class="col-lg-4 col-xl-3 pxp-services-1-item-container">
                        <div class="pxp-services-1-item text-center pxp-animate-icon">
                            <div class="pxp-services-1-item-icon">
                                <img src="new/images/service-2-icon.png" alt="Employer">
                            </div>
                            <div class="pxp-services-1-item-title">On-boarding Process </div>
                            <div class="pxp-services-1-item-text pxp-text-light">We can help you generate offer letters to desired candidates besides keeping the candidates resume in the data record for the future onboarding hiring process.</div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 pxp-services-1-item-container">
                        <div class="pxp-services-1-item text-center pxp-animate-icon">
                            <div class="pxp-services-1-item-icon">
                                <img src="new/images/service-3-icon.png" alt="Press">
                            </div>
                            <div class="pxp-services-1-item-title">Employee KYC Verification</div>
                            <div class="pxp-services-1-item-text pxp-text-light">Orangeslip offers employees KYC verification services and ensures that you hire the right candidate. With this, you will be assured of the authenticity of the candidate's overall details. </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 pxp-services-1-item-container">
                        <div class="pxp-services-1-item text-center pxp-animate-icon">
                            <div class="pxp-services-1-item-icon">
                                <img src="new/images/service-1-icon.png" alt="Candidate">
                            </div>
                            <div class="pxp-services-1-item-title">Resume Building Services </div>
                            <div class="pxp-services-1-item-text pxp-text-light">Orangeslip also helps candidates in creating an outstanding resume.Our experts will provide a well-formatted resume and help you impress the recruiters right away. </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-3 pxp-services-1-item-container">
                        <div class="pxp-services-1-item text-center pxp-animate-icon">
                            <div class="pxp-services-1-item-icon">
                                <img src="new/images/service-4-icon.png" alt="Candidate">
                            </div>
                            <div class="pxp-services-1-item-title">EMPILY Score </div>
                            <div class="pxp-services-1-item-text pxp-text-light">We generate the EMPILY score for each candidate following their previous job experience, employer ratings, skills, etc. And that's what makes us unique from others. </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-5">
            <div class="pxp-container">
                <div class="heading-container">
                    <div class="video_inner_box">
                        <img class="video_img" src="/new/images/video_bg.png" alt="">
                        <div class="vodeo_holder">
                            <div class="video-main">
                                <div class="promo-video">
                                    <div class="waves-block">
                                        <div class="waves wave-1"></div>
                                        <div class="waves wave-2"></div>
                                        <div class="waves wave-3"></div>
                                    </div>
                                </div>
                                <a class="popup-video video" href="https://www.youtube.com/watch?v=fb1KslyJlco"><i class="fa fa-play"></i></a>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>


        <section class="mt-70">
            <div class="pxp-container">
                <div class="row justify-content-between align-items-center mt-4 mt-md-5">
                    <div class="col-lg-6 col-xxl-5">
                        <div class="pxp-info-fig pxp-animate-in pxp-animate-in-right">
                            <div class="pxp-info-fig-image pxp-cover" style="background-image: url(new/images/about-2.jpg);"></div>
                            
                        </div>
                    </div>
                    <div class="col-lg-6 col-xxl-6">
                        <div class="pxp-info-caption pxp-animate-in pxp-animate-in-top mt-4 mt-sm-5 mt-lg-0">
                            <h2 class="pxp-section-h2">Hiring a Desirable Applicant is Easy NOW with Orangeslip.</h2>
                            <p class="pxp-text-light">Orangeslip offers a range of services to meet all of your candidate recruiting requirements. Moreover, we'll add the cherry on top for you by verifying the candidates to make sure you can select the best ones. Here are the features that you can enjoy our services---- </p>
                            <div class="pxp-info-caption-list mt-4">
                                <div class="pxp-info-caption-list-item">
                                    <img src="new/images/check.svg" alt="-"><span>Relaxation from candidate searching. </span>
                                </div>
                                <div class="pxp-info-caption-list-item">
                                    <img src="new/images/check.svg" alt="-"><span>KYC verified the candidate's shared documents. </span>
                                </div>
                                <div class="pxp-info-caption-list-item">
                                    <img src="new/images/check.svg" alt="-"><span>Clear vision about the candidate's past job experience with EMPILY score. </span>
                                </div>
                                <div class="pxp-info-caption-list-item">
                                    <img src="new/images/check.svg" alt="-"><span>Generate an offer letter on your behalf. </span>
                                </div>
                            </div>
                            <div class="pxp-info-caption-cta">
                                <a href="{{route ('enrollCompanyView')}}" class="btn rounded-pill pxp-section-cta">Get Started Now<span class="fa fa-angle-right"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-70">
            <div class="pxp-container">
                <div class="row justify-content-between align-items-end">
                    <div class="col-auto">
                        <h2 class="pxp-section-h2">Connecting millions through Blockchain</h2>
                        <p class="pxp-text-light">Millions of up-to-date CV listings connecting you directly to candidates.</p>
                    </div>
                </div>

                <div class="pxp-categories-carousel1 owl-carousel1 mt-4 mt-md-5 pxp-animate-in pxp-animate-in-top">
                    
                <div class="slide_box_main">
                    <div class="slide_box_row">
                        <div class="slide_box_item">
                            <div class="pxp-categories-card-2" style="background-color: #fe8b10;">
                                <img src="/new/images/icon1.png?df" alt="" class="icon">
                                <div class="pxp-categories-card-2-title">All Services</div>
                                <div class="pxp-categories-card-2-subtitle one_line_more">All Services.</div>
                                <div class="pxp-services-1-item-cta mt-3">
                                    </br>
                                    {{--<a href="#">Read more<span class="fa fa-angle-right"></span></a>--}}
                                </div>
                            </div>
                        </div>
                        <div class="slide_box_item">
                            <div class="pxp-categories-card-2">
                                <img src="/new/images/icon1.png?df" alt="" class="icon">
                                <div class="pxp-categories-card-2-title">On-boarding Hiring Process</div>
                                <div class="pxp-categories-card-2-subtitle one_line_more">The quest to find a suitable candidate to fit the job role is essential.</div>
                                <div class="pxp-services-1-item-cta mt-3">
                                    <a href="{{route('onbording')}}">Read more<span class="fa fa-angle-right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="slide_box_item">
                            <div class="pxp-categories-card-2">
                                <img src="/new/images/icon2.png" alt="" class="icon">
                                <div class="pxp-categories-card-2-title">Employee KYC verification</div>
                                <div class="pxp-categories-card-2-subtitle one_line_more">It is a fact that only skills and experience aren't enough nowadays to hire a candidate.</div>
                                <div class="pxp-services-1-item-cta mt-3">
                                    <a href="{{route('KYC-verification')}}">Read more<span class="fa fa-angle-right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="slide_box_item">
                            <div class="pxp-categories-card-2">
                                <img src="/new/images/icon7.png" alt="" class="icon">
                                <div class="pxp-categories-card-2-title">Resume Building Services </div>
                                <div class="pxp-categories-card-2-subtitle one_line_more">A resume plays a crucial part in job hiring. It is an undenying fact that a recruiter spends</div>
                                <div class="pxp-services-1-item-cta mt-3">
                                    <a href="{{route('resume-builder')}}">Read more<span class="fa fa-angle-right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="slide_box_item">
                            <div class="pxp-categories-card-2">
                                <img src="/new/images/icon4.png" alt="" class="icon">
                                <div class="pxp-categories-card-2-title">Candidate EMPILY Score</div>
                                <div class="pxp-categories-card-2-subtitle one_line_more">When you run a business or an organization, it is important to evaluate several</div>
                                <div class="pxp-services-1-item-cta mt-3">
                                    <a href="{{route('EMPILY-score')}}">Read more<span class="fa fa-angle-right"></span></a>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="slide_box_item">
                            <div class="pxp-categories-card-2">
                                <img src="/new/images/icon3.png" alt="" class="icon">
                                <div class="pxp-categories-card-2-title">Blockchain Development</div>
                                <div class="pxp-categories-card-2-subtitle one_line_more">Orangeslip is powered by Blockchain and thus it is the trusted platform for EMPILY data</div>
                                <div class="pxp-services-1-item-cta mt-3">
                                    <a href="{{route('blockchain-development')}}">Read more<span class="fa fa-angle-right"></span></a>
                                </div>
                            </div>
                        </div> -->
                        <div class="slide_box_item">
                            <div class="pxp-categories-card-2">
                                <img src="/new/images/icon6.png" alt="" class="icon">
                                <div class="pxp-categories-card-2-title">Candidate Information Verification</div>
                                <div class="pxp-categories-card-2-subtitle one_line_more">KYC, Personal and Professional Information</div>
                                <div class="pxp-services-1-item-cta mt-3">
                                    <a href="#">Read more<span class="fa fa-angle-right"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
            </div>
        </section>

        <section class="mt-70">
            <div class="pxp-container">
                <div class="pxp-promo-img pxp-cover pt-100 pb-100 pxp-animate-in pxp-animate-in-top" style="background-image: url(new/images/promo-img-bg.jpg);">
                    <div class="row">
                        <div class="col-sm-7 col-lg-6">
                            <h2 class="pxp-section-h2">Enjoy Stress-free Candidate Search with Our Service. </h2>
                            <p class="pxp-text-light">Orangeslip streamlines the hiring processes of your organization. The platform is the one-stop solution for your firm. So, whether you need to hire employees, do a profile verification, or review candidates with an EMPILY score, we can be your ultimate option.  </p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-70">
            <div class="pxp-container">
                <div class="row justify-content-center">

                     <div class="col-xl-5 col-xxl-5">
                        <div class="rec-off-img">
                            <img class="img-fluid" src="new/images/recruiter-img2.jpg" alt="">
                            
                        </div>
                    </div>

                    <div class="col-xl-7 col-xxl-7">

                        <div class="rec-off-content">
                        <h2 class="pxp-section-h2">Why Choose Us?</h2>
                       
                        <div class="text-justify">
                            <p>Orangeslip’s flexible approach helps better serve a concise yet comprehensive history of an individual’s skills and experience that the hiring manager can go through quickly. Here are the more factors that might convince you to hire our services----</p>
                        </div>
                        <ul>
                            <li>
                                <h5>Confidential---</h5>
                                <p>
                                The candidate’s information is stored securely and not accessible to one without permission. The resume verification process would be factual, trusted, and objective, preventing one from counterfeiting information.
                                </p>
                            </li>
                            <li>
                                <h5>Data Security---</h5>
                                <p>
                                Orangeslip constantly guarantees to provide the highest level of data security, enabling businesses to depend on technology and achieve greater efficiency with appropriate access to private candidate data. With our service, you can be certain that there won't be any third-party breaches.
                                </p>
                            </li>
                            <li>
                                <h5>Improved Efficiency---</h5>
                                <p>
                                We use blockchain technology to store and provide access to the data of candidates hired. Moreover, if you want to verify the applicants; we will do it for you. In this way, we provide easy accessibility with improved efficiency, saving time for your candidate hiring. 
                                </p>
                            </li>
                           
                        </ul>
                        
                     </div>
                    </div>
                </div>
                
            </div>
        </section>
        <section class="pt-5 pb-5 mt-70 mission-vision gradient_bg">
            <div class="pxp-container">
                <!-- <div class="row">
                    <div class="col-md-5 text-left">
                        <div class="text-block text-white">
                            <p>How To Connect?</p>
                            <h3>Join us to hire a verified candidate.</h3>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="smal-icons">
                            <div class="icon-bom">
                                <span class="badge">1</span>
                                <i class="fa fa-user-plus"></i>
                            </div>
                            <p>Create Your Profile</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="smal-icons">
                            <div class="icon-bom">
                                <span class="badge">2</span>
                                <i class="fa fa-check"></i>
                            </div>
                            <p>Choose your desired service</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="smal-icons">
                            <div class="icon-bom">
                                <span class="badge">3</span>
                                <i class="fa fa-search"></i>
                            </div>
                            <p>Get started with Orangeslip</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-5">
            <div class="pxp-container">
                <div class="row">
                    <div class="col-md-7 col-lg-7">
                        <h2 class="pxp-section-h2">Download Orangeslip NOW to Enjoy Our Excellent Features</h2>
                        <p class="pxp-text-light">Orangeslip is accessible in both operating systems, whether you use, Android or IoS. Simply go to the Apple Store or Google Play store and download the app RIGHT NOW. To enjoy seamless features, we have a separate app for Employers, HR, and candidates. Follow the link here as per your preference---</p>

                        <div class="app_text mt-4">
                            <div class="app_text_inner">
                                <h4>For Admin</h4>
                                <ul class="app_ul">
                                    <li><a href="#"><img src="new/images/play-store.png" alt=""></a></li>
                                    <li><a href="#"><img src="new/images/app-store.png" alt=""></a></li>
                                </ul>
                            </div>
                            <div class="app_text_inner">
                                <h4>For HR</h4>
                                <ul class="app_ul">
                                    <li><a href="#"><img src="new/images/play-store.png" alt=""></a></li>
                                    <li><a href="#"><img src="new/images/app-store.png" alt=""></a></li>
                                </ul>
                            </div>
                            <div class="app_text_inner">
                                <h4>For Candidates</h4>
                                <ul class="app_ul">
                                    <li><a href="#"><img src="new/images/play-store.png" alt=""></a></li>
                                    <li><a href="#"><img src="new/images/app-store.png" alt=""></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-5">
                        <div class="mobile_app-img">
                            <img class="img-fluid" src="new/images/mobile_app.png?ff" alt="">
                        </div>
                    </div>
                </div>

               
            </div>
        </section>

       

        {{--<section class="mt-70">
            <div class="pxp-container">
                <h2 class="pxp-section-h2 text-center">Receive the latest updates!</h2>
                <p class="pxp-text-light text-center">Create your profile now! For the best experience on job searches.</p>

                <div class="row mt-4 mt-md-5 justify-content-center">
                    <div class="col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                        <div class="pxp-subscribe-1-container pxp-animate-in pxp-animate-in-top">
                            <div class="pxp-subscribe-1-image">
                                <img src="/new/images/subscribe.png" alt="e">
                            </div>
                            <div class="pxp-subscribe-1-form">
                                <form>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Enter your email...">
                                        <button class="btn btn-primary" type="button">Subscribe<span class="fa fa-angle-right"></span></button>
                                      </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>--}}

        @include('layouts.homefooter')


        @include('layouts.script')
       

    </body>

</html>









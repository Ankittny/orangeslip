<!doctype html>
<html lang="en" class="pxp-root">
    
@include('layouts.htmlheader')

    <body>
        
    @include('layouts.homeheader')
    @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
        <section class="mt-60 pxp-no-hero">
            <div class="pxp-container">
                <h2 class="pxp-section-h2 text-center">We'd love to hear from you</h2>
                <p class="pxp-text-light text-center">Get in touch with us</p>

                <div class="row mt-4 mt-md-4 justify-content-center pxp-animate-in pxp-animate-in-top">
                    <div class="col-lg-4 col-xxl-3 pxp-contact-card-1-container">
                        <a href="#" class="pxp-contact-card-1">
                            <div class="pxp-contact-card-1-icon-container">
                                <div class="pxp-contact-card-1-icon">
                                    <span class="fa fa-map-marker"></span>
                                </div>
                            </div>
                            <div class="pxp-contact-card-1-title">A-62, DDA Shed, Okhla Phase 2</div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-xxl-3 pxp-contact-card-1-container">
                        <a href="#" class="pxp-contact-card-1">
                            <div class="pxp-contact-card-1-icon-container">
                                <div class="pxp-contact-card-1-icon">
                                    <span class="fa fa-phone"></span>
                                </div>
                            </div>
                            <div class="pxp-contact-card-1-title">+91 9811 920 377</div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-xxl-3 pxp-contact-card-1-container">
                        <a href="#" class="pxp-contact-card-1">
                            <div class="pxp-contact-card-1-icon-container">
                                <div class="pxp-contact-card-1-icon">
                                    <span class="fa fa-envelope-o"></span>
                                </div>
                            </div>
                            <div class="pxp-contact-card-1-title">info@orangeslip.com</div>
                        </a>
                    </div>
                </div>

                <div class="row mt-60 justify-content-center pxp-animate-in pxp-animate-in-top">
                    <div class="col-lg-6 col-xxl-4">
                        <div class="pxp-contact-us-form pxp-has-animation pxp-animate">
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
        </section>

        @include('layouts.homefooter')


        @include('layouts.script')

    </body>

</html>
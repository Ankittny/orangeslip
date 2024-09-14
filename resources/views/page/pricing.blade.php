<!doctype html>
<html lang="en" class="pxp-root">
    
@include('layouts.htmlheader')

    <body>

        @include('layouts.homeheader')
       
        <section class="mt-60 pxp-no-hero">
            <div class="pxp-container">
                <h2 class="pxp-section-h2">Platform Pricing</h2>
                <p class="pxp-text-light">Choose the plan that suits you best.</p>

                <div class="row pxp-animate-in pxp-animate-in-top">
                    @foreach($allPack as $pack)
                    <div class="col-md-6 col-xl-4 col-xxl-4 pxp-plans-card-1-container">
                        <div class="pxp-plans-card-1">
                            <div class="pxp-plans-card-1-top">
                                @if($pack->duration==365)<div class="pxp-plans-card-1-featured-label">Recommended</div>@endif
                                <div class="pxp-plans-card-1-title">{{$pack->pack_name}} Plan</div>
                                <div class="pxp-plans-card-1-price mb-4">
                                    <div class="pxp-plans-price-monthly">
                                        <span class="fa fa-inr"></span>{{$pack->offer_price}}
                                        <h5 class="text-danger">Price: <del>{{$pack->price}}</del></h5>
                                    </div>
                                </div>
                               
                                        <p>Validity: {{$pack->duration}} Days</p>
                                        <p>{{$pack->quantity}} Offer Letter</p>
                                <!-- <div class="pxp-plans-card-1-list">
                                    <p>Verifying the candidate’s identity and personal information building trust within the recruitment and hiring process. </p>
                                </div> -->
                            </div>
                            <!-- <div class="pxp-plans-card-1-bottom">
                                <div class="pxp-plans-card-1-cta">
                                    <a href="#" class="btn rounded-pill pxp-card-btn">Choose Plan</a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            </div>
        </section>
        <section class="pt-4 pxp-no-hero">
            <div class="pxp-container">
                <h2 class="pxp-section-h2">KYC Pricing</h2>
                <p class="pxp-text-light">Choose the plan that suits you best.</p>

                <div class="row pxp-animate-in pxp-animate-in-top">
                    @foreach($allKyc as $kyc)
                    <div class="col-md-6 col-xl-4 col-xxl-4 pxp-plans-card-1-container">
                        <div class="pxp-plans-card-1">
                            <div class="pxp-plans-card-1-top">
                                
                                <div class="pxp-plans-card-1-title">{{$kyc->title}} Verification</div>
                                <div class="pxp-plans-card-1-price mb-4">
                                    <div class="pxp-plans-price-monthly">
                                        <span class="fa fa-inr"></span>{{$kyc->amount}}
                                       
                                    </div>
                                </div>
                               
                                       
                                   
                                 <div class="pxp-plans-card-1-list">
                                    <p>Verifying the candidate’s identity and personal information building trust within the recruitment and hiring process. </p>
                                </div> 
                            </div>
                            <!-- <div class="pxp-plans-card-1-bottom">
                                <div class="pxp-plans-card-1-cta">
                                    <a href="#" class="btn rounded-pill pxp-card-btn">Choose Plan</a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            </div>
        </section>
        @include('layouts.homefooter')


        @include('layouts.script')

    </body>

</html>
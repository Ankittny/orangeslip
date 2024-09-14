<header class="pxp-header fixed-top pxp-has-border">
    <div class="pxp-container">
        <div class="pxp-header-container">
            <div class="pxp-logo">
                <a href="{{url('/')}}" class="pxp-animate">
                    <img src="/new/images/logo.png" alt="" />
                </a>
            </div>
            <nav class="pxp-nav dropdown-hover-all mobile_none">
                <ul>
                    <li>
                        <a style="font-size: 25px;" href="{{url('/')}}"><i class="fa fa-home"></i></a>
                    </li>
                    <li><a href="{{route('about')}}">About</a></li>
                    <li><a href="{{route('EMPILY-score')}}">EMPILY</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{route('onbording')}}"><img src="/new/images/icon1.png" alt="" class="menu_icon">On-boarding Hiring Process</a></li>
                            <li><a class="dropdown-item" href="{{route('KYC-verification')}}"><img src="/new/images/icon2.png" alt="" class="menu_icon">Employee KYC Verification</a></li>
                            <li><a class="dropdown-item" href="{{route('resume-builder')}}"><img src="/new/images/icon7.png" alt="" class="menu_icon">Resume Building Services</a></li>
                            <li><a class="dropdown-item" href="{{route('EMPILY-score')}}"><img src="/new/images/icon4.png" alt="" class="menu_icon">Candidate EMPILY Score</a></li>
                        </ul>
                    </li>    
                    <li><a href="{{route('pricing')}}">Pricing</a></li>
                    <li><a href="{{route('blog')}}">Blog</a></li>
                    <li><a href="{{route('contact')}}">Contact Us</a></li> 

                      
                </ul>
                
            </nav>
            <div class="mobile_none width-100"></div>
            <nav class="pxp-user-nav pxp-on-light d-sm-flex mobile_none">
                @guest
                    <a class="gradient_btn" href="{{ route('login') }}">Log In</a>
                    <a class="gradient_btn ms-2"  href="{{ route('register') }}">Sign up</a>
                @else
                <div class="dropdown pxp-user-nav-dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="pxp-user-nav-avatar pxp-cover" style="background-image: url({{ (Auth::user()->profile->avatar!='')?(url('images/'.Auth::user()->profile->avatar)):(url('/new/images/noimage.png')) }});"></div>
                        <div class="pxp-user-nav-name d-md-block">{{Auth::user()->business?Auth::user()->business->business_name : Auth::user()->first_name}}</div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{url('edit_profile')}}/{{Auth::user()->id}}">Edit profile</a></li>
                        @if(Session::get('adminLogin') == true)
                        <li><a class="dropdown-item" href="{{ url('login_as_admin') }}">Back to Administrator</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                    </ul>
                </div>
                @endif
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
                            <a href="{{url('/')}}" class="pxp-animate">
                                <img src="new/images/logo.png" alt="">
                            </a>
                        </div>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <nav class="pxp-nav-mobile">
                            <ul class="navbar-nav justify-content-end flex-grow-1">
                                <li class="nav-item">
                                    <a href="{{url('/')}}" class="nav-link">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('about')}}" class="nav-link">About</a>
                                </li>
                                <li class="nav-item"><a class="nav-link"href="{{route('EMPILY-score')}}">EMPILY</a></li>
                                <li class="nav-item dropdown">
                                    <a role="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                                    <ul class="dropdown-menu">
                                    <li class="nav-item"><a href="{{route('onbording')}}">On-boarding Hiring Process</a></li>
                                    <li class="nav-item"><a href="{{route('KYC-verification')}}">Employee KYC Verification</a></li>
                                    <li class="nav-item"><a href="{{route('resume-builder')}}">Resume Building Services</a></li>
                                    <li class="nav-item"><a href="{{route('EMPILY-score')}}">Candidate EMPILY Score</a></li>

                                       
                                    </ul>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('pricing')}}">Pricing</a></li>
                                <li class="nav-item">
                                    <a href="{{route('blog')}}" class="nav-link">Blog</a>
                                </li>
                               
                                {{--<li class="nav-item"><a class="nav-link" href="{{route('faq')}}">FAQs</a></li>--}}
                                <li class="nav-item"><a class="nav-link" href="{{route('contact')}}">Contact Us</a></li>
                               

                                
                                <li>
                                    <hr>
                                </li>
                                @guest	
                                <li class="nav-item pxp-on-light">
                                    <a class="gradient_btn btn-block" href="{{ route('login') }}" >Log In</a>
                                </li>
                                <li class="nav-item pxp-on-light mt-2">
                                    <a class="gradient_btn btn-block" href="{{ route('register') }}">Sign Up</a>
                                </li>
                                @else
                                <nav class="pxp-user-nav pxp-on-light d-xl-none">
                                    <div class="dropdown pxp-user-nav-dropdown">
                                        <a href="javascript:void(0)" class="dropdown-toggle" data-bs-toggle="dropdown">
                                            <div class="pxp-user-nav-avatar pxp-cover" style="background-image: url({{ (Auth::user()->profile->avatar!='')?(url('images/'.Auth::user()->profile->avatar)):(url('/new/images/noimage.png')) }});"></div>
                                            <div class="pxp-user-nav-name">{{Auth::user()->first_name}}</div>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                            <li><a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                                        </ul>
                                    </div>
                                </nav>
                                
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!--mobile menu-->
        </div>
    </div>
</header>



<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
	{{ csrf_field() }}
</form>


               
        
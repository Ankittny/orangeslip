
<div class="pxp-dashboard-side-panel d-none d-lg-block">
        <div class="pxp-logo">
            <a href="/" class="pxp-animate">
                <img src="{{asset('new/images/logo.png')}}" alt="">
            </a>
        </div>
    <nav class="mt-3 mt-lg-4 d-flex justify-content-between flex-column">
        <ul class="list-unstyled">
            <li class="{{ request()->is('dashboard*') ? 'pxp-active' : '' }}"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            @can("access-manage-business")
            @if(Auth::user()->account_type!='lead staff')
            <li class="nav-item dropdown {{ request()->is('business/create') || request()->is('business') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Business</a>
                <ul class="dropdown-menu {{ request()->is('business/create') || request()->is('business') ? 'show' : '' }}">
                    <li class="dropdown-item {{ request()->is('business/create') ? 'active' : '' }}"><a href="{{ route('business.create') }}">Add Business</a></li>
                    <li class="dropdown-item {{  request()->is('business')? 'active' : '' }}"><a href="{{route('business.index')}}">Manage Business</a></li>                     
                </ul>
            </li>
            @endif
            @endcan

            @can("access-manage-hr-list")
            <li class="nav-item dropdown {{ request()->is('addHr') || request()->is('hr_list') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">HR</a>
                <ul class="dropdown-menu {{ request()->is('addHr') || request()->is('hr_list') ? 'show' : '' }}">
                    <li class="dropdown-item {{ request()->is('addHr')  ? 'active' : '' }}"><a href="{{ route('add_hr') }}">Add HR</a></li>
                    <li class="dropdown-item {{ request()->is('hr_list') ? 'active' : '' }}"><a href="{{route('hr_list')}}">Manage HR</a></li>
                </ul>
            </li>
            @endcan

            @can('access-manage-candidate')
            <li class="nav-item dropdown {{ request()->is('registration') || request()->is('candidate_list') || request()->is('bulk_upload') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle " data-bs-toggle="dropdown">Candidate</a>
                <ul class="dropdown-menu {{ request()->is('registration') || request()->is('candidate_list') || request()->is('bulk_upload') ? 'show' : '' }}">
                    @if(Auth::user()->chkUserAccess(1))
                    <li class="dropdown-item {{ request()->is('registration') ? 'active' : '' }}"><a href="{{route('registration')}}">Add Candidate</a></li>
                    @endif
                    <li class="dropdown-item {{ request()->is('candidate_list') ? 'active' : '' }}"><a href="{{route('candidate_list')}}">Manage Candidate</a></li>
                    @if((Auth::user()->account_type=='hr') && (Auth::user()->chkUserAccess(3)))
                     
                    <li class="dropdown-item {{ request()->is('bulk_upload') ? 'active' : '' }}"><a href="{{route('bulk_upload')}}">Bulk Upload</a></li>                    
                    @endif
                </ul>
            </li>
            @endcan

            @can("access-manage-lead-head")
            <li class="nav-item dropdown {{ request()->is('add_lead_head') || request()->is('lead_head_list') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Lead Head</a>
                <ul class="dropdown-menu {{ request()->is('add_lead_head') || request()->is('lead_head_list') ? 'show' : '' }}">
                    <li class="dropdown-item {{ request()->is('add_lead_head')  ? 'active' : '' }}"><a href="{{ route('addLeadHead') }}">Add Lead Head</a></li>
                    <li class="dropdown-item {{ request()->is('lead_head_list') ? 'active' : '' }}"><a href="{{route('leadHeadList')}}">Manage Lead Head</a></li>
                </ul>
            </li>
            @endcan  

            @can("access-manage-lead-staff")
            <li class="nav-item dropdown {{ request()->is('add_lead_staff') || request()->is('lead_staff_list') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Lead Staff</a>
                <ul class="dropdown-menu {{ request()->is('add_lead_staff') || request()->is('lead_staff_list') ? 'show' : '' }}">
                    <li class="dropdown-item {{ request()->is('add_lead_staff')  ? 'active' : '' }}"><a href="{{ route('addLeadStaff') }}">Add Lead Staff</a></li>
                    <li class="dropdown-item {{ request()->is('lead_staff_list') ? 'active' : '' }}"><a href="{{route('leadStaffList')}}">Manage Lead Staff</a></li>
                </ul>
            </li>
            @endcan        
            
            @can('access-manage-verification-head')
            <li class="nav-item dropdown {{ request()->is('verification_head') || request()->is('verification_head_list') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Verification Head</a>
                <ul class="dropdown-menu {{ request()->is('verification_head') || request()->is('verification_head_list') ? 'show' : '' }}">
                    <li class="dropdown-item {{ request()->is('verification_head')  ? 'active' : '' }}"><a href="{{ route('addVerificationHead') }}">Add Verification Head</a></li>
                    <li class="dropdown-item {{ request()->is('verification_head_list') ? 'active' : '' }}"><a href="{{route('verificationHeadList')}}">Manage Verification Head</a></li>
                </ul>
            </li>
            @endcan
            @can('access-manage-verification-staff')
            <li class="nav-item dropdown {{ request()->is('verification_staff') || request()->is('verification_staff_list') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Verification Staff</a>
                <ul class="dropdown-menu {{ request()->is('verification_staff') || request()->is('verification_staff_list') ? 'show' : '' }}">
                    <li class="dropdown-item {{ request()->is('verification_staff')  ? 'active' : '' }}"><a href="{{ route('addVerificationStaff') }}">Add Verification Staff</a></li>
                    <li class="dropdown-item {{ request()->is('verification_staff_list') ? 'active' : '' }}"><a href="{{route('verificationStaffList')}}">Manage Verification Staff</a></li>
                </ul>
            </li>
            @endcan

            @can("access-manage-lead")
            <li class="nav-item dropdown {{ request()->is('enroll_list') || request()->is('assign_enroll_lead') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Enrolled Business List</a>
                <ul class="dropdown-menu {{ request()->is('enroll_list') || request()->is('assign_enroll_lead') ? 'show' : '' }}">
                    <li class="dropdown-item {{ request()->is('enroll_list')  ? 'active' : '' }}"><a href="{{ route('enrollList') }}">Manage Enrolled Business</a></li>
                    @if(Auth::user()->account_type!='lead staff')
                    <li class="dropdown-item {{ request()->is('assign_enroll_lead') ? 'active' : '' }}"><a href="{{route('assignBusLead')}}">Assign Enrolled Business</a></li>
                    @endif
                </ul>
            </li>             
            @endcan

            @can('access-manage-candidate')
            <li class="nav-item dropdown {{ request()->is('create_offer_letter') || request()->is('offer_letter_list') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Offer Letter</a>
                <ul class="dropdown-menu {{ request()->is('create_offer_letter') || request()->is('offer_letter_list') ? 'show' : '' }}">       
                @if(Auth::user()->chkUserAccess(4))            
                    <li class="dropdown-item {{ request()->is('create_offer_letter')  ? 'active' : '' }}"><a href="{{url('create_offer_letter')}}">Create</a></li>
                    @endif
                    <li class="dropdown-item {{ request()->is('offer_letter_list')  ? 'active' : '' }}"><a href="{{url('offer_letter_list')}}">List</a></li>
                </ul>
            </li>            
            @endcan

            @can('access-manage-verification')
            <li class="nav-item dropdown {{  request()->is('verificationlist') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">KYC</a>
                <ul class="dropdown-menu {{  request()->is('verificationlist') ? 'show' : '' }}">
                @if((Auth::user()->account_type=='business') || (Auth::user()->account_type=='hr')) 
                    <li class="dropdown-item {{ request()->is('verification')  ? 'active' : '' }}"><a href="{{route('verification.create')}}">Verification Reqest</a></li>
                @endif
                    <li class="dropdown-item {{ request()->is('verificationlist')  ? 'active' : '' }}"><a href="{{route('verification.list')}}">Verification List</a></li>
                </ul>
            </li>
            @endcan

            @can('access-manage-deposit')
            <li class="nav-item dropdown {{ request()->is('deposit') || request()->is('depositlist') || request()->is('hr_deposit_requests') || request()->is('transaction') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Wallet</a>
                <ul class="dropdown-menu {{ request()->is('deposit') || request()->is('depositlist') || request()->is('hr_deposit_requests') || request()->is('transaction') ? 'show' : '' }}">
                @if((Auth::user()->account_type=='business') || (Auth::user()->account_type=='superadmin')) 
                    <li class="dropdown-item {{  request()->is('packages_details') ? 'active' : '' }}"><a href="{{url('packages_details')}}">Package History</a></li>                   
                @endif
                @if(Auth::user()->account_type=='business')
                <li class="dropdown-item {{  request()->is('hr_deposit_requests') ? 'active' : '' }}"><a href="{{url('hr_deposit_requests')}}">HR Deposit Requests</a></li>
                
                @endif
                @if(Auth::user()->account_type=='business' || Auth::user()->account_type=='hr')
                 
                <li class="dropdown-item {{ request()->is('deposit')  ? 'active' : '' }}"><a href="{{ url('deposit') }}">New Deposit Request</a></li>
                @endif
                    <li class="dropdown-item {{  request()->is('depositlist') ? 'active' : '' }}"><a href="{{url('depositlist')}}">Deposit History</a></li>
                    <li class="dropdown-item {{ request()->is('transaction') ? 'active' : '' }}"><a href="{{url('transaction')}}">Transactions</a></li>
                </ul>
            </li>
            @endcan

            @if((Auth::user()->account_type=='business') || (Auth::user()->account_type=='hr'))
            <li class="nav-item dropdown {{ request()->is('rating_review_list') ? 'pxp-active' : '' }}"><a href="{{url('rating_review_list')}}">Rating & Review</a></li>
            @endif

            @if(Auth::user()->account_type!='candidate' )
            <li class="nav-item dropdown {{ request()->is('create') ||request()->is('job_role') ||request()->is('manageKyc')|| request()->is('salary_component') || request()->is('response_reason') || request()->is('user_access') || request()->is('manage_document_type') || request()->is('manage_city') || request()->is('packages') ||request()->is('bank_details') ||request()->is('manage_state') || request()->is('mail_server_setting') ||request()->is('meta_data_details') ||request()->is('manage-offer-letter') ||request()->is('manage_industries')? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Settings</a>
                <ul class="dropdown-menu {{ request()->is('create') || request()->is('job_role') || request()->is('salary_component') || request()->is('response_reason') ||request()->is('manage_city')|| request()->is('user_access') || request()->is('manage_state') || request()->is('designation') || request()->is('manage_document_type') ||request()->is('packages') ||request()->is('bank_details') || request()->is('emp_range') || request()->is('mail_server_setting') || request()->is('meta_data_details') ||request()->is('manageKyc') || request()->is('manage-offer-letter')||request()->is('manage_industries') || request()->is('matrix-attributes')? 'show' : '' }}">
                    <!-- <li class="dropdown-item {{ request()->is('create')  ? 'active' : '' }}"><a href="{{ url('create') }}">Contacts</a></li> -->
                    @can('access-manage-settings')
                    <li class="dropdown-item {{  request()->is('salary_component') ? 'active' : '' }}"><a href="{{url('salary_component')}}">Salary Components</a></li>
                    <li class="dropdown-item {{  request()->is('matrix-attributes') ? 'active' : '' }}"><a href="{{url('matrix-attributes')}}">Empily Attributes</a></li>
                    
                    <li class="dropdown-item {{  request()->is('response_reason') ? 'active' : '' }}"><a href="{{url('response_reason')}}">Response Reason</a></li>
                    {{--<li class="dropdown-item {{  request()->is('user_access') ? 'active' : '' }}"><a href="{{url('user_access')}}">HR Access</a></li>--}}
                    <li class="dropdown-item {{  request()->is('manage_state') ? 'active' : '' }}"><a href="{{url('manage_state')}}">State </a></li>
                    
                    <li class="dropdown-item {{  request()->is('manage_document_type') ? 'active' : '' }}"><a href="{{url('manage_document_type')}}">Document Type </a></li>
                    <li class="dropdown-item {{  request()->is('designation') ? 'active' : '' }}"><a href="{{url('designation')}}">Designation </a></li>
                    <li class="dropdown-item {{  request()->is('emp_range') ? 'active' : '' }}"><a href="{{url('emp_range')}}">No Of Employee </a></li>
                    <li class="dropdown-item {{  request()->is('packages') ? 'active' : '' }}"><a href="{{url('packages')}}">Packages </a></li>
                    <li class="dropdown-item {{  request()->is('bank_details') ? 'active' : '' }}"><a href="{{url('bank_details')}}">Bank Details </a></li>
                    <li class="dropdown-item {{  request()->is('mail_server_setting') ? 'active' : '' }}"><a href="{{url('mail_server_setting')}}">SMTP Details </a></li>                 
                    <li class="dropdown-item {{  request()->is('create') ? 'active' : '' }}"><a href="{{url('create')}}">Site Settings </a></li>
                    <li class="dropdown-item {{  request()->is('meta_data_details') ? 'active' : '' }}"><a href="{{url('meta_data_details')}}">Meta Data </a></li>                   
                    <li class="dropdown-item {{  request()->is('manageKyc') ? 'active' : '' }}"><a href="{{url('manageKyc')}}">Manage Kyc </a></li> 
                    <li class="dropdown-item {{  request()->is('manage_industries') ? 'active' : '' }}"><a href="{{url('manage_industries')}}">Manage Industry </a></li> 
                    @endcan     
                    @if(Auth::user()->account_type=='business')
                        <li class="dropdown-item {{  request()->is('mail_server_setting') ? 'active' : '' }}"><a href="{{url('mail_server_setting')}}">SMTP Details </a></li>  
                    @endif             
                    @if((Auth::user()->account_type=='superadmin') || (Auth::user()->account_type=='business') || (Auth::user()->account_type=='hr'))
                        <li class="dropdown-item {{  request()->is('job_role') ? 'active' : '' }}"><a href="{{url('job_role')}}">Job Role</a></li>
                        <li class="dropdown-item {{  request()->is('manage-offer-letter') ? 'active' : '' }}"><a href="{{url('manage-offer-letter')}}">Offer Letter Template </a></li>
                        <li class="dropdown-item {{  request()->is('manage_city') ? 'active' : '' }}"><a href="{{url('manage_city')}}">City </a></li>
                    @endif
                </ul>
            </li>  
            @endif     

            
            @if(Auth::user()->account_type=='superadmin' )
            <li class="nav-item dropdown {{  request()->is('admin-candidate-list') || request()->is('bulk_upload') ? 'pxp-active' : '' }}">
                <a role="button" class="dropdown-toggle " data-bs-toggle="dropdown">Orangeslip</a>
                <ul class="dropdown-menu {{ request()->is('admin-candidate-list') || request()->is('bulk_upload') ? 'show' : '' }}">
                     
                     
                    
                    <li class="dropdown-item {{ request()->is('admin-candidate-list') ? 'active' : '' }}"><a href="{{route('admin-candidate-list')}}">Manage Candidate</a></li>
                     
                     
                    <li class="dropdown-item {{ request()->is('bulk_upload') ? 'active' : '' }}"><a href="{{route('bulk_upload')}}">Bulk Upload</a></li>                    
                     
                </ul>
            </li>
            @endif
           
            @if(Auth::user()->account_type=='candidate')
            
            <li class="nav-item dropdown {{ request()->is('candidate_profile') ? 'pxp-active' : '' }}"><a href="{{url('candidate_profile')}}">Profile</a></li>
            <li class="nav-item dropdown {{ request()->is('candidate_uncheck_offer') ? 'pxp-active' : '' }}"><a href="{{url('candidate_uncheck_offer')}}">Offers</a></li>
            <li class="nav-item dropdown {{ request()->is('rating_review_list') ? 'pxp-active' : '' }}"><a href="{{url('rating_review_list')}}">Rating & Review</a></li>
           {{--<li class="nav-item dropdown {{ request()->is('candidate_offer') ? 'pxp-active' : '' }}"><a href="{{url('candidate_offer')}}">Offers</a></li>--}}
           <li class="nav-item dropdown {{ request()->is('empily') ? 'pxp-active' : '' }}"><a href="{{route('empily',[base64_encode(Auth::user()->candidate->id)]) }}">EMPILY Score</a></li>
        
            @endif
             
            <!-- <li><a href="{{url('candidate/login/q8tpfa')}}">Candidate Login</a></li> -->
            <!-- <li class="{{ request()->is('resume/create*') ? 'pxp-active' : '' }}"><a href="{{route('resume.create')}}">Resume Create</a></li>
            <li class="nav-item dropdown">
                <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Dropdown</a>
                <ul class="dropdown-menu">
                    <li class="dropdown-item"><a href="#">Candidate</a></li>
                    <li class="dropdown-item"><a href="#">Candidate</a></li>
                </ul>
            </li>
            <li><a href="#">Apllications</a></li>
            <li><a href="#">Favourite Jobs</a></li>
            <li><a href="#">Change Password</a></li> -->
        </ul>
    </nav>

</div>

<div class="pxp-dashboard-content-header pxp-is-candidate">
    <div class="pxp-nav-trigger navbar pxp-is-dashboard d-lg-none">
        <a role="button" data-bs-toggle="offcanvas" data-bs-target="#pxpMobileNav" aria-controls="pxpMobileNav">
            <div class="pxp-line-1"></div>
            <div class="pxp-line-2"></div>
            <div class="pxp-line-3"></div>
        </a>
        <div class="offcanvas offcanvas-start pxp-nav-mobile-container pxp-is-dashboard pxp-is-candidate" tabindex="-1" id="pxpMobileNav">
            <div class="offcanvas-header">
            <div class="pxp-logo">
                <a href="/" class="pxp-animate">
                    <img src="{{asset('new/images/logo.png')}}" alt="">
                </a>
            </div>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <nav class="pxp-nav-mobile">
                    <ul class="navbar-nav justify-content-end flex-grow-1">
                    
                        <li class="{{ request()->is('dashboard*') ? 'pxp-active' : '' }}"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        @can("access-manage-business")
                        @if(Auth::user()->account_type!='lead staff')
                        <li class="nav-item dropdown {{ request()->is('business/create') || request()->is('business') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Business</a>
                            <ul class="dropdown-menu {{ request()->is('business/create') || request()->is('business') ? 'show' : '' }}">
                                <li class="dropdown-item {{ request()->is('business/create') ? 'active' : '' }}"><a href="{{ route('business.create') }}">Add Business</a></li>
                                <li class="dropdown-item {{  request()->is('business')? 'active' : '' }}"><a href="{{route('business.index')}}">Manage Business</a></li>                     
                            </ul>
                        </li>
                        @endif
                        @endcan

                        @can("access-manage-hr-list")
                        <li class="nav-item dropdown {{ request()->is('addHr') || request()->is('hr_list') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">HR</a>
                            <ul class="dropdown-menu {{ request()->is('addHr') || request()->is('hr_list') ? 'show' : '' }}">
                                <li class="dropdown-item {{ request()->is('addHr')  ? 'active' : '' }}"><a href="{{ route('add_hr') }}">Add HR</a></li>
                                <li class="dropdown-item {{ request()->is('hr_list') ? 'active' : '' }}"><a href="{{route('hr_list')}}">Manage HR</a></li>
                            </ul>
                        </li>
                        @endcan

                        @can('access-manage-candidate')
                        <li class="nav-item dropdown {{ request()->is('registration') || request()->is('candidate_list') || request()->is('bulk_upload') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle " data-bs-toggle="dropdown">Candidate</a>
                            <ul class="dropdown-menu {{ request()->is('registration') || request()->is('candidate_list') || request()->is('bulk_upload') ? 'show' : '' }}">
                                @if(Auth::user()->chkUserAccess(1))
                                <li class="dropdown-item {{ request()->is('registration') ? 'active' : '' }}"><a href="{{route('registration')}}">Add Candidate</a></li>
                                @endif
                                <li class="dropdown-item {{ request()->is('candidate_list') ? 'active' : '' }}"><a href="{{route('candidate_list')}}">Manage Candidate</a></li>
                                @if((Auth::user()->account_type=='hr') && (Auth::user()->chkUserAccess(3)))
                                <li class="dropdown-item {{ request()->is('bulk_upload') ? 'active' : '' }}"><a href="{{route('bulk_upload')}}">Bulk Upload</a></li>                    
                                @endif
                            </ul>
                        </li>
                        @endcan

                        @can("access-manage-lead-head")
                        <li class="nav-item dropdown {{ request()->is('add_lead_head') || request()->is('lead_head_list') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Lead Head</a>
                            <ul class="dropdown-menu {{ request()->is('add_lead_head') || request()->is('lead_head_list') ? 'show' : '' }}">
                                <li class="dropdown-item {{ request()->is('add_lead_head')  ? 'active' : '' }}"><a href="{{ route('addLeadHead') }}">Add Lead Head</a></li>
                                <li class="dropdown-item {{ request()->is('lead_head_list') ? 'active' : '' }}"><a href="{{route('leadHeadList')}}">Manage Lead Head</a></li>
                            </ul>
                        </li>
                        @endcan  

                        @can("access-manage-lead-staff")
                        <li class="nav-item dropdown {{ request()->is('add_lead_staff') || request()->is('lead_staff_list') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Lead Staff</a>
                            <ul class="dropdown-menu {{ request()->is('add_lead_staff') || request()->is('lead_staff_list') ? 'show' : '' }}">
                                <li class="dropdown-item {{ request()->is('add_lead_staff')  ? 'active' : '' }}"><a href="{{ route('addLeadStaff') }}">Add Lead Staff</a></li>
                                <li class="dropdown-item {{ request()->is('lead_staff_list') ? 'active' : '' }}"><a href="{{route('leadStaffList')}}">Manage Lead Staff</a></li>
                            </ul>
                        </li>
                        @endcan        
            
                        @can('access-manage-verification-head')
                        <li class="nav-item dropdown {{ request()->is('verification_head') || request()->is('verification_head_list') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Verification Head</a>
                            <ul class="dropdown-menu {{ request()->is('verification_head') || request()->is('verification_head_list') ? 'show' : '' }}">
                                <li class="dropdown-item {{ request()->is('verification_head')  ? 'active' : '' }}"><a href="{{ route('addVerificationHead') }}">Add Verification Head</a></li>
                                <li class="dropdown-item {{ request()->is('verification_head_list') ? 'active' : '' }}"><a href="{{route('verificationHeadList')}}">Manage Verification Head</a></li>
                            </ul>
                        </li>
                        @endcan
                        @can('access-manage-verification-staff')
                        <li class="nav-item dropdown {{ request()->is('verification_staff') || request()->is('verification_staff_list') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Verification Staff</a>
                            <ul class="dropdown-menu {{ request()->is('verification_staff') || request()->is('verification_staff_list') ? 'show' : '' }}">
                                <li class="dropdown-item {{ request()->is('verification_staff')  ? 'active' : '' }}"><a href="{{ route('addVerificationStaff') }}">Add Verification Staff</a></li>
                                <li class="dropdown-item {{ request()->is('verification_staff_list') ? 'active' : '' }}"><a href="{{route('verificationStaffList')}}">Manage Verification Staff</a></li>
                            </ul>
                        </li>
                        @endcan

                        @can("access-manage-lead")
                        <li class="nav-item dropdown {{ request()->is('enroll_list') || request()->is('assign_enroll_lead') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Enrolled Business List</a>
                            <ul class="dropdown-menu {{ request()->is('enroll_list') || request()->is('assign_enroll_lead') ? 'show' : '' }}">
                                <li class="dropdown-item {{ request()->is('enroll_list')  ? 'active' : '' }}"><a href="{{ route('enrollList') }}">Manage Enrolled Business</a></li>
                                @if(Auth::user()->account_type!='lead staff')
                                <li class="dropdown-item {{ request()->is('assign_enroll_lead') ? 'active' : '' }}"><a href="{{route('assignBusLead')}}">Assign Enrolled Business</a></li>
                                @endif
                            </ul>
                        </li>             
                        @endcan

                        @can('access-manage-candidate')
                        <li class="nav-item dropdown {{ request()->is('create_offer_letter') || request()->is('offer_letter_list') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Offer Letter</a>
                            <ul class="dropdown-menu {{ request()->is('create_offer_letter') || request()->is('offer_letter_list') ? 'show' : '' }}">   
                                @if(Auth::user()->chkUserAccess(4))               
                                <li class="dropdown-item {{ request()->is('create_offer_letter')  ? 'active' : '' }}"><a href="{{url('create_offer_letter')}}">Create</a></li>
                                @endif
                                <li class="dropdown-item {{ request()->is('offer_letter_list')  ? 'active' : '' }}"><a href="{{url('offer_letter_list')}}">List</a></li>
                            </ul>
                        </li>            
                        @endcan

                        @can('access-manage-verification')
                        <li class="nav-item dropdown {{  request()->is('verificationlist') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">KYC</a>
                            <ul class="dropdown-menu {{  request()->is('verificationlist') ? 'show' : '' }}">
                            @if((Auth::user()->account_type=='business') || (Auth::user()->account_type=='hr')) 
                                <li class="dropdown-item {{ request()->is('verification')  ? 'active' : '' }}"><a href="{{route('verification.create')}}">Verification Reqest</a></li>
                            @endif
                                <li class="dropdown-item {{ request()->is('verificationlist')  ? 'active' : '' }}"><a href="{{route('verification.list')}}">Verification List</a></li>
                            </ul>
                        </li>
                        @endcan

                        @can('access-manage-deposit')
                        <li class="nav-item dropdown {{ request()->is('deposit') || request()->is('depositlist') || request()->is('hr_deposit_requests') || request()->is('transaction') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Wallet</a>
                            <ul class="dropdown-menu {{ request()->is('deposit') || request()->is('depositlist') || request()->is('hr_deposit_requests') || request()->is('transaction') ? 'show' : '' }}">
                            @if((Auth::user()->account_type=='business') || (Auth::user()->account_type=='superadmin')) 
                                <li class="dropdown-item {{  request()->is('packages_details') ? 'active' : '' }}"><a href="{{url('packages_details')}}">Package History</a></li>                   
                            @endif
                            @if(Auth::user()->account_type=='business')
                            <li class="dropdown-item {{  request()->is('hr_deposit_requests') ? 'active' : '' }}"><a href="{{url('hr_deposit_requests')}}">HR Deposit Requests</a></li>
                            <li class="dropdown-item {{ request()->is('deposit')  ? 'active' : '' }}"><a href="{{ url('deposit') }}">New Deposit Request</a></li>
                            @endif
                                <li class="dropdown-item {{  request()->is('depositlist') ? 'active' : '' }}"><a href="{{url('depositlist')}}">Deposit History</a></li>
                                <li class="dropdown-item {{ request()->is('transaction') ? 'active' : '' }}"><a href="{{url('transaction')}}">Transactions</a></li>
                            </ul>
                        </li>
                        @endcan

                        @if((Auth::user()->account_type=='business') || (Auth::user()->account_type=='hr'))
                        <li class="nav-item dropdown {{ request()->is('rating_review_list') ? 'pxp-active' : '' }}"><a href="{{url('rating_review_list')}}">Rating & Review</a></li>
                        @endif

                        @if(Auth::user()->account_type!='candidate' )
                        <li class="nav-item dropdown {{ request()->is('create') ||request()->is('job_role') || request()->is('salary_component') || request()->is('response_reason') || request()->is('user_access') || request()->is('manage_document_type') || request()->is('manage_city') || request()->is('packages') ||request()->is('bank_details') ||request()->is('manage_state') || request()->is('mail_server_setting') || request()->is('manageKyc') || request()->is('meta_data_details') || request()->is('manage-offer-letter')||request()->is('manage_industries')? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Settings</a>
                            <ul class="dropdown-menu {{ request()->is('create') || request()->is('job_role') || request()->is('salary_component') || request()->is('response_reason') ||request()->is('manage_city')|| request()->is('user_access') || request()->is('manage_state') || request()->is('designation') || request()->is('manage_document_type') ||request()->is('packages') ||request()->is('bank_details') || request()->is('emp_range') || request()->is('mail_server_setting') || request()->is('meta_data_details')||request()->is('manageKyc') || request()->is('manage-offer-letter') ||request()->is('manage_industries')? 'show' : '' }}">
                                <!-- <li class="dropdown-item {{ request()->is('create')  ? 'active' : '' }}"><a href="{{ url('create') }}">Contacts</a></li> -->
                                @can('access-manage-settings')
                                <li class="dropdown-item {{  request()->is('salary_component') ? 'active' : '' }}"><a href="{{url('salary_component')}}">Salary Components</a></li>
                               
                                <li class="dropdown-item {{  request()->is('response_reason') ? 'active' : '' }}"><a href="{{url('response_reason')}}">Response Reason</a></li>
                                {{--<li class="dropdown-item {{  request()->is('user_access') ? 'active' : '' }}"><a href="{{url('user_access')}}">HR Access</a></li>--}}
                                <li class="dropdown-item {{  request()->is('manage_state') ? 'active' : '' }}"><a href="{{url('manage_state')}}">State </a></li>
                                
                                <li class="dropdown-item {{  request()->is('manage_document_type') ? 'active' : '' }}"><a href="{{url('manage_document_type')}}">Document Type </a></li>
                                <li class="dropdown-item {{  request()->is('designation') ? 'active' : '' }}"><a href="{{url('designation')}}">Designation </a></li>
                                <li class="dropdown-item {{  request()->is('emp_range') ? 'active' : '' }}"><a href="{{url('emp_range')}}">No Of Employee </a></li>
                                <li class="dropdown-item {{  request()->is('packages') ? 'active' : '' }}"><a href="{{url('packages')}}">Packages </a></li>
                                <li class="dropdown-item {{  request()->is('bank_details') ? 'active' : '' }}"><a href="{{url('bank_details')}}">Bank Details </a></li>
                                <li class="dropdown-item {{  request()->is('mail_server_setting') ? 'active' : '' }}"><a href="{{url('mail_server_setting')}}">SMTP Details </a></li>                 
                                <li class="dropdown-item {{  request()->is('create') ? 'active' : '' }}"><a href="{{url('create')}}">Site Settings </a></li>
                                <li class="dropdown-item {{  request()->is('meta_data_details') ? 'active' : '' }}"><a href="{{url('meta_data_details')}}">Meta Data </a></li>            
                                <li class="dropdown-item {{  request()->is('manageKyc') ? 'active' : '' }}"><a href="{{url('manageKyc')}}">Manage Kyc </a></li>
                                <li class="dropdown-item {{  request()->is('manage_industries') ? 'active' : '' }}"><a href="{{url('manage_industries')}}">Manage Industry </a></li> 
                                @endcan
                                @if(Auth::user()->account_type=='business')
                                <li class="dropdown-item {{  request()->is('mail_server_setting') ? 'active' : '' }}"><a href="{{url('mail_server_setting')}}">SMTP Details </a></li>        
                                    
                                @endif
                                @if((Auth::user()->account_type=='superadmin') || (Auth::user()->account_type=='business') || (Auth::user()->account_type=='hr'))
                                    <li class="dropdown-item {{  request()->is('manage-offer-letter') ? 'active' : '' }}"><a href="{{url('manage-offer-letter')}}">Offer Letter Template </a></li>
                                    <li class="dropdown-item {{  request()->is('job_role') ? 'active' : '' }}"><a href="{{url('job_role')}}">Job Role</a></li>
                                    <li class="dropdown-item {{  request()->is('manage_city') ? 'active' : '' }}"><a href="{{url('manage_city')}}">City </a></li>
                                @endif
                            </ul>
                        </li>      
                        @endif       
                        @if(Auth::user()->account_type=='superadmin' )
                        <li class="nav-item dropdown {{  request()->is('admin-candidate-list') || request()->is('bulk_upload') ? 'pxp-active' : '' }}">
                            <a role="button" class="dropdown-toggle " data-bs-toggle="dropdown">Orangeslip</a>
                            <ul class="dropdown-menu {{ request()->is('admin-candidate-list') || request()->is('bulk_upload') ? 'show' : '' }}">
                                
                                
                                
                                <li class="dropdown-item {{ request()->is('admin-candidate-list') ? 'active' : '' }}"><a href="{{route('admin-candidate-list')}}">Manage Candidate</a></li>
                                
                                
                                <li class="dropdown-item {{ request()->is('bulk_upload') ? 'active' : '' }}"><a href="{{route('bulk_upload')}}">Bulk Upload</a></li>                    
                                
                            </ul>
                        </li>
                        @endif
                    
                        @if(Auth::user()->account_type=='candidate')
                        <li class="nav-item dropdown {{ request()->is('candidate_profile') ? 'pxp-active' : '' }}"><a href="{{url('candidate_profile')}}">Profile</a></li>
                        <li class="nav-item dropdown {{ request()->is('candidate_uncheck_offer') ? 'pxp-active' : '' }}"><a href="{{url('candidate_uncheck_offer')}}">Offers</a></li>
                        <li class="nav-item dropdown {{ request()->is('rating_review_list') ? 'pxp-active' : '' }}"><a href="{{url('rating_review_list')}}">Rating & Review</a></li>
                        {{--<li class="nav-item dropdown {{ request()->is('candidate_offer') ? 'pxp-active' : '' }}"><a href="{{url('candidate_offer')}}">Offers</a></li>--}}
                        <li class="nav-item dropdown {{ request()->is('empily') ? 'pxp-active' : '' }}"><a href="{{route('empily',[base64_encode(Auth::user()->candidate->id)]) }}">EMPILY Score</a></li>
                        @endif
             
                        <!-- <li><a href="{{url('candidate/login/q8tpfa')}}">Candidate Login</a></li> -->
                        <!-- <li class="{{ request()->is('resume/create*') ? 'pxp-active' : '' }}"><a href="{{route('resume.create')}}">Resume Create</a></li>
                        <li class="nav-item dropdown">
                            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">Dropdown</a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-item"><a href="#">Candidate</a></li>
                                <li class="dropdown-item"><a href="#">Candidate</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Apllications</a></li>
                        <li><a href="#">Favourite Jobs</a></li>
                        <li><a href="#">Change Password</a></li> -->
                    </ul>                   
                </nav>
            </div>
        </div>
    </div>
    <nav class="pxp-user-nav pxp-on-light">
        <h6 class="mb-0">
            @php
            $ac_type=Auth::user()->account_type;
            $role=[
                'candidate'=>'',
                'superadmin'=>'Admin',
                'business'=>'Business',
                'hr'=>'HR',
                'verification head'=>'Verification Head',
                'verification staff'=>'Verification Staff',
                'lead staff'=>'Lead Staff',
                'lead head'=>'Lead Head',
                ];
                echo $role[$ac_type];
            @endphp

                 
                 </h6>
        <!-- <div class="dropdown pxp-user-nav-dropdown pxp-user-notifications">
            <a href="#" class="dropdown-toggle">
                <span class="fa fa-bell-o"></span>
                <div class="pxp-user-notifications-counter">5</div>
            </a>
        </div> -->
        
        <div class="dropdown pxp-user-nav-dropdown">
            <a role="button" class="dropdown-toggle" data-bs-toggle="dropdown">
                @if(Auth::user()->account_type!='candidate')
                <div class="pxp-user-nav-avatar pxp-cover" style="background-image: url({{ (Auth::user()->profile->avatar!='')?(url('images/'.Auth::user()->profile->avatar)):(url('/new/images/noimage.png')) }});"></div>
                @else
                <div class="pxp-user-nav-avatar pxp-cover" style="background-image: url({{ (Auth::user()->candidate->photo!='')?(url('images/'.Auth::user()->candidate->photo)):(url('/new/images/noimage.png')) }});"></div>
                @endif
                <div class="pxp-user-nav-name d-none d-md-block">@if($ac_type=='business'){{Auth::user()->business->business_name}}@else{{Auth::user()->first_name}} {{Auth::user()->last_name}}@endif</div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(Auth::user()->account_type!='candidate')
                <li><a class="dropdown-item" href="{{url('edit_profile')}}">Edit profile</a></li>
                @endif
                <li><a class="dropdown-item" href="{{url('change_password')}}">Change Password</a></li>
                @if(Session::get('adminLogin') == true)
                    <li><a class="dropdown-item" href="{{ url('login_as_admin') }}">Back to Administrator</a></li>
                @endif
                
                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                
            </ul>
        </div>
    </nav>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
	{{ csrf_field() }}
</form>
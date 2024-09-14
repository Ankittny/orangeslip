@extends('admin.layouts.app')

@section('content')

    @if(session('success'))                                     
        <script type="text/javascript">toastr.success("{{session('success')}}")</script> 
    @endif
    @if(session('error'))                                
        <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
    @endif
    @if (session('status'))
        <script type="text/javascript">toastr.success("{{session('status')}}")</script> 
    @endif
<div class="pxp-dashboard-content-details">
    <h1>Dashboard</h1>
    <p class="pxp-text-light">Welcome to Orangeslip</p>
    @if(Auth::user()->account_type!='hr' && Auth::user()->account_type!='candidate' )
    <div class="row mt-4 mt-lg-4 align-items-center">
         
        <div class="col-md-2 col-xl-2 col-sm-2">
            <label>Referral Code :</label>  
            <input type="text" readonly class="form-control" id="refLink" value="{{Auth::user()->user_code}}">
        </div>
        <div class="col-md-2 col-xl-2 col-sm-2">
        <label>&nbsp;</label>
        <button class="btn rounded-pill btn-block pxp-section-cta" value="copy" onclick="copyToClipboard()">Copy!</button>
        </div>
    </div>
    <!-- <div class="row mt-4 mt-lg-4 align-items-center">
         
        <div class="col-md-8 col-xl-8 col-sm-8">
            <label>Referral Link :</label>  
            <input type="text" readonly class="form-control" id="refLink" value="https://orangeslip.com/enroll_company?ref_code={{Auth::user()->user_code}}">
        </div>
        <div class="col-md-4 col-xl-4 col-sm-6">
        <label>&nbsp;</label>
        <button class="btn rounded-pill btn-block pxp-section-cta" value="copy" onclick="copyToClipboard()">Copy!</button>
        </div>
    </div> -->
    @endif
    @isset($packDetails)
        <div class="mt-4">      
            <a href="{{url('packages_details')}}" class="dashboard_box gradient16">               
                <!-- <h2 class="mt-0">Package Details</h2> -->
                <h4 style="font-size: 20px;" class="mt-0"> Current Package: <strong>{{$packDetails->pack_name}}</strong></h4>
                <h4 style="font-size: 20px;"> Valid Upto: <strong>{{$packDetails->expire_date}}</strong></h4>
                <h4 style="font-size: 20px;"> Used Qty.: <strong>{{$packDetails->used_qty}} Offer Letter</strong></h4>
                <h4 style="font-size: 20px;"> Remaining Qty.: <strong>{{$packDetails->remain_qty}} Offer Letter</strong></h4>               
            </a>        
        </div>
    @endif

    <div class="row mt-4 mt-lg-4 align-items-center">
        @isset($balance)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('transaction')}}" class="dashboard_box gradient1">
                <img src="{{asset('new/images/icon/icon01.png')}}" alt="">
                <h5>Wallet Balance</h5>
                <h2><i class="fa fa-inr"></i> {{$balance}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_business)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('business')}}" class="dashboard_box gradient6">
                <img src="{{asset('new/images/icon/icon06.png')}}" alt="">
                <h5>Total Employer</h5>
                <h2>{{$no_of_business}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_hr)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('hr_list')}}" class="dashboard_box gradient7">
            <img src="{{asset('new/images/icon/icon07.png')}}" alt="">
                <h5>Total HR</h5>
                <h2>{{$no_of_hr}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_candidate)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('candidate_list')}}?status=1" class="dashboard_box gradient11">
                <img src="{{asset('new/images/icon/icon11.png')}}" alt="">
                <h5>Total Candidate</h5>
                <h2>{{$no_of_candidate}}</h2>
            </a>
        </div>
        @endif

        @isset($no_of_lead_head)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('lead_head_list')}}" class="dashboard_box gradient8">
            <img src="{{asset('new/images/icon/icon08.png')}}" alt="">
                <h5>Total Lead Head</h5>
                <h2>{{$no_of_lead_head}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_lead_staff)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('lead_staff_list')}}" class="dashboard_box gradient9">
                <img src="{{asset('new/images/icon/icon09.png')}}" alt="">
                <h5>Total Lead Staff</h5>
                <h2>{{$no_of_lead_staff}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_verification_head)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('verification_head_list')}}" class="dashboard_box gradient8">
            <img src="{{asset('new/images/icon/icon08.png')}}" alt="">
                <h5>Total Verification Head</h5>
                <h2>{{$no_of_verification_head}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_verification_staff)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('verification_staff_list')}}" class="dashboard_box gradient9">
                <img src="{{asset('new/images/icon/icon09.png')}}" alt="">
                <h5>Total Verification Staff</h5>
                <h2>{{$no_of_verification_staff}}</h2>
            </a>
        </div>
        @endif

        @isset($no_of_lead)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('enroll_list')}}" class="dashboard_box gradient6">
                <img src="{{asset('new/images/icon/icon06.png')}}" alt="">
                <h5>Total Enrolled Employer</h5>
                <h2>{{$no_of_lead}}</h2>
            </a>
        </div>
        @endif
        @isset($pending_lead)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('assign_enroll_lead')}}" class="dashboard_box gradient6">
                <img src="{{asset('new/images/icon/icon06.png')}}" alt="">
                <h5>Total Pending Lead</h5>
                <h2>{{$pending_lead}}</h2>
            </a>
        </div>
        @endif
        @isset($verified_lead)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('enroll_list')}}?status=2" class="dashboard_box gradient6">
                <img src="{{asset('new/images/icon/icon06.png')}}" alt="">
                <h5>Total Verified Lead</h5>
                <h2>{{$verified_lead}}</h2>
            </a>
        </div>
        @endif
        @isset($created_lead)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('enroll_list')}}?status=3" class="dashboard_box gradient6">
                <img src="{{asset('new/images/icon/icon06.png')}}" alt="">
                <h5>Total Created Lead</h5>
                <h2>{{$created_lead}}</h2>
            </a>
        </div>
        @endif
        @isset($rejected_lead)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('enroll_list')}}?status=4" class="dashboard_box gradient6">
                <img src="{{asset('new/images/icon/icon06.png')}}" alt="">
                <h5>Total Rejected Lead</h5>
                <h2>{{$rejected_lead}}</h2>
            </a>
        </div>
        @endif
        @isset($assigned_lead)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('enroll_list')}}?status=5" class="dashboard_box gradient6">
                <img src="{{asset('new/images/icon/icon06.png')}}" alt="">
                <h5>Total Assigned Lead</h5>
                <h2>{{$assigned_lead}}</h2>
            </a>
        </div>
        @endif
        
       


        @isset($no_of_response)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('offer_letter_list')}}?status=all_res" class="dashboard_box gradient10">
                <img src="{{asset('new/images/icon/icon10.png')}}" alt="">
                <h5>Total Response</h5>
                <h2>{{$no_of_response}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_offerletter_generated)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('offer_letter_list')}}" class="dashboard_box gradient12">
                <img src="{{asset('new/images/icon/icon12.png')}}" alt="">
                <h5>Offer Letter Generated</h5>
                <h2>{{$no_of_offerletter_generated}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_confirmed_joining)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('offer_letter_list')}}?status=1" class="dashboard_box gradient14">
                <img src="{{asset('new/images/icon/icon14.png')}}" alt="">
                <h5>Offer Letter Accepted</h5>
                <h2>{{$no_of_confirmed_joining}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_rejected_offer_letter)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('offer_letter_list')}}?status=2" class="dashboard_box gradient16">
                <img src="{{asset('new/images/icon/icon16.png')}}" alt="">
                <h5>Offer Letter Rejected</h5>
                <h2>{{$no_of_rejected_offer_letter}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_reschedule_joining)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('offer_letter_list')}}?status=3" class="dashboard_box gradient15">
                <img src="{{asset('new/images/icon/icon15.png')}}" alt="">
                <h5>Total Reschedule Joining</h5>
                <h2>{{$no_of_reschedule_joining}}</h2>
            </a>
        </div>
        @endif

        
       
        @isset($no_of_kyc_request)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('verificationlist')}}" class="dashboard_box gradient3">
                <img src="{{asset('new/images/icon/icon03.png')}}" alt="">
                <h5>Total KYC Request</h5>
                <h2>{{$no_of_kyc_request}}</h2>
            </a>
        </div>
        @endif
        
        @isset($no_of_kyc_completed)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('verificationlist')}}?status=3" class="dashboard_box gradient5">
                <img src="{{asset('new/images/icon/icon05.png')}}" alt="">
                <h5>Total KYC Completed</h5>
                <h2>{{$no_of_kyc_completed}}</h2>
            </a>
        </div>
        @endif

        @isset($no_of_kyc_pending)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('verificationlist')}}?status=1" class="dashboard_box gradient4">
                <img src="{{asset('new/images/icon/icon04.png')}}" alt="">
                <h5>Total KYC Pending</h5>
                <h2>{{$no_of_kyc_pending}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_kyc_assigned)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('verificationlist')}}?status=2" class="dashboard_box gradient4">
                <img src="{{asset('new/images/icon/icon04.png')}}" alt="">
                <h5>Total KYC Assigned</h5>
                <h2>{{$no_of_kyc_assigned}}</h2>
            </a>
        </div>
        @endif
       
        
        
        
       {{--
        @isset($no_of_selected_candidate)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('candidate_list')}}?status=1" class="dashboard_box gradient11">
                <img src="{{asset('new/images/icon/icon11.png')}}" alt="">
                <h5>No Of Selected Candidate</h5>
                <h2>{{$no_of_selected_candidate}}</h2>
            </a>
        </div>
        @endif
        --}}
       
        @isset($no_of_pending_offer)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('offer_letter_list')}}?status=O" class="dashboard_box gradient13">
                <img src="{{asset('new/images/icon/icon13.png')}}" alt="">
                <h5>No Of Pending Offer</h5>
                <h2>{{$no_of_pending_offer}}</h2>
            </a>
        </div>
        @endif
        
        

        @isset($no_of_deposit_request)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('depositlist')}}" class="dashboard_box gradient17">
                <img src="{{asset('new/images/icon/icon17.png')}}" alt="">
                <h5>No Of Deposit Request New</h5>
                <h2>{{$no_of_deposit_request}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_deposit_approved)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('depositlist')}}" class="dashboard_box gradient18">
            <img src="{{asset('new/images/icon/icon18.png')}}" alt="">
                <h5>No Of Deposit Request Approved</h5>
                <h2>{{$no_of_deposit_approved}}</h2>
            </a>
        </div>
        @endif
        @isset($no_of_deposit_rejected)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('depositlist')}}" class="dashboard_box gradient19">
            <img src="{{asset('new/images/icon/icon19.png')}}" alt="">
                <h5>No Of Deposit Request Rejected</h5>
                <h2>{{$no_of_deposit_rejected}}</h2>
            </a>
        </div>
        @endif
     

        @isset($offerletters)
        <div class="col-md-4 col-xl-4 col-sm-6">
            <a href="{{url('/candidate_uncheck_offer')}}" class="dashboard_box gradient12">
                <img src="{{asset('new/images/icon/icon12.png')}}" alt="">
                <h5>Offer Letters</h5>
                <h2>{{$offerletters}}</h2>
            </a>
        </div>
        @endif
        @isset($progress_lvl)
        <div class="col-md-12">
            <div class="dashboard_box p-2">
             
                <h5 class="text-dark">Profile Complete</h5>
                <div class="progress" style="height:25px; background-color: #d3d6d8;">
                    <div class="progress-bar bg-success"  role="progressbar" aria-valuenow="{{$progress_lvl}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$progress_lvl}}%">
                    {{$progress_lvl}}%
                    </div>
                </div>
            </div>
        </div>
        @endif

         
    </div>

</div>
@isset($offerletters)
@if($offerletters!=0)
 <!--candidate offers-->
 {{--<div class="modal hide fade" id="offerModal">
        <div class="modal-dialog">
            <div class="modal-content">
             
            <div class="modal-body">
                <h4 class="modal-title text-center">Offer Letters</h4>
                <p class="mb-5">We find {{$offerletters}} offer letters to your email. Please show and confirm the offers.</p>
                <a href="{{url('candidate_uncheck_offer')}}" class="btn btn-success">Show</a>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>                  
                 
            </div>
            </div>
        </div>
</div>--}}
    <!--candidate Offers-->
@endif
@endif
@endsection
@push('js')
<script>
    function copyToClipboard() {
        document.getElementById("refLink").select();
        document.execCommand('copy');
    }
</script>
<script type="text/javascript">
    $(window).on('load', function() {
        $('#offerModal').modal('show');
    });
</script>
@endpush
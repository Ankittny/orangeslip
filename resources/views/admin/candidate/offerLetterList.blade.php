@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
<div class="candidate_List_box">
                <h5>Search By</h5>
                <div class="candidate_List_box_inner">
                
		            <form name="search" method="get" action="{{url('offer_letter_list')}}">
     			    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label class="control-label">{{ __('Name') }}</label>
                            <input type="text" name="cname" id="cname" class="form-control" value="{{ isset($searchData['cname']) ? $searchData['cname'] : "" }}">							
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="control-label">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ isset($searchData['email']) ? $searchData['email'] : "" }}">							
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="control-label">{{ __('Phone') }}</label>
                            <input type="number" name="phone" id="phone" class="form-control" value="{{ isset($searchData['phone']) ? $searchData['phone'] : "" }}">							
                        </div>
                    </div>
                    <div class="row">  
                        <div class="col-md-3 mb-3">
                            <label class="control-label">{{ __('Joining Date From') }}</label>
                            <input type="date" name="from" id="from" class="form-control" value="{{ isset($searchData['from']) ? $searchData['from'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="control-label">{{ __('Joinig Date to') }}</label>
                            <input type="date" name="to" id="to" class="form-control" value="{{ isset($searchData['to']) ? $searchData['to'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="control-label">{{ __('Joining Place') }}</label>
                            <input type="text" name="place" id="place" class="form-control" value="{{ isset($searchData['place']) ? $searchData['place'] : "" }}">							
                        </div>       
                        <div class="col-md-3 mb-3">
                            <label class="control-label">{{ __('Status') }}</label>
                            <select name="status" id="status" >
                                <option value=''>{{ __('Select') }}</option>
                               
                                <option value="0"  >{{ __('Pending') }}</option>
                                <option value="1"  >{{ __('Offer Letter Accepted') }}</option>
                                <option value="2"  >{{ __('Offer Letter Rejected') }}</option>
                                <option value="3"  >{{ __('Request for Reschedule') }}</option>
                            </select>						
                        </div>       
                         

                        
			        </div>
                        <div class="text-center">
                            <button type="submit" class="btn rounded-pill pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                            <button type="button" class="btn rounded-pill pxp-section-cta" id="reset">{{ __('Clear') }}</button>
                        </div>
		            </form>
                </div>
            </div>

           
    <div class="pxp-dashboard-content-details">
        <h1>Offer Letter List</h1>
        <button type="button" class="btn expBtn sm_btn rounded-pill pxp-section-cta" >Export</button>
        @if(! $offerletters->isEmpty())
         
        @foreach($offerletters as $offerletter)
            <div class="candidate_List_box">
                <div class="candidate_List_box_inner">
                    <div class="row">
                        <div class="col-md-2 col-sm-3 col-lg-2 text-center">
                            <img class="candidate_List_img" src="{{ ($offerletter->candidateDetails->photo!='')?(url('images/'.$offerletter->candidateDetails->photo)):(url('/new/images/noimage.png')) }}" alt="">
                            {{$offerletter->candidateDetails->candidate_code}}
                        </div>
                        <div class="col-md-10 col-sm-9 col-lg-10">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="line_holder">
                                        <p class="d-item_one">Candidate Name</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($offerletter->candidateDetails->name)}}</p>
                                    </div>
                                    
                                    <div class="line_holder">
                                        <p class="d-item_one">Joining Date</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$offerletter->joining_date}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Place Of Joining</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$offerletter->place_of_joining}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Reporting Time</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$offerletter->time_of_joining}}</p>
                                    </div>
                                    
                                    
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="line_holder">
                                        <p class="d-item_one">Annual CTC</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two"><i class="fa fa-inr"></i> {{$offerletter->annual_ctc}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">HR</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($offerletter->hrDetails->first_name)}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Business</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($offerletter->businessDetails->business->business_name)}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Status</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">
                                            @if($offerletter->is_accepted==0)
                                            Pending
                                            @elseif($offerletter->is_accepted==1)
                                            Offer Accepted(Joining Confirmed)
                                            @elseif($offerletter->is_accepted==2)
                                            Offer Rejected
                                            @elseif($offerletter->is_accepted==3)
                                            Request For Reschedule
                                            @endif
                                        </p>
                                    </div>
                                    
                                </div>
                                
                            </div>     
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 candidate_List_btn">
                   
                    @if(($offerletter->is_accepted==3) && ($offerletter->is_modify==0))
                    <a href="{{url('joiningdetails')}}/{{base64_encode($offerletter->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Approve Reschedule</a>
                    @endif
                    <a href="{{url('offer_letter')}}/{{base64_encode($offerletter->id)}}" class="btn sm_btn rounded-pill pxp-section-cta" target="_blank">Offer Letter</a>
                    @if($offerletter->is_modify==0 && $offerletter->is_accepted==0)
                    <a href="{{url('resend_mail')}}/{{base64_encode($offerletter->id)}}" onclick="return confirm('Are you sure?');" class="btn sm_btn rounded-pill pxp-section-cta">Resend Mail</a>
                    <a href="{{url('regenerate_offer_letter')}}/{{base64_encode($offerletter->id)}}" onclick="return confirm('Are you sure?');" class="btn sm_btn rounded-pill pxp-section-cta" target="_blank">Edit</a>
                    @endif
                   
                </div>
            </div>

        @endforeach

        {{$offerletters->links()}}
    @else
        <p>Sorry No Matching Found!</p>
    @endif  
    </div>

       
 
@endsection
@push('js')
<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/offer_letter_list?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });
</script>
@endpush
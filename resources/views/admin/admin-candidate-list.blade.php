
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
                 
		            <form name="search" method="get" id="searchForm" action="{{route('admin-candidate-list')}}">
     			    <div class="row">

                        <div class="col-md-3 mb-4">
                            <label class="control-label">{{ __('Name') }}</label>
                            <input type="text" name="cname" id="cname" class="form-control" value="{{ isset($searchData['cname']) ? $searchData['cname'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="control-label">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ isset($searchData['email']) ? $searchData['email'] : "" }}">							
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="control-label">{{ __('Phone') }}</label>
                            <input type="number" name="phone" id="phone"  class="form-control" value="{{ isset($searchData['phone']) ? $searchData['phone'] : "" }}">							
                        </div>
                        
                    </div>
                   
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="control-label" >&nbsp;</label>
                            <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" >&nbsp;</label>
                            <button type="button" class="btn rounded-pill btn-block pxp-section-cta" id="reset" value="true">{{ __('Clear') }}</button>
                             
                        </div>
                        
			        </div>
                        
		            </form>
                </div>
            </div>
           
    <div class="pxp-dashboard-content-details custom_chk">
        <h1>Candidate List</h1>

        <div class="d-flex justify-content-between mb-3">
            <div>
                <button type="button" class="btn expBtn sm_btn rounded-pill pxp-section-cta" >Export</button>
                
            </div>
            <div>
                              
            </div>
        </div>

        <!-- <span data-href="/export-csv"  id="export" class="btn btn-success btn-sm" onclick ="exportTasks (event.target);">Export CSV</span> -->
        <p class="statusMsg"></p>
        @foreach($candidates as $candidate)
            <div class="candidate_List_box">
             
                <div class="candidate_List_box_inner">
                    <div class="row">
                        <div class="col-md-2 col-sm-3 col-lg-2 text-center">
                            <img class="candidate_List_img" src="{{ ($candidate->photo!='')?(url('images/'.$candidate->photo)):(url('/new/images/noimage.png')) }}" alt="">
                            {{$candidate->candidate_code}}
                        </div>
                        <div class="col-md-10 col-sm-9 col-lg-10">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="line_holder">
                                        <p class="d-item_one">Name</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($candidate->name)}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Email</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($candidate->email)}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Phone No</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$candidate->phone}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Gender</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($candidate->gender)}}</p>
                                    </div>
                                    
                                    @if($candidate->rating!=NULL)
                                    <div class="line_holder">
                                        <p class="d-item_one">Rating</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$candidate->rating}}</p>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="line_holder">
                                        <p class="d-item_one">Date</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$candidate->created_at}}</p>
                                    </div>

                                    <div class="line_holder">
                                        <p class="d-item_one">Added By</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">
                                           {{$candidate->added_by==1?'Orangeslip':''}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            
                           
                        </div>
                        
                    </div>
                </div>
                <div class="mt-3 candidate_List_btn">
                    <div class="d-flex justify-content-between mb-3">
                        <div>                             
                               {{-- <a href="{{url('edit_candidate')}}/{{base64_encode($candidate->id)}}" class="btn sm_btn rounded-pill pxp-section-cta">Edit Candidate Details</a>--}}
                               <a href="{{ route('candidateview',[base64_encode($candidate->id)]) }}" class="btn sm_btn rounded-pill btn-warning" target="_blank">Resume</a>
                               <a href="{{ route('empily',[base64_encode($candidate->id)]) }}" target="_blank" class="btn sm_btn rounded-pill pxp-section-cta1 btn-success">EMPILY Score</a>
                        </div>
                         
                    </div>
                   
                </div>
               
            </div>

        @endforeach

        {{$candidates->links()}}
       
    </div>
  
     
 
@endsection
@push('js')

 
<script>
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      
       

      window.location.href = _url;
   }
</script>
<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/candidate_list?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });


     
</script>

@endpush
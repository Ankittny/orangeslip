@extends('admin.layouts.app')
@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif

            <div class="pxp-dashboard-content-details">

            <h5>Search By</h5>
                <div class="">
                 
		            <form name="search" method="get" action="{{url('business')}}">
     			    <div class="row">

                        <div class="col-md-4 mb-4">
                            <label class="control-label">{{ __('Keyword') }}</label>
                            <input type="text" name="keyword" id="keyword" class="form-control" value="{{ isset($searchData['keyword']) ? $searchData['keyword'] : "" }}">							
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="control-label">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ isset($searchData['email']) ? $searchData['email'] : "" }}">							
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="control-label">{{ __('Mobile No') }}</label>
                            <input type="number" name="mobile_no" id="mobile_no" class="form-control" value="{{ isset($searchData['mobile_no']) ? $searchData['mobile_no'] : "" }}">							
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="control-label">{{ __('Created From') }}</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ isset($searchData['from_date']) ? $searchData['from_date'] : "" }}">							
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="control-label">{{ __('Created To') }}</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ isset($searchData['to_date']) ? $searchData['to_date'] : "" }}">							
                        </div>
                        <div class="col-md-4 mb-4">
                        <label class="control-label">{{ __('Status') }}</label>
                        <select name="status" id="status" >
                                <option value="">{{ __('All') }}</option>
                                <option value="1" @isset($searchData['status']){{  $searchData['status'] == 1 ? "selected" : "" }}@endif>{{ __('Active') }}</option>
                                <option value="2"  @isset($searchData['status']){{  $searchData['status'] == 2 ? "selected" : "" }}@endif>{{ __('Inactive') }}</option>                                
                            </select>						
                        </div>
                       
                        <div class="col-md-4 mb-3">
                            <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                        </div>
                        <div class="col-md-4">
                        <button type="button" class="btn rounded-pill btn-block pxp-section-cta" id="reset" value="true">{{ __('Clear') }}</button>
                        </div>
                    </div>
                       
			        </div>
                       
		            </form>
               

                <hr>

                <h5>Business List</h5>

               <button type="button" class="btn expBtn" >Export</button>
                <div class="table-responsive">  
                    <table class="table align-middle footable">
                        <thead>
                            <tr>
                                
                                <th>Business Name</th>
                                <th>Owner Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th data-breakpoints="xs sm md lg">Phone No</th>
                                <th data-breakpoints="xs sm md lg">No of Employee</th>
                                <th data-breakpoints="xs sm md lg">Created Date</th>
                                <th data-breakpoints="xs sm md lg">Last Login</th>
                                <th data-breakpoints="xs sm md lg">Reference Code</th>
                                <th data-breakpoints="xs sm md lg">Action</th>
                            
                                 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($businesses as $business)
                            <tr>
                            
                                <td>{{$business->business_name}}</td>
                                <td>{{$business->first_name.' '.$business->last_name}}</td>
                                <td>{{$business->email}}</td>
                                <td>{{$business->userStatus==1 ? 'Active':'Inactive'}}</td>
                                <td>{{$business->mobile_no}}</td>
                                <td>{{$business->range_start}} - {{$business->range_end}}</td>
                                <td>{{$business->usercreatedate}}</td>
                                <td>{{$business->last_login}}</td>
                                <td>{{$business->referral_code}}</td>
                                <td>
                               
                                @if(Auth::user()->account_type=='superadmin')
                                    <form method="post" action="{{ route('login_as_member') }}" style="display: inline-block;">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $business->user_id }}">
                                    <button type="submit" class="btn btn-sm btn-rounded btn-primary">Login as Business</button>
                                    </form> 
                                    
                                    <a href="{{url('credit_amount')}}/{{$business->user_id}}" class="btn btn-sm btn-rounded btn-success">Credit</a>
                                    <a href="{{url('debit_amount')}}/{{$business->user_id}}" class="btn btn-sm btn-rounded btn-danger">Debit</a>
                                @endif
                                    <a href="{{route('business.edit',[$business->user_id])}} " class="btn btn-sm btn-rounded btn-warning">Edit</a> 
                                    <a href="{{url('change_status')}}/{{$business->user_id}}" class="btn btn-sm btn-rounded btn-warning" onclick="return confirm('Are you sure to {{$business->userStatus==1 ? 'Deactive':'Active'}} ?');">{{$business->userStatus==1 ? 'Deactive':'Active'}}</a>  
                                </td>  
                              
                                
                                
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
                {{$businesses->links()}}
            </div>
@endsection
@push('js')
<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/business?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });
</script>
@endpush
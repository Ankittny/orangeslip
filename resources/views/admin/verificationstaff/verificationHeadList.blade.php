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
                 
		            <form name="search" method="get" action="{{url('verification_head_list')}}">
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

                <h5>Verification Head List</h5>
                <button type="button" class="btn expBtn" >Export</button>
                <div class="table-responsive">  
                    <table class="table align-middle footable">
                        <thead>
                            <tr>
                                
                                <th>Name</th>                               
                                <th>Email</th>
                                <th>Status</th>
                                <th data-breakpoints="xs sm md lg">Phone No</th>                               
                                <th data-breakpoints="xs sm md lg">Created Date</th>
                                <th data-breakpoints="xs sm md lg">Last Login</th>
                                <th data-breakpoints="xs sm md lg">Action</th>
                            
                                 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allHead as $head)
                            <tr>
                            
                                <td>{{$head->first_name}} {{$head->last_name}}</td>
                               
                                <td>{{$head->email}}</td>
                                <td>{{$head->status==1 ? "Active":"Inactive"}}</td>
                                <td>{{$head->mobile_no}}</td>
                               
                                <td>{{$head->created_at}}</td>
                                <td>{{$head->last_login}}</td>
                                <td>
                                @if(Auth::user()->account_type=='superadmin')
                                    <form method="post" action="{{ route('login_as_member') }}" style="display: inline-block;">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $head->user_id }}">
                                    <button type="submit" class="btn btn-sm btn-rounded btn-primary">Login as Lead Head</button>
                                    </form> 
                                @endif
                                    <a href="{{url('change_status')}}/{{$head->user_id}}" class="btn btn-sm btn-rounded btn-warning">{{$head->status==1?"Deactive":"Active"}}</a>  
                                    <a href="{{route('editVerificationHead',[$head->user_id])}} " class="btn btn-sm btn-rounded btn-warning">Edit</a>  

                                </td>  
                              
                                
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
                {{$allHead->links()}}
            </div>
@endsection
@push('js')
<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/verification_head_list?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });
</script>
@endpush
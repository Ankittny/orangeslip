@extends('admin.layouts.app')
@section('content')
        
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif




<div class="pxp-dashboard-content-details">
<div class="">
    <h5>Search By</h5>
    <div class="">
        
        <form name="search" method="get" action="{{url('hr_list')}}">
        <div class="row">

            <div class="col-md-4 mb-3">
            <label class="control-label">{{ __('Keyword') }}</label>
                            <input type="text" name="keyword" id="keyword" class="form-control" value="{{ isset($searchData['keyword']) ? $searchData['keyword'] : "" }}">								
            </div>
            <div class="col-md-4 mb-3">
                <label class="control-label">{{ __('Email') }}</label>
                <input type="email" name="email" id="email"  class="form-control" value="{{ isset($searchData['email']) ? $searchData['email'] : "" }}">							
            </div>
            <div class="col-md-4 mb-3">
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
            
                         

        @if(Auth::user()->account_type=='superadmin')
            <div class="col-md-4 mb-3">
                <label class="control-label" >{{ __('Business') }}</label>
                <select name="business" id="business"  >
                    <option value="" selected>{{ __('All') }}</option>
                    @foreach($allBusiness as $business)
                    <option value="{{$business->id}}" @isset($searchData['business']){{  $searchData['business'] == $business->id ? "selected" : "" }}@endif>{{ $business->business->business_name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-4">
                <label class="control-label">&nbsp;</label>
                <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
            </div>
            <div class="col-md-4">
                <label class="control-label">&nbsp;</label>
                <button type="button" class="btn rounded-pill btn-block pxp-section-cta" id="reset" value="true">{{ __('Clear') }}</button>
            </div>
        </form>
    </div>
</div>
<hr>
    <h5>HR List</h5>
    <!-- <span class="pull-right"><a class="btn sm_btn rounded-pill pxp-section-cta" href="{{route('add_hr')}}">Create HR</a></span> -->
    <!-- <a href="{{url('export')}}/{{$allHr}}" class="btn sm_btn rounded-pill pxp-section-cta">Export</a> -->
    <button type="button" class="btn expBtn btn-sm btn-rounded" >Export</button>
    
    <div class="table-responsive">             
        <table class="table align-middle footable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th data-breakpoints="xs">Contact No</th>               
                    <th data-breakpoints="xs sm">Business</th>
                    <th data-breakpoints="xs sm ">Status</th>
                    <th data-breakpoints="xs sm md lg">Access</th>
                    <th data-breakpoints="xs sm md lg">Created Date</th>
                    <th data-breakpoints="xs sm md lg">Last Login</th>
                    <th data-breakpoints="xs sm md lg">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allHr as $hr)
                <tr>
                    <td>{{strtoupper($hr->first_name.' '.$hr->last_name)}}</td>
                    <td>{{strtoupper($hr->email)}}</td>
                    <td>{{$hr->mobile_no}}</td>               
                    <td>{{strtoupper($hr->Parent->business->business_name)}}</td>   
                    <td>{{$hr->status == 1 ? "Active":"Inactive"}}</td>             
                    <td> 
                        @foreach($hr->userAccess as $usac=> $acc) 
                            #{{$acc->title}},
                            
                        @endforeach
                    </td>                
                    
                    
                    <td>{{$hr->created_at}}</td>
                    <td>{{$hr->last_login}}</td>
                    <td>
                        @if(Auth::user()->account_type=='superadmin')
                        <form method="post" action="{{ route('login_as_member') }}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $hr->user_id }}">
                            <button type="submit" class="btn btn-sm btn-rounded btn-primary">Login as HR</button>
                        </form> 
                        @endif
                        <a href="{{url('credit_amount')}}/{{$hr->user_id}}" class="btn btn-sm btn-rounded btn-success">Credit</a>
                        <a href="{{url('debit_amount')}}/{{$hr->user_id}}" class="btn btn-sm btn-rounded btn-danger">Debit</a>  
                        
                        <a href="{{url('editHr')}}/{{$hr->user_id}}" class="btn btn-sm btn-rounded btn-warning">Edit</a>  
                        <a href="{{url('change_status')}}/{{$hr->user_id}}" class="btn btn-sm btn-rounded btn-warning" onclick="return confirm('Are you sure to {{$hr->status==1 ? 'Deactive':'Active'}}?');">{{$hr->status==1?"Deactive":"Active"}}</a>  
                        <a href="{{url('transaction')}}?user={{$hr->user_id}}" class="btn btn-sm btn-rounded btn-warning">Transaction</a>  
                         
                    </td>
                </tr>
                
            
                @endforeach
                
            </tbody>
        </table>
    </div>
    {{$allHr->links()}}
</div>
@endsection
@push('js')
<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/hr_list?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });
</script>
@Endpush
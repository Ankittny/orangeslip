@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
<!-- <link href="{{asset('css/pages/footable-page.css')}}" rel="stylesheet">
<link href="{{asset('css/pages/other-pages.css')}}" rel="stylesheet"> -->

    <div class="pxp-dashboard-content-details">
    <h1>Transactions</h1>
           

            <div class="">
                <div class="">
                    <form name="search" method="get" action="{{url('transaction')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('Transaction ID') }}</label>
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control" value="{{ isset($searchData['transaction_id']) ? $searchData['transaction_id'] : "" }}">							
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('Transaction Type') }}</label>
                            <select name="type" id="type" >
                                <option value="">{{ __('All') }}</option>
                                <option value="Credit"  @isset($searchData['type']){{  $searchData['type'] == 'Credit' ? "selected" : "" }}@endif>{{ __('Credit') }}</option>
                                <option value="Debit" @isset($searchData['type']){{  $searchData['type'] == 'Debit' ? "selected" : "" }}@endif>{{ __('Debit') }}</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('Status') }}</label>
                            <select name="status" id="status" >
                                <option value="">{{ __('All') }}</option>
                                <option value="1" @isset($searchData['status']){{  $searchData['status'] == 1 ? "selected" : "" }}@endif>{{ __('Success') }}</option>
                                <option value="0"  @isset($searchData['status']){{  $searchData['status'] == 0 ? "selected" : "" }}@endif>{{ __('Pending') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('From  Date') }}</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ isset($searchData['from_date']) ? $searchData['from_date'] : "" }}">							
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('To Date') }}</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ isset($searchData['to_date']) ? $searchData['to_date'] : "" }}">							
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">&nbsp;</label>
                            <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">&nbsp;</label>
                            <button type="button" class="btn rounded-pill btn-block pxp-section-cta" id="reset" value="true">{{ __('Reset') }}</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <hr>
            <button type="button" class="btn expBtn sm_btn rounded-pill pxp-section-cta" >Export</button>
            <div class="">
                <div class="">
                    <div class="">
                        <h5>Balance : <i class="fa fa-inr"></i> {{Auth::user()->balance}}</h5>
                        <div class="table-responsive">
                            <table id="demo-foo-addrow" class="footable table contact-list">
                                <thead>
                                    <tr>
                                    <th>Transaction ID</th>
                                    <th data-breakpoints="xs">Username</th>                                                                                       
                                    <th data-breakpoints="xs sm">Type</th>
                                    <th data-breakpoints="xs sm">Source</th>
                                    <th data-breakpoints="xs sm">Description</th>
                                    <th data-breakpoints="xs sm md lg">Amount </th>
                                    <th data-breakpoints="xs sm md lg">Updated Balance </th>
                                    <th data-breakpoints="xs sm md lg">Date</th>
                                    <th data-breakpoints="xs sm md lg">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     
                                    @foreach($transaction as $t)
                                    <tr>
                                        <td>{{$t->transaction_id}}</td>
                                        @if(Auth::user()->account_type=='superadmin')
                                        <td>{{$t->user->business ? $t->user->business->business_name : $t->user->Parent->business->business_name}}</td>     
                                        @else
                                        <td>{{$t->user->business ? $t->user->business->business_name : ($t->user->first_name.' '.$t->user->last_name)}}</td>     
                                        @endif                                           
                                        <td>{{$t->type}}</td>
                                        <td>{{$t->source}}</td>
                                        <td>{{$t->description}}</td>
                                        <td><i class="fa fa-inr"></i> {{$t->amount}}</td>
                                        <td><i class="fa fa-inr"></i> {{$t->updated_balance}}</td>
                                        <td>{{$t->created_at}}</td>
                                        <td>{{ ($t->status == '1' ? "Success":"Fail") }}  </td>                                        
                                    </tr>
                                    @endforeach
                                   
                                </tbody>
                            </table>
                                    {{ $transaction->links() }}
                        </div>
                    </div>
                </div>
            </div>
    </div>

@endsection

@push('js')
<script>
    $('#reset').on('click',function(){
         
        $("#transaction_id").val('');
        $("#from_date").val('');
        $("#to_date").val('');
        $("#type")[0].selectize.clear();
        
        $("#status")[0].selectize.clear();
    });
</script>
<!-- <script src="{{asset('js/pages/moment.js')}}"></script>
<script src="{{asset('js/pages/footable.min.js')}}"></script>
<script src="{{asset('js/pages/footable-init.js')}}"></script> -->
<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/transaction?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });
</script>
@endpush

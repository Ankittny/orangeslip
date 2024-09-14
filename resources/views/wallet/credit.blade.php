@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
<div class="container-fluid">
               
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Credit Balance</h4>
                        
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <!-- <ol class="breadcrumb">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item">Credit Balance</li>
                            </ol> -->
                            Balance:  <i class="fa fa-inr"></i> {{Auth::user()->balance}}
                            
                        </div>
                    </div>
                </div>
                <div class="sm_box">
                    <div class="card">
                        <div class="card-body">
                                         
                            <p>Creditor Details: Name: <strong>{{$user->first_name}}</strong>, Email: <strong>{{$user->email}}</strong>,  Type: <strong>{{$user->account_type}}</strong></p>
                            <form method="POST" action="{{ url('credit_amount') }}/{{$id}}">
                                @csrf

                                    <input type="hidden" name="user_id" value="{{$id}}">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationServer01">Balance (<i class="fa fa-inr"></i>)</label>
                                        <input type="text"  id="updated_balance"  name="updated_balance" value="{{ $updated_balance }}" class="form-control "  placeholder="Package name" readonly>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="validationServer01">Amount (<i class="fa fa-inr"></i>)*</label>
                                        <input type="number"  id="amount"  name="amount" class="form-control "  placeholder="Amount" required>
                                        @if ($errors->has('amount'))
                                            <label class="text-danger">{{ $errors->first('amount') }}</label>
                                        @endif 
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="validationServer03">Description *</label>
                                        <textarea  class="form-control " id="description" name="description"   row="3" required></textarea>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-block" type="submit">Add</button>

                                <a href="{{url('hr_list')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
                            </form>
                        </div>
                    </div>
                </div>
</div>

@endsection

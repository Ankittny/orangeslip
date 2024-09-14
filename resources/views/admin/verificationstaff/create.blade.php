@extends('admin.layouts.app')
@section('content')
<div class="pxp-dashboard-content-details">
    <h1>Add Verification Staff</h1>
    

    <form action="{{ route('storeVerificationStaff') }}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="first_name" class="form-label {{ $errors->has('first_name')?' has-error':' has-feedback' }}">First Name *</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter First Name" value="{{ old('first_name')}}" required>
                    @if ($errors->has('first_name'))
                        <label class="text-danger">{{ $errors->first('first_name') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="last_name {{ $errors->has('last_name')?' has-error':' has-feedback' }}" class="form-label">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter Last Name" value="{{ old('last_name')}}" required>
                    @if ($errors->has('last_name'))
                        <label class="text-danger">{{ $errors->first('last_name') }}</label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">           
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email {{ $errors->has('email')?' has-error':' has-feedback' }}" class="form-label">Email *</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email" value="{{ old('email')}}" required>
                    @if ($errors->has('email'))
                        <label class="text-danger">{{ $errors->first('email') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label  class="form-label">Country *</label>
                     
                    <select   name="country" id="country"  required>                                                                    
                        @foreach($country as $c)
                        <option value="{{$c->id}}" {{$c->id==69?"selected":""}} > {{$c->name}} (+{{$c->calling_code}})</option>
                        @endforeach                                            
                    </select>    
                     
                </div>
                    @if ($errors->has('mobile_no'))
                        <label class="text-danger">{{ $errors->first('country') }}</label>
                    @endif
                </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="mobile_no {{ $errors->has('mobile_no')?' has-error':' has-feedback' }}" class="form-label">Mobile No *</label>
                    <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Enter Mobile No" value="{{ old('mobile_no')}}" required>
                    @if ($errors->has('mobile_no'))
                        <label class="text-danger">{{ $errors->first('mobile_no') }}</label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender *</label>
                    <select name="gender" id="gender" required>
                        <option value="">Select Option</option>
                        <option value="male" {{old('gender')=='male' ? 'selected':''}}>Male</option>
                        <option value="female" {{old('gender')=='female' ? 'selected':''}}>Female</option>
                        <option value="other" {{old('gender')=='other' ? 'selected':''}}>Other</option>
                    </select>
                </div>
                @if ($errors->has('gender'))
                        <label class="text-danger">{{ $errors->first('gender') }}</label>
                    @endif
            </div>

            @if(Auth::user()->account_type=='superadmin')
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="head" class="form-label">Verification Head *</label>
                    <select name="head" id="head" required>
                        <option value="">Select Head</option>
                        @foreach($allHead as $head)
                        <option value="{{$head->id}}" {{old('head')==$head->id ? 'selected':''}}>{{$head->first_name.' '.$head->last_name}} ({{$head->email}})</option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('gender'))
                        <label class="text-danger">{{ $errors->first('gender') }}</label>
                    @endif
            </div>
            @endif
           
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password {{ $errors->has('password')?' has-error':' has-feedback' }}" class="form-label">Password *</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required>
                    @if ($errors->has('password'))
                        <label class="text-danger">{{ $errors->first('password') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password_confirmation {{ $errors->has('password_confirmation')?' has-error':' has-feedback' }}" class="form-label">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Enter Confirm Password" required>
                    @if ($errors->has('password_confirmation'))
                        <label class="text-danger">{{ $errors->first('password_confirmation') }}</label>
                    @endif
                </div>
            </div>
        </div>
       
        <div class="mt-3 mt-lg-3">
            <button class="btn rounded-pill pxp-section-cta">Save</button>
        </div>
    </form>
</div>
@endsection
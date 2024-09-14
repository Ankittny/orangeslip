@extends('admin.layouts.app')
@section('content')
<div class="pxp-dashboard-content-details">
     
    <div class="d-flex justify-content-between">
        <h4 class="text-themecolor">Edit Lead Staff</h4>
        <a href="{{url('lead_staff_list')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
    </div>
    

    <form action="{{url('update_lead_staff')}}/{{$user->id}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="first_name" class="form-label {{ $errors->has('first_name')?' has-error':' has-feedback' }}">First Name *</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter First Name" value="{{ old('first_name', $user->first_name) }}" required>
                    @if ($errors->has('first_name'))
                        <label class="text-danger">{{ $errors->first('first_name') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="last_name {{ $errors->has('last_name')?' has-error':' has-feedback' }}" class="form-label">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter Last Name" value="{{old('last_name',$user->last_name)}}" required>
                    @if ($errors->has('last_name'))
                        <label class="text-danger">{{ $errors->first('last_name') }}</label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">           
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="email {{ $errors->has('email')?' has-error':' has-feedback' }}" class="form-label">Email *</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email" value="{{old('email',$user->email)}}" required>
                    @if ($errors->has('email'))
                        <label class="text-danger">{{ $errors->first('email') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="country" class="form-label">Country *</label>
                    <select   name="country" id="country"  required>                                                                    
                        @foreach($country as $c)
                        <option value="{{$c->id}}"  {{ old('country', $user->profile->country) == $c->id ? 'selected' : '' }}> {{$c->name}} (+{{$c->calling_code}})</option>
                        @endforeach                                            
                    </select>
                   
                    @if ($errors->has('country'))
                        <label class="text-danger">{{ $errors->first('country') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="mobile_no {{ $errors->has('mobile_no')?' has-error':' has-feedback' }}" class="form-label">Mobile No *</label>
                    <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Enter Mobile No" value="{{old('mobile_no',$user->profile->mobile_no)}}" required>
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
                        <option value="male" {{$user->profile->gender=='male' ? "selected":""}}>Male</option>
                        <option value="female" {{$user->profile->gender=='female' ? "selected":""}}>Female</option>
                        <option value="other" {{$user->profile->gender=='other' ? "selected":""}}>Other</option>
                    </select>
                </div>
                @if ($errors->has('gender'))
                        <label class="text-danger">{{ $errors->first('gender') }}</label>
                    @endif
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="registration_doc" class="form-label">Password (left blank for no update)</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="password">
                </div>
                @if ($errors->has('password'))
                        <label class="text-danger">{{ $errors->first('password') }}</label>
                    @endif
            </div>
            
       
        </div>
        
        
      

       
        <div class="mt-3 mt-lg-3">
            <button class="btn rounded-pill pxp-section-cta">Save</button>
        </div>
    </form>
</div>
@endsection
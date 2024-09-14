@extends('admin.layouts.app')
@section('content')

<div class="pxp-dashboard-content-details">
    <h1>Assign Role</h1>
    <p class="pxp-text-light">Choose a new account password.</p>

    <form action="{{ route('acl.assign_role.save') }}" method="post">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-6">
                <label for="user_id">{{ __('Select User') }}:</label>
                <select name="user_id" class="form-control" id="user_id">
                    <option value="">{{ __('Select') }}</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{ $user->first_name.' '.$user->last_name }}</option>
                    @endforeach								
                </select>
            </div>
            <div class="col-md-6{{ $errors->has('type')?' has-error':' has-feedback' }}">
                <label for="type">{{ __('Select Role') }}:</label>
                <select name="type" class="form-control" id="type">
                    <option value="">{{ __('Select') }}</option>
                    @foreach($roles as $role)
                        <option value="{{$role->name}}">{{ $role->title}}</option>
                    @endforeach	
                    <!-- <option value="superadmin">{{ __('Superadmin') }}</option>
                    <option value="hr">{{ __('HR') }}</option>
                    <option value="business">{{ __('Business') }}</option>
                    <option value="candidate">{{ __('Candidate') }}</option>									 -->
                </select>
            </div>
        </div>

        <div class="mt-3 mt-lg-3">
            <button class="btn rounded-pill pxp-section-cta">Save</button>
        </div>
    </form>
</div>

@endsection
@extends('admin.layouts.app')
@section('content')
<!-- Page content -->
<div class="secondary-nav">
    <ul class="nav nav-tabs">
        <li><a class="active" href="{{ url(Config::get('app.locale').'/home') }}">{{ __('Home') }}</a></li>
        <li>{{ __('Manage Roles') }}</li>
    </ul>
</div>
<br>
<div class="page-content">
    <div class="container-fluid">
        {{--@include('flash::message')--}}
        <h6 class="white-color">{{ __('Create a new Role') }}</h6>
        <div class="card-widget white">
            <form action="{{ route('acl.role.store') }}" method="post">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4{{ $errors->has('role_name')?' has-error':' has-feedback' }}">
                        <input type="text" name="role_name" class="form-control" placeholder="{{ __('Enter role name') }}">
                        @if($errors->has('role_name'))
                            <p class="help-block">{{ $errors->first('role_name') }}</p>
                        @endif
                    </div>
                    <div class="col-md-4{{ $errors->has('role_title')?' has-error':' has-feedback' }}">
                        <input type="text" name="role_title" class="form-control" placeholder="{{ __('Enter role title') }}">
                        @if($errors->has('role_title'))
                            <p class="help-block">{{ $errors->first('role_title') }}</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-block btn-orange">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

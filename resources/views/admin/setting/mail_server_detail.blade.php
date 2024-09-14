@extends('admin.layouts.app')

@section('content')


    <div class="pxp-dashboard-content-details">
        <h1>Settings</h1>
        <p class="pxp-text-light">Mail Server Details</p>
        @if(session('success'))                                
        <div class="alert alert-success" role="alert">
        {{session('success')}}
        </div>
        @endif
        @if(session('error'))                                
        <div class="alert alert-danger" role="alert">
        {{session('error')}}
        </div>
        @endif
        <form method="post" action="@if(empty($msd)){{route('mailServerSetting.store')}} @else {{route('mailServerSetting.update')}}@endif" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Mail Host *</label>
                        <input type="text" id="mail_host" name="mail_host" class="form-control" value="@isset($msd){{$msd->mail_host}}@endif"  required>                                
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Mail Port *</label>
                        <input type="text" id="mail_port" name="mail_port" class="form-control" value="@isset($msd){{$msd->mail_port}}@endif" required>                                
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Mail Username *</label>
                        <input type="text" id="mail_username" name="mail_username" class="form-control" value="@isset($msd){{$msd->mail_username}}@endif"  required>                                
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Mail Password *</label>
                        <input type="text" id="mail_password" name="mail_password" class="form-control" value="@isset($msd){{$msd->mail_password}}@endif" required>                                
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">From Address *</label>
                        <input type="text" id="from_address" name="from_address" class="form-control" value="@isset($msd){{$msd->from_address}}@endif"  required>                                
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">From Name *</label>
                        <input type="text" id="from_name" name="from_name" class="form-control" value="@isset($msd){{$msd->from_name}}@endif"  required>                                
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                    <label  class="form-label">&nbsp;</label>
                    <button class="btn rounded-pill pxp-section-cta btn-block">Save</button>
                    </div>
                </div> 
                   
            </div>
        </form>
    
         
    </div>
 

@endsection
@push('js')

@endpush
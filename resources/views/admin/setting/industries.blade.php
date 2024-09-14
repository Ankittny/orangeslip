@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif

    <div class="pxp-dashboard-content-details">
        <h1>Settings</h1>
        <p class="pxp-text-light">Add Industry</p>
        
        <form method="post" action="{{route('setting.manageIndustryStore')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                 
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Industry *</label>
                        <input type="text"   name="industry" class="form-control" required>                                
                    </div>                           
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                    <label  class="form-label">&nbsp;</label>
                    <button class="btn rounded-pill pxp-section-cta btn-block">Add</button>
                    </div>
                </div> 
                   
            </div>
        </form>
     <hr>
     <h5 class="mb-3">Industries</h5>
<div class="row g-2">
     @foreach($industries as $ind)
     <div class="col-md-4 col-lg-3 col-sm-6">    
    
        <div class="card-body card mb-2">
            <h6 class="mb-0" style="font-size: 14px;">{{$ind->name}}</h6>
            
        </div>
         
    </div>
    @endforeach 
</div>
     
        
    </div>
 
 
 
 
@endsection
 
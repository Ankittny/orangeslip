@extends('admin.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>


        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
    <div class="pxp-dashboard-content-details">
    <h4>Settings / <span>Offer Letter Template</span></h4>
        
        
        <form method="post" action="{{route('setting.manageOfferLetterUpdate')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Letter Head *</label>
                        <input type="file" name="letter_head" class="form-control" required>                                
                    </div>
                        @if($errors->has('letter_head'))
                            <label class="text-danger">{{ $errors->first('letter_head') }}</label>
                        @endif                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Template Name *</label>
                        <input type="text" name="temp_name" class="form-control" required>                                
                    </div>
                        @if($errors->has('temp_name'))
                            <label class="text-danger">{{ $errors->first('temp_name') }}</label>
                        @endif                           
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn rounded-pill pxp-section-cta btn-block">Add</button>
                    </div>
                </div> 
            </div>
            {{--<div class="row">    
                 
                 
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Content </label>
                        <textarea name="description" class="form-control" ></textarea>                                
                    </div>     
                                              
                </div>
            </div>--}}
            
               
                   
             
        </form>
     <hr>
        <!-- <table class="table align-middle"> -->
        <div class="table-responsive">
            <table id="example" class="table align-middle" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Template Name</th>
                        <th>Letter Head</th>     
                        @if(Auth::user()->account_type=='superadmin')
                        <th>Business Name</th>
                        @endif    
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                        @foreach($allTmp as $t)
                       
                        <td>{{$t->name}}</td>
                        <td>
                        <a href="{{ (url('images/'.$t->letter_head))}}" >
                            <img src="{{ ($t->letter_head!='')?(url('images/'.$t->letter_head)):(url('images/country_image/no_image.jpg')) }}" alt="" height="150px" width="150px">
                        </a>
                        </td>
                        @if(Auth::user()->account_type=='superadmin')
                        <td>
                        @php
                        $business=App\Models\BusinessDetail::where('user_id',$t->business_id)->pluck('business_name')->first();
                        @endphp
                        {{$business?$business:'SuperAdmin'}}
                        </td>
                        @endif
                    </tr>
                    @endforeach                
                </tbody>
            </table>
        </div> 
         
    </div>

 
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('#summernote').summernote();
    });
  </script>

@endpush
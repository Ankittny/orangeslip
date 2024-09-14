 @extends('admin.layouts.app')
@section('content')
<div class="pxp-dashboard-content-details">
    <h1>Create HR</h1>
    <p class="pxp-text-light">Add new HR.</p>

    <form action="{{ route('hrdetails.store') }}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        
        @if(Auth::user()->account_type=='superadmin')
         
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password-repeat" class="form-label">Business *</label>
                    <select  id="parent_id" name="parent_id"   required>
                        <option  value="" >Select business</option>
                        @foreach($all_business as $business)
                        <option  value='{{$business->id}}' {{ old('parent_id') == $business->id ? "selected" : "" }} >{{$business->business->business_name}}</option>
                        @endforeach
                    </select>
                </div>
                @if($errors->has('parent_id'))
                    <label class="text-danger">{{ $errors->first('parent_id') }}</label>
                @endif
            </div>
      
        @else
        <input type="hidden" name="parent_id" value="{{Auth::user()->id}}">
        @endif
        
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
                <div class="col-md-4">
                    <label for="email {{ $errors->has('email')?' has-error':' has-feedback' }}" class="form-label">Email *</label>
                    <div class="input-group mb-2" style="position:relative;">
                        
                        <input type="text" id="username" name="username" class="form-control"  placeholder="Username" value="{{old('username')}}">
                        
                        <div class="input-group-prepend" style="position:absolute; right:-10px; top:4px" id="mailDomain">
                        
                        </div>
                        <input type="hidden" id="domain" class="form-control"  value="">
                        <input type="hidden" id="email" name="email" class="form-control"  value="{{old('email')}}">
                        
                    </div>
                    @if ($errors->has('email'))
                            <label class="text-danger">{{ $errors->first('email') }}</label>
                        @endif
                </div>  
           {{--<div class="col-md-4">
                <div class="mb-3">
                    <label for="email {{ $errors->has('email')?' has-error':' has-feedback' }}" class="form-label">Email *</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email" value="{{old('email')}}" required>
                    @if ($errors->has('email'))
                        <label class="text-danger">{{ $errors->first('email') }}</label>
                    @endif
                     
                </div>                   
            </div> --}}
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="country {{ $errors->has('country')?' has-error':' has-feedback' }}" class="form-label">Country *</label>
                        <select  name="country" id="country"  required>                 
                            @foreach($country as $c)
                            <option value="{{$c->id}}" {{ old('country', 69) == $c->id ? 'selected' : '' }} >{{$c->name}} (+{{$c->calling_code}})</option>
                            @endforeach                                            
                        </select>      
                            @if ($errors->has('country'))
                                <label class="text-danger">{{ $errors->first('country') }}</label>
                            @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label  class="form-label">Mobile No *</label>
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
                        <option value="male" {{ old('gender') == "male" ? "selected" : "" }} >Male</option>
                        <option value="female" {{ old('gender') == "female" ? "selected" : "" }}>Female</option>
                        <option value="other" {{ old('gender') == "other" ? "selected" : "" }}>Other</option>
                    </select>
                </div>
                @if ($errors->has('gender'))
                        <label class="text-danger">{{ $errors->first('gender') }}</label>
                    @endif
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="desg {{ $errors->has('desg')?' has-error':' has-feedback' }}" class="form-label">Designation *</label>
                    <input type="text" id="desg" name="desg" class="form-control" placeholder="Enter Designation" value="{{ old('desg')}}" required>
                    @if ($errors->has('desg'))
                        <label class="text-danger">{{ $errors->first('desg') }}</label>
                    @endif
                </div>
            </div>

            
        </div>
        
        
       
        
        <div class="row">   
        

            
            
        </div>

        <hr>
         <div class="row custom_chk">
         <label class="form-label">Access</label>
            @foreach($all_access as $key=>$access)
            <div class="col-md-3">
                <div class="mb-3">
                
                     @php
                     //dd($access->id,old(per[$access->id]));
                     @endphp
                     <input type="checkbox"  name="per[{{$access->id}}]" value="1" {{ (is_array(old('per')) and array_key_exists($access->id,old('per'))) ? ' checked' : '' }}> 
                    <label  class="form-label">{{$access->title}}</label>

                </div>
            </div>
            @endforeach
           
            <!-- <div class="col-md-3">
                <div class="mb-3">
                    <label for="edit_candidate" class="form-label">Edit Candidate</label>
                    <input type="checkbox" id="edit_candidate" name="edit_candidate" value="1">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="view_candidate" class="form-label">View Candidate</label>
                    <input type="checkbox" id="view_candidate" name="view_candidate" value="1">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="bulk_upload" class="form-label">Bulk Upload</label>
                    <input type="checkbox" id="bulk_upload" name="bulk_upload" value="1">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="create_offer_letter" class="form-label">Create Offer Letter</label>
                    <input type="checkbox" id="create_offer_letter" name="create_offer_letter" value="1">
                </div>
            </div> -->
        </div> 
        <hr>

        <div class="mt-3 mt-lg-3">
            <button class="btn rounded-pill pxp-section-cta">Save</button>
        </div>
    </form>
</div>
@endsection
@push('script')
<script>
      $( document ).ready(function() {
        var business={{Auth::user()->id}};
        @if(Auth::user()->account_type=='business')
        var business_id=business;
        @else
        var  business_id =$("#parent_id option:selected").val();
        @endif
        getDomain(business_id);
         
      });
      $('#parent_id').change(function(){

            var  business_id = $("#parent_id option:selected").val();
            getDomain(business_id);
            
        
        });
      function getDomain(business_id){
        business_id = business_id;
                $.ajax({
                type:'GET',
                url:"{{url('get_domain')}}",
                data:'business_id='+business_id,
                success: function(response) {
                     
                    $('#mailDomain').html('<div class="input-group-text">@'+response+'</div>');
                    $('#domain').val('@'+response);
                }
            });
        } 
    $(document).ready(function(){
        $("#username").on('keyup',function(){
        var username = $('#username').val();
        var domain = $('#domain').val();
        $('#email').val(username+domain);
        });
    });
</script>
@endpush
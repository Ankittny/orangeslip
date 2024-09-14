@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif

<div class="pxp-dashboard-content-details">
                <h1>Change Password</h1>
                 
                
                <form method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <p class="statusMsg"></p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label  class="form-label">Old Password *</label>
                                <input type="password" id="old_password" name="old_password" class="form-control" placeholder="Enter Old Password" value="{{ old('old_password') }}" required>
                            </div>
                            <p class="error_old_password qerr"></p>
                             
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label  class="form-label">New Password *</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter New Password" value="{{ old('new_password') }}" required>
                            </div>
                            <p class="error_password qerr"></p>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label  class="form-label">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" maxlength="10" placeholder="Confirm Password"  required>
                            </div>
                            <p class="error_password_confirmation qerr"></p>
                        </div>
                       
                    </div>
                    
                     
                    <button type="button" id="btn6" class="btn rounded-pill pxp-section-cta btn-block" onClick="updatePass();">Save</button>
                    
                    
                        
                     
                </form>
            </div>

@endsection
@push('js')
<script>
//     $( document ).ready(function() {
//     alert('hello');
// });

function updatePass()
    {
        //alert(1);
        $('.qerr').html('');
        $('.statusMsg').html('');
     
        var token='{{ csrf_token() }}';
        var old_password = $('#old_password').val(); 
        var password = $('#password').val(); 
        var password_confirmation = $('#password_confirmation').val();
        
        $.ajax({
            type:'POST',
            url:"{{url('change_password')}}",
            data:'_token='+token+'&old_password='+old_password+'&password='+password+'&password_confirmation='+password_confirmation,

           
            success: function(response) {
                $('#old_password').val(''); 
                $('#password').val(''); 
                $('#password_confirmation').val(''); 
               if(response==1)
               {
                $('.statusMsg').html('<span style="color:green;">Password Updated  Successfully!.</span>');
                setTimeout(function() 
                    {
                        location.href= "/dashboard";
                         
                    }, 2000); 
                
               }
               else
                {
                    $('.statusMsg').html('<span style="color:red;">'+response+'</span>');
                }
            },
            error: function (reject) {
           
                if( reject.status === 422 ) {
                    //console.log(reject);
                    var resp = $.parseJSON(reject.responseText);
                    $.each(resp.errors, function (key, val) {
                        console.log(key,val);
                        $('.error_'+key).html(val[0]).css("color","red","display","show");
                        $( key ).text(val[0]);
                    });
                }
            }
        });
    }

    

</script>
@endpush
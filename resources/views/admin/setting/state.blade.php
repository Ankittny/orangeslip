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
        <p class="pxp-text-light">State</p>
        
        <form method="post" action="{{route('setting.manageStateStore')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Country *</label>
                        <select  name="country"  required>
                        <option value="69">India</option>
                         

                        </select>                                
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">State *</label>
                        <input type="text"   name="state_title" class="form-control" required>                                
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
     <hr>
     <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Action</th>    
                    <th>State Name</th>
                    <th>Status</th>                
                     
                </tr>
            </thead>
            <tbody>                
                <tr>
                    @foreach($allState as $state)
                    <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" onclick="getStateDetails({{$state->state_id}})">Edit</button></td>  
                    <td>{{$state->state_title}}</td>
                    <td>{{$state->status}}</td>
                    
                </tr>
                @endforeach                
            </tbody>
        </table>
    </div>
        {{$allState->links()}}
    </div>
 
<!-- Popup Modal-->
 
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update State</h4>
        
            <form role="form" method="post">
                @csrf
                <div class="form-group">
                    <input type="hidden" id="state_id" value="">
                    <div class="mb-3">
                        <label   class="form-label">State Name *</label>
                        <input type="text" id="state_title"  class="form-control" value="">                                
                    </div>
                    
                    <div class="mb-3">            
                        <label for="status" class="form-label">Status *</label>
                        <select  id="status" >
                        <option value="">Select</option>
                        <option value="Active" >Active</option>
                        <option value="Inactive" >Inactive</option>
                        </select> 
                    </div>                  
                </div>                   
            </form>
            <p class="statusMsg"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>   
            <button type="button" class="btn btn-success " id="btn1" onClick="submitEditForm()">SUBMIT</button>        
        </div>
    </div>
  </div>
</div>  
@endsection
@push('js')
  
<script> 
    var state= $("#status").selectize();
</script>
<script>
    function getStateDetails(s_id)
    {
        var state_id = s_id;
        var token='{{ csrf_token() }}';
        $.ajax({
            type:'GET',
            url:"{{url('get_state_details')}}",
            data:'id='+state_id,
            success: function(response) {                                
                $('#state_id').val(response.state_id);
                $('#state_title').val(response.state_title);
                 
                
                
                state[0].selectize.setValue(response.status);
                
                selectize.refreshOptions();    
    
                console.log(response);
            }
        });
    }
  
    function submitEditForm(){
 
 var state_id=$('#state_id').val();
 var state_title=$('#state_title').val();
  
 var status=$('#status').val();
 var token='{{ csrf_token() }}';
 //console.log(staff_id);
 $.ajax({
     type:'POST',
     url:"{{ url('update_state_details') }}",
     data:'_token='+token+'&state_id='+state_id+'&state_title='+state_title+'&status='+status,
     beforeSend: function () {
         $('.submitBtn').attr("disabled","disabled");
        //  $('.modal-body').css('opacity', '.5');
     },
     success:function(msg){
         //console.log(msg);
         if(msg == 'ok'){
             $('#btn1').hide();
             
             $('.statusMsg').html('<span style="color:green;">State Updated Successfully.</p> Redirecting.....');
                 setTimeout(function() 
                 {
                     location.reload();  //Refresh page
                 }, 1000);
         }else{
             $('.statusMsg').html('<span style="color:red;">'+ msg +'</span>');
         }
         
     }
 });

}

</script>
@endpush
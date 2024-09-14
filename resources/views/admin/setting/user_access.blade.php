@extends('admin.layouts.app')

@section('content')


    <div class="pxp-dashboard-content-details">
        <h1>Settings</h1>
        <p class="pxp-text-light">User Access</p>
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
        <form method="post" action="{{route('setting.userAccess.store')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Access Name *</label>
                        <input type="text" id="access_name" name="access_name" class="form-control" required>                                
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Access Title *</label>
                        <input type="text" id="access_title" name="access_title" class="form-control" required>                                
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
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                        @foreach($access as $ac)
                        <td>{{$ac->name}}</td>
                        <td>{{$ac->title}}</td>
                        <td>{{$ac->status==1?"Active":"Inactive"}}</td>
                        <!-- <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" onclick="getUserAccess({{$ac->id}})">Edit</button></td> -->
                        
                    </tr>
                    @endforeach                
                </tbody>
            </table>
        </div>
        {{$access->links()}}
    </div>
 
<!-- Popup Modal-->
<!--
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update Salary Componenet</h4>
        <p class="statusMsg"></p>
            <form role="form" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" id="acc_id" value="">
                    <div class="mb-3">
                        <label   class="form-label">Access Name *</label>
                        <input type="text" id="access_nameu" name="access_name" class="form-control" value="">                                
                    </div>
                    <div class="mb-3">
                        <label   class="form-label">Access Title *</label>
                        <input type="text" id="access_titleu" name="access_titleu" class="form-control" value="">                                
                    </div>
                                   
                    
                    
                    <div class="mb-3">            
                        <label for="status" class="form-label">Status *</label>
                        <select  id="acc_status" class="form-controll">
                        <option value="">Select</option>
                        <option value="1" >Active</option>
                        <option value="0" >Inactive</option>
                        </select> 
                    </div>                  
                </div>                   
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>   
            <button type="button" class="btn btn-success " id="btn1" onClick="submitEditForm()">SUBMIT</button>        
        </div>
    </div>
  </div>
</div> -->
@endsection
@push('js')
<!-- 
<script> 
    var accs= $("#acc_status").selectize();
</script>
<script>
    function getUserAccess(acc_id)
    {
        var access_id = acc_id;
        var token='{{ csrf_token() }}';
        $.ajax({
            type:'GET',
            url:"{{url('get_user_access')}}",
            data:'id='+access_id,
            success: function(response) {                                
                $('#acc_id').val(response.id);
                $('#access_nameu').val(response.name);
                $('#access_titleu').val(response.title);
                
                
                acc[0].selectize.setValue(response.status);
                
                selectize.refreshOptions();    
    
                console.log(response);
            }
        });
    }
  
    function submitEditForm(){
 
 var comp_id=$('#comp_id').val();
 var component1=$('#component1').val();
 var category1=$('#category1').val();
 var com_status=$('#com_status').val();
 var token='{{ csrf_token() }}';
 //console.log(staff_id);
 $.ajax({
     type:'POST',
     url:"{{ url('update_user_access') }}",
     data:'_token='+token+'&comp_id='+comp_id+'&component1='+component1+'&category1='+category1+'&com_status='+com_status,
     beforeSend: function () {
         $('.submitBtn').attr("disabled","disabled");
         $('.modal-body').css('opacity', '.5');
     },
     success:function(msg){
         //console.log(msg);
         if(msg == 'ok'){
             $('#btn1').hide();
             
             $('.statusMsg').html('<span style="color:green;">Salary Comopnent Updated Successfully.</p>');
                 setTimeout(function() 
                 {
                     location.reload();  //Refresh page
                 }, 2000);
         }else{
             $('.statusMsg').html('<span style="color:red;">'+ msg +'</span>');
         }
         
     }
 });

}

</script> -->
@endpush
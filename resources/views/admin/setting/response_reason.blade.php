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
        <p class="pxp-text-light">Response Reason</p>
        
        <form method="post" action="{{route('setting.responseReason.store')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Reason *</label>
                        <input type="text" id="reason" name="reason" class="form-control" required>                                
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
                    <th>Reason</th>
                    <th>Status</th>
                    
                </tr>
            </thead>
            <tbody>                
                <tr>
                    @foreach($allReason as $role)
                    <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" onclick="getResponse({{$role->id}})">Edit</button></td>
                    <td>{{$role->title}}</td>
                    <td>{{$role->status==1?"Active":"Inactive"}}</td>
                </tr>
                @endforeach                
            </tbody>
        </table>
    </div>
        {{$allReason->links()}}
    </div>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update Response Reason</h4>
        
            <form role="form" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="roll_id" value=""> 
                        <div class="mb-3">
                        <label for="roll_name" class="form-label">Reason *</label>
                        <input type="text" id="roll_name" name="roll_name" class="form-control" value="">                                
                    </div>
                    <div class="mb-3">            
                        <label for="roll_status" class="form-label">Status *</label>
                        <select  id="roll_status" >
                        <option value="">Select</option>
                        <option value="1" >Active</option>
                        <option value="0" >Inactive</option>
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
    function getResponse(roll_id)
    {
        var job_roll_id = roll_id;
        var token='{{ csrf_token() }}';
        $.ajax({
            type:'GET',
            url:"{{url('get_res_reason')}}",
            data:'id='+job_roll_id,
            success: function(response) {
                //$('#roll_name').html(response.name);
                $('#roll_id').val(response.id);
                $('#roll_name').val(response.title);
                //$('#roll_status').val(response.status).change();
                test[0].selectize.setValue(response.status);
               
    
                console.log(response);
            }
        });
    }

    function submitEditForm(){
 
        var roll_id=$('#roll_id').val();
        var roll_name=$('#roll_name').val();
        var roll_status=$('#roll_status').val();
        var token='{{ csrf_token() }}';
        //console.log(staff_id);
        $.ajax({
            type:'POST',
            url:"{{ url('update_res_reason') }}",
            data:'_token='+token+'&roll_id='+roll_id+'&roll_name='+roll_name+'&roll_status='+roll_status,
            beforeSend: function () {
                $('.submitBtn').attr("disabled","disabled");
                // $('.modal-body').css('opacity', '.5');
            },
            success:function(msg){
                //console.log(msg);
                if(msg == 'ok'){
                    $('#btn1').hide();
                    
                    $('.statusMsg').html('<span style="color:green;">Response Reason Updated Successfully.</p> Redirecting.....');
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
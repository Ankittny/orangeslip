@extends('admin.layouts.app')

@section('content')

        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
    <div class="pxp-dashboard-content-details">
    <h4>Settings / <span>Designation</span></h4>
        
        
        <form method="post" action="{{route('setting.designation.store')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Designation *</label>
                        <input type="text" id="designation" name="designation" class="form-control" required>                                
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn rounded-pill pxp-section-cta btn-block">Save</button>
                    </div>
                </div> 
                   
            </div>
        </form>
     
        <!-- <table class="table align-middle"> -->
        <div class="table-responsive">
            <table id="example" class="table align-middle" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Designation</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                        @foreach($allDesg as $desg)
                        <td><button type="button" class="assignbtn btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$desg->name}}" onclick="getDesg({{$desg->id}})">Edit</button></td>
                        <td>{{$desg->name}}</td>
                        <td>{{$desg->status==1?"Active":"Inactive"}}</td>
                    </tr>
                    @endforeach                
                </tbody>
            </table>
        </div>
        {{$allDesg->links()}}
    </div>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update Designation</h4>
        
            <form role="form" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="desg_id" value=""> 
                        <div class="mb-3">
                        <label for="desg_name" class="form-label">Designation *</label>
                        <input type="text" id="desg_name" name="desg_name" class="form-control" value="">                                
                    </div>
                    <div class="mb-3">            
                        <label for="desg_status" class="form-label">Status *</label>
                        <select  id="desg_status" >
                        <option value="">Select</option>
                        <option value="1" >Active</option>
                        <option value="2" >Inactive</option>
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
    function getDesg(desg_id)
    {
        var desg_id = desg_id;
        var token='{{ csrf_token() }}';
        
         
        

        
        $.ajax({
            type:'GET',
            url:"{{url('get_designation')}}",
            data:'id='+desg_id,
            success: function(response) {
                //$('#roll_name').html(response.name);
                $('#desg_id').val(response.id);
                $('#desg_name').val(response.name);
                //$('#roll_status').val(response.status).change();
                test[0].selectize.setValue(response.status);
               
    
                console.log(response);
            }
        });
    }

    function submitEditForm(){
 
        var desg_id=$('#desg_id').val();
        var desg_name=$('#desg_name').val();
        var desg_status=$('#desg_status').val();
        var token='{{ csrf_token() }}';
        //console.log(staff_id);
        $.ajax({
            type:'POST',
            url:"{{ url('update_designation') }}",
            data:'_token='+token+'&desg_id='+desg_id+'&desg_name='+desg_name+'&desg_status='+desg_status,
            beforeSend: function () {
                $('.submitBtn').attr("disabled","disabled");
                //$('.modal-body').css('opacity', '.5');
            },
            success:function(msg){
                //console.log(msg);
                if(msg == 'ok'){
                    $('#btn1').hide();
                    
                    $('.statusMsg').html('<span style="color:green;">Designation Updated Successfully.</p> Redirecting.....');
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
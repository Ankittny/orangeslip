@extends('admin.layouts.app')

@section('content')

        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
    <div class="pxp-dashboard-content-details">
    <h4>Settings / <span>Document Type</span></h4>
        
        
        <form method="post" action="{{url('manage_document_type')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Document Type *</label>
                        <input type="text" id="name" name="name" class="form-control" required>                                
                    </div>    
                    @if ($errors->has('name'))
                        <label class="text-danger">{{ $errors->first('name') }}</label>
                    @endif                       
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
                        <th>Job Role</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                        @foreach($allTypes as $type)
                        <td><button type="button" class="assignbtn btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$type->name}}" onclick="getDocType({{$type->id}})">Edit</button></td>
                        <td>{{$type->name}}</td>
                        <td>{{$type->status==1?"Active":"Inactive"}}</td>
                    </tr>
                    @endforeach                
                </tbody>
            </table>
        </div>
        {{$allTypes->links()}}
    </div>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update Document Type</h4>
        
            <form role="form" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="type_id" value=""> 
                        <div class="mb-3">
                        <label  class="form-label">Document Type *</label>
                        <input type="text" id="type_name" name="type_name" class="form-control" value="">                                
                    </div>
                    <div class="mb-3">            
                        <label for="roll_status" class="form-label">Status *</label>
                        <select  id="type_status" >
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
    function getDocType(dt_id)
    {
        var doc_type_id = dt_id;
        var token='{{ csrf_token() }}';
        
         
        

        
        $.ajax({
            type:'GET',
            url:"{{url('get_doc_type')}}",
            data:'id='+doc_type_id,
            success: function(response) {
                //$('#roll_name').html(response.name);
                $('#type_id').val(response.id);
                $('#type_name').val(response.name);
                //$('#roll_status').val(response.status).change();
                test[0].selectize.setValue(response.status);
               
    
                console.log(response);
            }
        });
    }

    function submitEditForm(){
 
        var type_id=$('#type_id').val();
        var type_name=$('#type_name').val();
        var type_status=$('#type_status').val();
        var token='{{ csrf_token() }}';
        //console.log(staff_id);
        $.ajax({
            type:'POST',
            url:"{{ url('update_doc_type_details') }}",
            data:'_token='+token+'&type_id='+type_id+'&type_name='+type_name+'&type_status='+type_status,
            beforeSend: function () {
                $('.submitBtn').attr("disabled","disabled");
                //$('.modal-body').css('opacity', '.5');
            },
            success:function(msg){
                //console.log(msg);
                if(msg == 'ok'){
                    $('#btn1').hide();
                    
                    $('.statusMsg').html('<span style="color:green;">Doc Type Updated Successfully..</p> Redirecting.....');
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
@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
<div class="candidate_List_box">
    <h4>Settings / <span>Matrix Attributes</span></h4>
    
    <form method="post" action="{{route('matrixAttributeSave')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Attribute Name *</label>
                    <input  name="att_name" class="form-control" required>
                                                   
                </div>                           
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Attribute Title *</label>
                    <input type="text"   name="att_title" class="form-control" required readonly>                                
                </div>                           
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Minimum Point *</label>
                    <input type="text"   name="min_point" class="form-control" required>                                
                </div>                           
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">Maximum Point *</label>
                    <input type="text"   name="max_point" class="form-control" required>                                
                </div>                           
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="mb-3">
                    <label  class="form-label">&nbsp;</label>
                    <button class="btn rounded-pill pxp-section-cta btn-block">Save</button>
                    </div>
                </div>                 
            </div>                 
        </div>
    </form>
                
</div>
            

    <div class="pxp-dashboard-content-details">
        <div class="candidate_List_box_inner">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Attribute Name</th>
                            <th>Attribute Title</th>
                            <th>Min Point</th>
                            <th>Max Point</th>
                            
                            
                        </tr>
                    </thead>
                    <tbody>                
                        <tr>
                            @foreach($attributes as $att)
                            <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" onclick="getAttributeDetails({{$att->id}})">Edit</button></td>
                            <td>{{$att->name}}</td>
                            <td>{{$att->title}}</td>
                            <td>{{$att->min_point}}</td>
                            <td>{{$att->max_point}}</td>
                            
                        </tr>
                        @endforeach                
                    </tbody>
                </table>
            </div>
                {{$attributes->links()}}
        </div>
 
<!-- Popup Modal-->
 
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update Attribute</h4>
        
            <form role="form" method="post">
                @csrf
                <div class="form-group">
                    <input type="hidden" id="att_id" value="">
                    <div class="mb-3">
                        <label   class="form-label">Attribute Name *</label>
                        <input type="text" id="att_name"  class="form-controll" oninput="setTitle(this.value)">                                
                    </div>
                    
                    <div class="mb-3">            
                        <label for="state" class="form-label">Attribute Title *</label>
                        <input  id="att_title" type="text" class="form-controll" readonly>                       
                    </div>                  
                    <div class="mb-3">            
                        <label for="state" class="form-label">Minimum Point *</label>
                        <input  id="min_point" type="text" class="form-controll">                       
                    </div>                  
                    <div class="mb-3">            
                        <label for="state" class="form-label">Maximum Point *</label>
                        <input  id="max_point" type="text" class="form-controll">                       
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

    $("#att_name").on('input', function(key) {
        var value = $(this).val().toLowerCase();
        $('#att_title').val(value.replace(/ /g, '_'));
    })
    $("input[name='att_name']").on('input', function(key) {
        var value = $(this).val().toLowerCase();
        $("input[name='att_title']").val(value.replace(/ /g, '_'));
    })


    function getAttributeDetails(att_id)
    {
        var att_id = att_id;
        var token='{{ csrf_token() }}';
        $.ajax({
            type:'GET',
            url:"{{url('matrix-attributes')}}",
            data:'att_id='+att_id,
            dataType: "json",
            success: function(response) {   
                
                    $('#att_id').val(response.id);
                    $('#att_name').val(response.name);
                    $('#att_title').val(response.title);
                    $('#max_point').val(response.max_point);
                    $('#min_point').val(response.min_point);                
            }
        });
    }
  
    function submitEditForm(){
  
 var att_id=$('#att_id').val();
 var att_name=$('#att_name').val();
 var att_title=$('#att_title').val();
 var max_point=$('#max_point').val();
 var min_point=$('#min_point').val();
 var token='{{ csrf_token() }}';
 //console.log(staff_id);
 $.ajax({
     type:'POST',
     url:"{{ url('matrix-attributes') }}",
     data:'_token='+token+'&att_id='+att_id+'&att_name='+att_name+'&att_title='+att_title+'&max_point='+max_point+'&min_point='+min_point,
     beforeSend: function () {
         $('.submitBtn').attr("disabled","disabled");
         //$('.modal-body').css('opacity', '.5');
     },
     success:function(msg){
         //console.log(msg);
         if(msg == 'ok'){
             $('#btn1').hide();
             
             $('.statusMsg').html('<span style="color:green;">Attribute Updated Successfully.</p> Redirecting.....');
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
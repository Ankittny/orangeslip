@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
<div class="candidate_List_box">
    <h4>Settings / <span>No Of Employee</span></h4>
    
    <form method="post" action="{{route('setting.empRange.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-5">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">From *</label>
                    <input  class="form-control" name="range_start" type="number" required>                   
                    
                </div>                           
            </div>
            <div class="col-md-5">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">To  *</label>
                    <input type="number"   name="range_end" class="form-control" required>                                
                </div>                           
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                <label  class="form-label">&nbsp;</label>
                <button class="btn rounded-pill pxp-section-cta btn-block">Save</button>
                </div>
            </div>                 
        </div>
    </form>
                
</div>
            

    <div class="pxp-dashboard-content-details">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        
                        
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                        @foreach($allRange as $range)
                        <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" onclick="getCityDetails({{$range->id}})">Edit</button></td>
                        <td>{{$range->range_start}}</td>
                        <td>{{$range->range_end}}</td>
                        <td>{{$range->status==1?"Active":"Inactive"}}</td>
                        
                    </tr>
                    @endforeach                
                </tbody>
            </table>
        </div>
    </div>
 
<!-- Popup Modal-->
 
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update Range</h4>
        
            <form role="form" method="post">
                @csrf
                <div class="form-group">
                    <input type="hidden" id="range_id" value="">
                    <div class="mb-3">
                        <label   class="form-label">From *</label>
                        <input type="number" id="range_start"  class="form-control" value="">                                
                    </div>
                    <div class="mb-3">
                        <label   class="form-label">To *</label>
                        <input type="number" id="range_end"  class="form-control" value="">                                
                    </div>
                    
                              
                    <div class="mb-3">            
                        <label for="status" class="form-label">Status *</label>
                        <select  id="status" class="form-controll">
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
   
    var st= $("#status").selectize();
</script>
<script>
    function getCityDetails(range_id)
    {
        var range_id = range_id;
        var token='{{ csrf_token() }}';
        $.ajax({
            type:'GET',
            url:"{{url('get_emp_range')}}",
            data:'id='+range_id,
            success: function(response) {                                
                $('#range_id').val(response.id);
                $('#range_start').val(response.range_start);
                $('#range_end').val(response.range_end);
                 
                
                 
                
                
                
                st[0].selectize.setValue(response.status);
                
                selectize.refreshOptions();    
    
                console.log(response);
            }
        });
    }
  
    function submitEditForm(){
 
 var range_id=$('#range_id').val();
 var range_start=$('#range_start').val();
 var range_end=$('#range_end').val();
  
 var status=$('#status').val();
 var token='{{ csrf_token() }}';
 //console.log(staff_id);
 $.ajax({
     type:'POST',
     url:"{{ url('update_emp_range') }}",
     data:'_token='+token+'&range_id='+range_id+'&range_start='+range_start+'&range_end='+range_end+'&status='+status,
     beforeSend: function () {
         $('.submitBtn').attr("disabled","disabled");
         //$('.modal-body').css('opacity', '.5');
     },
     success:function(msg){
         //console.log(msg);
         if(msg == 'ok'){
             $('#btn1').hide();
             
             $('.statusMsg').html('<span style="color:green;">Range Updated Successfully.</p> Redirecting.....');
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
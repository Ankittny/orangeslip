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
                <p class="pxp-text-light">Setting</p>
                
                <form method="post" action="{{url('store')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password" class="form-label">Key *</label>
                                <input type="text"  name="set_key" class="form-control ac_no" value="{{old('set_key')}}" required>                                
                            </div>   
                                @if($errors->has('set_key'))
                                    <label class="text-danger">{{ $errors->first('set_key') }}</label>
                                @endif               
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pxp-candidate-new-password" class="form-label">Value *</label>
                                <input type="text"  name="set_value" class="form-control ac_no" value="{{old('set_value')}}" required>                                
                            </div>   
                                @if($errors->has('set_value'))
                                    <label class="text-danger">{{ $errors->first('set_value') }}</label>
                                @endif               
                        </div>
                        <div class="col-md-3">
                            <label  class="form-label">&nbsp;</label>
                            <button class="btn rounded-pill pxp-section-cta btn-block">Save</button>
                        </div>
                       
                    </div>
                  

                    
                </form>

                <div class="table-responsive">
            <table id="example" class="table align-middle" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($settings as $set=>$setting)                
                    <tr>                        
                        <td><button type="button" class="assignbtn btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$setting->id}}" onclick="getPack({{$setting->id}})">Edit</button></td>
                        <td>{{$setting->key}}</td>
                        <td>{{$setting->value}}</td>                                                                        
                        <td>{{$setting->status==1?"Active":"Inactive"}}</td>
                    </tr>
                    @endforeach                
                </tbody>
            </table>
        </div>
</div>
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update Bank Details</h4>
        
            <form role="form" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="set_id" value=""> 
                         
                        
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Bank Name *</label>
                        <input type="text" id="key_set" class="form-control" value="{{old('key_set')}}"  >                                
                    </div>
                  
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">A/C Number *</label>
                        <input type="text"  id="value_set" class="form-control ac_no" value="{{old('value_set')}}"  >                                
                    </div>   
                   
                     
                    <div class="mb-3">            
                        <label for="pack_status" class="form-label">Status *</label>
                            <select  id="status_set" >
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
    function getPack(set_id)
    {
        $('.qerr').html('');
    $('.statusMsg').html('');
        var set_id = set_id;
        var token='{{ csrf_token()}}';
        
        console.log(set_id);

        
        $.ajax({
            type:'GET',
            url:"{{url('edit_setting')}}",
            data:'id='+set_id,
            success: function(response) {
                //$('#roll_name').html(response.name);
                $('#set_id').val(response.id);
                $('#key_set').val(response.key);
                $('#value_set').val(response.value);
                test[0].selectize.setValue(response.status);              
    
                console.log(response);
            }
        });
    }

    function submitEditForm(){
 
        var set_id=$('#set_id').val();
        var key_set=$('#key_set').val();
        var value_set=$('#value_set').val();
      
        var status_set=$('#status_set').val();
    
        var token='{{ csrf_token() }}';
        //console.log(staff_id);
        $.ajax({
            type:'POST',
            url:"{{ url('update_setting') }}",
            data:'_token='+token+'&set_id='+set_id+'&key_set='+key_set+'&value_set='+value_set+'&status_set='+status_set,
             
            success:function(msg){
                //console.log(msg);
                if(msg == 'ok'){
                    $('#btn1').hide();
                    
                    $('.statusMsg').html('<span style="color:green;">Setting Updated Successfully.</p> Redirecting.....');
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 1000);
                }else{
                    $('.statusMsg').html('<span style="color:red;">'+ msg +'</span>');
                }
                
            },
            error: function (personal_reject) {
           
           if( personal_reject.status === 422 ) {
               //console.log(reject);
               var personal_resp = $.parseJSON(personal_reject.responseText);
               $.each(personal_resp.errors, function (key, val) {
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
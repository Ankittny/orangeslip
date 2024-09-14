@extends('admin.layouts.app')

@section('content')

        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
    <div class="pxp-dashboard-content-details">
    <h4>Settings / <span>Meta Data</span></h4>
        
        
        <form method="post" action="{{route('setting.metaDataStore')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">URL *</label>
                        <input type="text" name="url" class="form-control" value="{{old('url')}}" required>                                
                    </div>
                        @if($errors->has('url'))
                            <label class="text-danger">{{ $errors->first('url') }}</label>
                        @endif                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Title *</label>
                        <input type="text"  name="title" class="form-control ac_no" value="{{old('title')}}" required>                                
                    </div>   
                         @if($errors->has('title'))
                            <label class="text-danger">{{ $errors->first('title') }}</label>
                        @endif               
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Description *</label>
                        <input type="text"  name="description" class="form-control" value="{{old('description')}}" required>                                
                    </div>   
                         @if($errors->has('description'))
                            <label class="text-danger">{{ $errors->first('description') }}</label>
                        @endif               
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Keywords *</label>
                        <input type="text" name="keywords" class="form-control ifsc" value="{{old('keywords')}}" required>                                
                    </div>    
                        @if($errors->has('keywords'))
                            <label class="text-danger">{{ $errors->first('keywords') }}</label>
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
                        <th>URL</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Keywords</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                        @foreach($metaData as $pack)
                        <td><button type="button" class="assignbtn btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$pack->id}}" onclick="getPack({{$pack->id}})">Edit</button></td>
                        <td>{{$pack->url}}</td>
                        <td>{{$pack->meta_title}}</td>
                        <td>{{$pack->meta_description}}</td>
                        <td>{{$pack->meta_keywords}}</td>
                                                               
                        <td>{{$pack->status==1?"Active":"Inactive"}}</td>
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
        <h4 class="modal-title text-center" > Update Meta Data</h4>
        
            <form role="form" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="meta_id" value=""> 
                         
                        
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">URL *</label>
                        <input type="text" id="meta_url" class="form-control" value="{{old('meta_url')}}"  >                                
                    </div>
                  
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Title *</label>
                        <input type="text"  id="meta_title" class="form-control ac_no" value="{{old('meta_title')}}"  >                                
                    </div>   
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Description *</label>
                        <input type="text"  id="meta_description" class="form-control" value="{{old('meta_description')}}"  >                                
                    </div>   
                         
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Kewords *</label>
                        <input type="text" id="meta_keywords" class="form-control ifsc" value="{{old('meta_keywords')}}" required>                                
                    </div>    
                       
                     
                     
                    <div class="mb-3">            
                        <label for="meta_status" class="form-label">Status *</label>
                            <select  id="meta_status" >
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
    function getPack(meta_id)
    {
        $('.qerr').html('');
    $('.statusMsg').html('');
        var meta_id = meta_id;
        var token='{{ csrf_token()}}';
        
        

        
        $.ajax({
            type:'GET',
            url:"{{url('edit_meta_data')}}",
            data:'id='+meta_id,
            success: function(response) {
                //$('#roll_name').html(response.name);
                $('#meta_id').val(response.id);
                $('#meta_url').val(response.url);
                $('#meta_title').val(response.meta_title);
                $('#meta_description').val(response.meta_description);
                $('#meta_keywords').val(response.meta_keywords);
               
                
                            
                test[0].selectize.setValue(response.status);              
    
                console.log(response);
            }
        });
    }

    function submitEditForm(){
 
        var meta_id=$('#meta_id').val();
        var meta_url=$('#meta_url').val();
        var meta_title=$('#meta_title').val();
        var meta_description=$('#meta_description').val();

        var meta_keywords=$('#meta_keywords').val();
        
        var meta_status=$('#meta_status').val();
    
        var token='{{ csrf_token() }}';
        //console.log(staff_id);
        $.ajax({
            type:'POST',
            url:"{{ url('update_meta_data') }}",
            data:'_token='+token+'&meta_id='+meta_id+'&meta_url='+meta_url+'&meta_title='+meta_title+'&meta_description='+meta_description+'&meta_keywords='+meta_keywords+'&meta_status='+meta_status,
             
            success:function(msg){
                //console.log(msg);
                if(msg == 'ok'){
                    $('#btn1').hide();
                    
                    $('.statusMsg').html('<span style="color:green;">Meta Data Updated Successfully.</p> Redirecting.....');
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
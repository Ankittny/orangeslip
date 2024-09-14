@extends('admin.layouts.app')

@section('content')

        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
    <div class="pxp-dashboard-content-details">
    <h4>Settings / <span>Packages</span></h4>
        
        
        <form method="post" action="{{route('setting.packageStore')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Package Name *</label>
                        <input type="text" name="pack_name" class="form-control" required>                                
                    </div>
                        @if($errors->has('pack_name'))
                            <label class="text-danger">{{ $errors->first('pack_name') }}</label>
                        @endif                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Price *</label>
                        <input type="text"  name="price" class="form-control" required>                                
                    </div>   
                         @if($errors->has('price'))
                            <label class="text-danger">{{ $errors->first('price') }}</label>
                        @endif               
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Offer Price *</label>
                        <input type="text" name="off_price" class="form-control" required>                                
                    </div>    
                        @if($errors->has('off_price'))
                            <label class="text-danger">{{ $errors->first('off_price') }}</label>
                        @endif                       
                </div>
                 
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Duration(in Days) *</label>
                        <input type="text"  name="duration" class="form-control" required>                                
                    </div>   
                        @if($errors->has('duration'))
                            <label class="text-danger">{{ $errors->first('duration') }}</label>
                        @endif                        
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Quantity (Offer Generate) *</label>
                        <input type="text"  name="quantity" class="form-control" required>                                
                    </div> 
                        @if($errors->has('quantity'))
                            <label class="text-danger">{{ $errors->first('quantity') }}</label>
                        @endif                          
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Description </label>
                        <textarea   name="description" class="form-control" ></textarea>                                
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
                        <th>Package Name</th>
                        <th>Price</th>
                        <th>Offer Price</th>
                        <th>Duration(in Days)</th>
                        <th>Quantity (Offer Generate)</th>
                        <th>Description</th>                       
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                        @foreach($allPack as $pack)
                        <td><button type="button" class="assignbtn btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$pack->id}}" onclick="getPack({{$pack->id}})">Edit</button></td>
                        <td>{{$pack->pack_name}}</td>
                        <td>{{$pack->price}}</td>
                        <td>{{$pack->offer_price}}</td>
                        <td>{{$pack->duration}}</td>
                        <td>{{$pack->quantity}}</td>
                        <td>{{$pack->description}}</td>                        
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
        <h4 class="modal-title text-center" > Update Package</h4>
        
            <form role="form" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="pack_id" value=""> 
                         
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Package Name *</label>
                        <input type="text" id="pack_name" class="form-control" required> 
                        <p class="error_pack_name qerr"></p>                               
                    </div>
                        
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Price *</label>
                        <input type="text"  id="price" class="form-control" required>    
                        <p class="error_price qerr"></p>                            
                    </div>   
                         
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Offer Price *</label>
                        <input type="text" id="off_price"  class="form-control" required>   
                        <p class="error_off_price qerr"></p>                             
                    </div>    
                       
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Duration(in Days) *</label>
                        <input type="text" id="duration"  class="form-control" required>
                        <p class="error_duration qerr"></p>                                
                    </div>   
                       
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Quantity (Offer Generate) *</label>
                        <input type="text" id="quantity"  class="form-control" required>  
                        <p class="error_quantity qerr"></p>                              
                    </div> 
                        
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Description </label>
                        <textarea id="description"  class="form-control" ></textarea>                                
                    </div>     
                                              
                
                    <div class="mb-3">            
                        <label for="pack_status" class="form-label">Status *</label>
                            <select  id="pack_status" >
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
    function getPack(pack_id)
    {
        $('.qerr').html('');
    $('.statusMsg').html('');
        var pack_id = pack_id;
        var token='{{ csrf_token()}}';
        
         
        

        
        $.ajax({
            type:'GET',
            url:"{{url('edit_package')}}",
            data:'id='+pack_id,
            success: function(response) {
                //$('#roll_name').html(response.name);
                $('#pack_id').val(response.id);
                $('#pack_name').val(response.pack_name);
                $('#price').val(response.price);
                $('#off_price').val(response.offer_price);
                $('#duration').val(response.duration);
                $('#quantity').val(response.quantity);
                $('#description').val(response.description);                 
                test[0].selectize.setValue(response.status);              
    
                console.log(response);
            }
        });
    }

    function submitEditForm(){
 
        var pack_id=$('#pack_id').val();
        var pack_name=$('#pack_name').val();
        var price=$('#price').val();
        var off_price=$('#off_price').val();
        var duration=$('#duration').val();
        var quantity=$('#quantity').val();
        var description=$('#description').val();
        var pack_status=$('#pack_status').val();
    
        var token='{{ csrf_token() }}';
        //console.log(staff_id);
        $.ajax({
            type:'POST',
            url:"{{ url('update_package') }}",
            data:'_token='+token+'&pack_id='+pack_id+'&pack_name='+pack_name+'&price='+price+'&off_price='+off_price+'&duration='+duration+'&quantity='+quantity+'&description='+description+'&pack_status='+pack_status,
             
            success:function(msg){
                //console.log(msg);
                if(msg == 'ok'){
                    $('#btn1').hide();
                    
                    $('.statusMsg').html('<span style="color:green;">Package Updated Successfully.</p> Redirecting.....');
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
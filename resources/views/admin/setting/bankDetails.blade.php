@extends('admin.layouts.app')

@section('content')

        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
    <div class="pxp-dashboard-content-details">
    <h4>Settings / <span>Bank Details</span></h4>
        
        
        <form method="post" action="{{route('setting.bankDetailsStore')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Bank Name *</label>
                        <input type="text" name="bank_name" class="form-control" value="{{old('bank_name')}}" required>                                
                    </div>
                        @if($errors->has('bank_name'))
                            <label class="text-danger">{{ $errors->first('bank_name') }}</label>
                        @endif                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">A/C Number *</label>
                        <input type="text"  name="ac_no" class="form-control ac_no" value="{{old('ac_no')}}" required>                                
                    </div>   
                         @if($errors->has('ac_no'))
                            <label class="text-danger">{{ $errors->first('ac_no') }}</label>
                        @endif               
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">A/C Holder Name *</label>
                        <input type="text"  name="ac_name" class="form-control" value="{{old('ac_name')}}" required>                                
                    </div>   
                         @if($errors->has('ac_name'))
                            <label class="text-danger">{{ $errors->first('ac_name') }}</label>
                        @endif               
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">IFSC Code *</label>
                        <input type="text" name="ifsc" class="form-control ifsc" value="{{old('ifsc')}}" required>                                
                    </div>    
                        @if($errors->has('ifsc'))
                            <label class="text-danger">{{ $errors->first('ifsc') }}</label>
                        @endif                       
                </div>
                 
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Branch Code *</label>
                        <input type="text"  name="branch_code" class="form-control" value="{{old('branch_code')}}" required>                                
                    </div>   
                        @if($errors->has('branch_code'))
                            <label class="text-danger">{{ $errors->first('branch_code') }}</label>
                        @endif                        
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Branch Address *</label>
                        <input type="text"  name="branch_address" class="form-control" value="{{old('branch_address')}}" required>                                
                    </div> 
                        @if($errors->has('branch_address'))
                            <label class="text-danger">{{ $errors->first('branch_address') }}</label>
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
                        <th>Bank Name</th>
                        <th>A/C Number</th>
                        <th>A/C Name</th>
                        <th>IFSC Code</th>
                        <th>Branch Code</th>
                        <th>Branch Address</th>
                                        
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                        @foreach($allBank as $pack)
                        <td><button type="button" class="assignbtn btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$pack->id}}" onclick="getPack({{$pack->id}})">Edit</button></td>
                        <td>{{$pack->bank_name}}</td>
                        <td>{{$pack->ac_no}}</td>
                        <td>{{$pack->ac_name}}</td>
                        <td>{{$pack->ifsc}}</td>
                        <td>{{$pack->branch_code}}</td>
                        <td>{{$pack->branch_address}}</td>                                                
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
        <h4 class="modal-title text-center" > Update Bank Details</h4>
        
            <form role="form" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="bank_id" value=""> 
                         
                        
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Bank Name *</label>
                        <input type="text" id="bank_name" class="form-control" value="{{old('bank_name')}}"  >                                
                    </div>
                  
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">A/C Number *</label>
                        <input type="text"  id="ac_no" class="form-control ac_no" value="{{old('ac_no')}}"  >                                
                    </div>   
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">A/C  Holder Name *</label>
                        <input type="text"  id="ac_name" class="form-control" value="{{old('ac_name')}}"  >                                
                    </div>   
                         
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">IFSC Code *</label>
                        <input type="text" id="ifsc" class="form-control ifsc" value="{{old('ifsc')}}" required>                                
                    </div>    
                       
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Branch Code *</label>
                        <input type="text"  id="branch_code" class="form-control" value="{{old('branch_code')}}"  >                                
                    </div>   
                        
                    <div class="mb-3">
                        <label for="pxp-candidate-new-password" class="form-label">Branch Address *</label>
                        <input type="text"  id="branch_address" class="form-control" value="{{old('branch_address')}}"  >                                
                    </div> 
                     
                    <div class="mb-3">            
                        <label for="pack_status" class="form-label">Status *</label>
                            <select  id="bank_status" >
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
    function getPack(bank_id)
    {
        $('.qerr').html('');
    $('.statusMsg').html('');
        var bank_id = bank_id;
        var token='{{ csrf_token()}}';
        
        

        
        $.ajax({
            type:'GET',
            url:"{{url('edit_bank')}}",
            data:'id='+bank_id,
            success: function(response) {
                //$('#roll_name').html(response.name);
                $('#bank_id').val(response.id);
                $('#bank_name').val(response.bank_name);
                $('#ac_no').val(response.ac_no);
                $('#ac_name').val(response.ac_name);
                $('#ifsc').val(response.ifsc);
                $('#branch_code').val(response.branch_code);
                $('#branch_address').val(response.branch_address);
                            
                test[0].selectize.setValue(response.status);              
    
                console.log(response);
            }
        });
    }

    function submitEditForm(){
 
        var bank_id=$('#bank_id').val();
        var bank_name=$('#bank_name').val();
        var ac_no=$('#ac_no').val();
        var ac_name=$('#ac_name').val();

        var ifsc=$('#ifsc').val();
        var branch_code=$('#branch_code').val();
        var branch_address=$('#branch_address').val();
         
        var bank_status=$('#bank_status').val();
    
        var token='{{ csrf_token() }}';
        //console.log(staff_id);
        $.ajax({
            type:'POST',
            url:"{{ url('update_bank') }}",
            data:'_token='+token+'&bank_id='+bank_id+'&bank_name='+bank_name+'&ac_no='+ac_no+'&ac_name='+ac_namess+'&ifsc='+ifsc+'&branch_code='+branch_code+'&branch_address='+branch_address+'&bank_status='+bank_status,
             
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

    $(document).ready(function(){     
        
        $(".ifsc").change(function () {      
        var inputvalues = $(this).val();      
          var reg = /[A-Z|a-z]{4}[0][a-zA-Z0-9]{6}$/;    
                        if (inputvalues.match(reg)) {    
                            return true;    
                        }    
                        else {    
                             $(".ifsc").val("");    
                            alert("You entered invalid IFSC code");    
                            $(".ifsc").focus();
                            // document.getElementById("ifsc").focus();    
                            return false;    
                        }    
        });    
        });    
        $(document).ready(function(){    

        $(".ac_no").change(function () {      
        var inputvalues = $(this).val();      
          var reg = /^[0-9]{9,18}$/;    
                        if (inputvalues.match(reg)) {    
                            return true;    
                        }    
                        else {    
                             $(".ac_no").val("");    
                            alert("You entered invalid Account Number"); 
                            $(".ac_no").focus();   
                            //document.getElementById("txtifsc").focus();    
                            return false;    
                        }    
        });      
            
        });    
</script>
@endpush
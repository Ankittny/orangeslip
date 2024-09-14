@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
<div class="candidate_List_box">
    <h4>Settings / <span>City</span></h4>
    
    <form method="post" action="{{route('setting.manageCityStore')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-5">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">State *</label>
                    <select  name="state" required>
                    <option value='' selected>Select state</option>
                    @foreach($allState as $state)
                    <option value="{{$state->state_id}}">{{$state->state_title}}</option>
                    @endforeach
                    </select>                                
                </div>                           
            </div>
            <div class="col-md-5">
                <div class="mb-3">
                    <label for="pxp-candidate-new-password" class="form-label">City *</label>
                    <input type="text"   name="city" class="form-control" required>                                
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
        
        <h5>Search By</h5>
        
        
        
                <div class="candidate_List_box_inner">
                 
		            <form name="search" method="get" action="{{url('manage_city')}}">
     			     
                    <div class="row">                     

                        <div class="col-md-4">
                            <label class="control-label">{{ __('State') }}</label>
                            <select   name="state"  onchange="getCity(this.value)" >
                                    <option value='' selected>Select state</option>
                                    @foreach($allState as $s)
                                    <option value='{{$s->state_id}}' @isset($search['state']){{$search['state']==$s->state_id ? 'selected':''}} @endif>{{$s->state_title}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">{{ __('City') }}</label>
                            <input type="text" name="city" class="form-control" value="@isset($search['city']){{$search['city']}} @endif">                                   
                        </div>
                        
                        <div class="col-md-4">
                            <label class="control-label" >{{ __('Status') }}</label>
                            <select  name="status" >
                                <option value=''>{{ __('Select') }}</option>
                                <option value="Active" @isset($search['status']){{$search['status']=='Active' ? 'selected':''}} @endif>{{ __('Active') }}</option>
                                <option value="Inactive"  @isset($search['status']){{$search['status']=='Inactive' ? 'selected':''}} @endif>{{ __('Inactive') }}</option>
                            </select>
                        </div>

                        
			        </div>
                        <div class="col-md-3">
                            <label class="control-label">&nbsp;</label>
                            <button type="submit" class="btn btn-block btn-warning " name="search" value="true">{{ __('Search') }}</button>
                        </div>
		            </form>
     <hr>
     <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>City Name</th>
                    <th>State Name</th>
                    <th>Status</th>
                    
                     
                </tr>
            </thead>
            <tbody>                
                <tr>
                    @foreach($allCity as $city)
                    <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" onclick="getCityDetails({{$city->id}})">Edit</button></td>
                    <td>{{$city->name}}</td>
                    <td>{{$city->state->state_title}}</td>
                    <td>{{$city->status}}</td>
                    
                </tr>
                @endforeach                
            </tbody>
        </table>
    </div>
        {{$allCity->links()}}
    </div>
 
<!-- Popup Modal-->
 
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update City</h4>
        
            <form role="form" method="post">
                @csrf
                <div class="form-group">
                    <input type="hidden" id="city_id" value="">
                    <div class="mb-3">
                        <label   class="form-label">City Name *</label>
                        <input type="text" id="city"  class="form-control" value="">                                
                    </div>
                    
                    <div class="mb-3">            
                        <label for="state" class="form-label">State *</label>
                        <select  id="state" class="form-controll">
                        <option value="">Select</option>
                        @foreach($allState as $state)
                        <option value="{{$state->state_id}}">{{$state->state_title}}</option>
                        @endforeach
                        </select> 
                    </div>                  
                    <div class="mb-3">            
                        <label for="status" class="form-label">Status *</label>
                        <select  id="status" class="form-controll">
                        <option value="">Select</option>
                        <option value="Active" >Active</option>
                        <option value="Inactive" >Inactive</option>
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
    var state= $("#state").selectize();
    var st= $("#status").selectize();
</script>
<script>
    function getCityDetails(c_id)
    {
        var city_id = c_id;
        var token='{{ csrf_token() }}';
        $.ajax({
            type:'GET',
            url:"{{url('get_city_details')}}",
            data:'id='+city_id,
            success: function(response) {                                
                $('#city_id').val(response.id);
                $('#city').val(response.name);
                
                 
                
                
                state[0].selectize.setValue(response.state_id);
                st[0].selectize.setValue(response.status);
                
                selectize.refreshOptions();    
    
                console.log(response);
            }
        });
    }
  
    function submitEditForm(){
 
 var city_id=$('#city_id').val();
 var city=$('#city').val();
 var state=$('#state').val();
  
 var status=$('#status').val();
 var token='{{ csrf_token() }}';
 //console.log(staff_id);
 $.ajax({
     type:'POST',
     url:"{{ url('update_city_details') }}",
     data:'_token='+token+'&city_id='+city_id+'&city='+city+'&state='+state+'&status='+status,
     beforeSend: function () {
         $('.submitBtn').attr("disabled","disabled");
         //$('.modal-body').css('opacity', '.5');
     },
     success:function(msg){
         //console.log(msg);
         if(msg == 'ok'){
             $('#btn1').hide();
             
             $('.statusMsg').html('<span style="color:green;">City Updated Successfully.</p> Redirecting.....');
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
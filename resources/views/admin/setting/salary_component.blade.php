@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
<div class="candidate_List_box">
<h4>Settings / <span>Salary Componenet</span></h4>
    
    <form method="post" action="{{route('setting.salaryComponent.store')}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <select id="category"   name="category"  required> 
                            <option value="" >Select</option>
                            <option value="Earning" >Earning</option>
                            <option value="Deduction" >Deduction</option>
                        </select>                               
                    </div>                           
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="component" class="form-label">Component *</label>
                        <input type="text" id="component" name="component" class="form-control" required>                                
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
                
</div>

    <div class="pxp-dashboard-content-details">
        <h5>Search By</h5>
        
         
        <form name="search" method="get" action="{{url('salary_component')}}">
     			     
                      <div class="row">                     
  
                          <div class="col-md-4">
                              <label class="control-label">{{ __('Category') }}</label>
                              <select   name="category"  >
                                      <option value='' selected>Select Category</option>
                                      <option value='Earning' {{isset($searchData['category'])=='Earning' ? "selected":"" }}>Earning</option>
                                      <option value='Deduction'  {{isset($searchData['category'])=='Deduction' ? "selected":"" }}>Deduction</option>
                                     
                              </select>
                          </div>
  
                          
                          <div class="col-md-4">
                              <label class="control-label" >{{ __('Status') }}</label>
                              <select  name="status" >
                                  <option value=''>{{ __('Select') }}</option>
                                  <option value="1" {{isset($searchData['status'])=='1' ? "selected":"" }}>{{ __('Active') }}</option>
                                  <option value="0"  {{isset($searchData['status'])=='0' ? "selected":"" }}>{{ __('Inactive') }}</option>
                              </select>
                          </div>
  
                          
                      </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label">&nbsp;</label>
                                <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">&nbsp;</label>
                                <button type="button" class="btn rounded-pill btn-block pxp-section-cta" id="reset" value="true">{{ __('Clear') }}</button>
                            </div>
                        </div>
                      </form>
        <hr>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Category</th>
                        <th>Component</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>                
                    <tr>
                        @foreach($salary_components as $sc)
                        <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" onclick="getSalComponent({{$sc->id}})">Edit</button></td>
                        <td>{{$sc->category}}</td>
                        <td>{{$sc->component}}</td>
                        <td>{{$sc->status==1?"Active":"Inactive"}}</td>                    
                    </tr>
                    @endforeach                
                </tbody>
            </table>
        </div>
        {{$salary_components->links()}}
    </div>

    <!-- Popup Modal-->
    <div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">       
        
        <div class="modal-body">
        <h4 class="modal-title text-center" > Update Salary Componenet</h4>
        
            <form role="form" method="post">
                @csrf
                <div class="form-group">
                    <input type="hidden" id="comp_id" value="">
                    <div class="mb-3">
                        <label   class="form-label">Component *</label>
                        <input type="text" id="component1" name="component1" class="form-control" value="">                                
                    </div>
                    <div class="mb-3">            
                        <label for="category" class="form-label">Category *</label>
                        <select  id="category1" >
                        <option value="">Select</option>
                        <option value="Earning" >Earning</option>
                        <option value="Deduction" >Deduction</option>
                        </select> 
                    </div>               
                    
                    
                    <div class="mb-3">            
                        <label for="status" class="form-label">Status *</label>
                        <select  id="com_status" >
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
     

 
    var cc= $("#category1").selectize();
    var cs= $("#com_status").selectize();
</script>
<script>
    function getSalComponent(comp_id)
    {
        var sal_comp_id = comp_id;
        var token='{{ csrf_token() }}';
        $.ajax({
            type:'GET',
            url:"{{url('get_sal_component')}}",
            data:'id='+sal_comp_id,
            success: function(response) {                                
                $('#comp_id').val(response.id);
                $('#component1').val(response.component);
                
                //test[0].selectize.setValue(response.category);
                cc[0].selectize.setValue(response.category);
                cs[0].selectize.setValue(response.status);
                selectize.refreshOptions();    
    
                console.log(response);
            }
        });
    }
  
    function submitEditForm(){
 
 var comp_id=$('#comp_id').val();
 var component1=$('#component1').val();
 var category1=$('#category1').val();
 var com_status=$('#com_status').val();
 var token='{{ csrf_token() }}';
 //console.log(staff_id);
 $.ajax({
     type:'POST',
     url:"{{ url('update_sal_component') }}",
     data:'_token='+token+'&comp_id='+comp_id+'&component1='+component1+'&category1='+category1+'&com_status='+com_status,
     beforeSend: function () {
         $('.submitBtn').attr("disabled","disabled");
        //  $('.modal-body').css('opacity', '.5');
     },
     success:function(msg){
         //console.log(msg);
         if(msg == 'ok'){
             $('#btn1').hide();
             
             $('.statusMsg').html('<span style="color:green;">Salary Comopnent Updated Successfully.</p> Redirecting.....');
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
@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
         
               
                
        <div class="">
                <div class="">
                    <form name="search" method="get" action="{{url('hr_deposit_requests')}}">
                    @csrf
                    <div class="row">
                        
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('Transaction ID') }}</label>
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control" value="{{ isset($searchData['transaction_id']) ? $searchData['transaction_id'] : "" }}">							
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('Status') }}</label>
                            <select name="status" id="status" >
                                <option value="">{{ __('All') }}</option>
                                <option value="1" @isset($searchData['status']){{  $searchData['status'] == 1 ? "selected" : "" }}@endif>{{ __('Pending') }}</option>
                                <option value="2"  @isset($searchData['status']){{  $searchData['status'] == 2 ? "selected" : "" }}@endif>{{ __('Approved') }}</option>
                                <option value="3"  @isset($searchData['status']){{  $searchData['status'] == 3 ? "selected" : "" }}@endif>{{ __('Reject') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('From  Date') }}</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ isset($searchData['from_date']) ? $searchData['from_date'] : "" }}">							
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="control-label">{{ __('To Date') }}</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ isset($searchData['to_date']) ? $searchData['to_date'] : "" }}">							
                        </div>
                        
                        <div class="col-md-3">
                            <label class="control-label">&nbsp;</label>
                            <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">&nbsp;</label>
                            <button type="button" class="btn rounded-pill btn-block pxp-section-cta" id="reset" value="true">{{ __('Reset') }}</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <hr>
        
    <div class="pxp-dashboard-content-details">
    <h1>Deposit List</h1>

    <button type="button" class="btn expBtn sm_btn rounded-pill pxp-section-cta" >Export</button>
                
            <div class="mt-4">
                <div class="">
                                
                    
                    <div class="table-responsive">
                        <table id="demo-foo-addrow" class="footable table contact-list">
                            <thead>
                                <tr>
                                    <th>User Name</th>                                    
                                    <th>Transaction ID</th>
                                    <th>Amount (<i class="fa fa-inr"></i>)</th>
                                    <th data-breakpoints="xs sm">Comment</th>
                                    
                                    <th data-breakpoints="xs sm md">Date</th>
                                    <th data-breakpoints="xs sm md lg">Status</th>
                                   
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deposits as $w)
                                <tr>
                                    <td>{{ $w->user->first_name}} {{ $w->user->last_name}}</td>
                                    <td>{{ $w->tid}}</td>
                                    <td>{{ $w->amount}}</td>
                                    <td>{{ $w->comment}}</td>
                                     
                                    <td>{{ $w->created_at}}</td>
                                    
                                    <td>
                                                                          
                                        @if($w->status==2)
                                        Approved
                                        @elseif($w->status==3)
                                        Rejected
                                        @else
                                        Pending
                                        @endif

                                        @if($w->status==1)
                                        
                                        
                                        <button type="button" class="assignbtn btn btn-sm btn-rounded btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-id="{{$w->id}}">Update</button>
                                      
                                        @endif
                                        
                                        
                                    </td>
                                   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $deposits->links() }}
                    </div>
                    
                </div>
            </div>
        
    </div>

    <div class="modal fade" id="modalForm">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-body">
                    <h4 class="modal-title">Update Deposit Request Status</h4>
                
                        <form role="form" method="post" id="modelForm">
                            @csrf
                            <input type="hidden" id="d_id" value="">
                        
                            <div class="form-group">
                                <label for="inputName">Select Response *</label>
                                <Select  id="response" name="response" required>
                                    <option value="" >Select Response</option>
                                    <option value="2" >Approve</option>
                                    <option value="3" >Reject</option>
                                </select>
                            </div>
                            <div class="form-group" id="div2">
                                <label for="inputName">Remark *</label>
                                <textarea  class="form-control" id="remark" row="3" required></textarea>                           
                            </div>
                            <!-- <div class="form-group" id="div3" style="display:none">
                                <label for="inputName">Reason *</label>
                                <textarea  class="form-control" id="reason" row="3" required></textarea>                           
                            </div> -->
                        </form>
                        <p class="statusMsg"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="resetForm()">Close</button>
                    <button type="button" class="btn btn-success " id="btn1" onClick="submitContactForm()">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
<!-- Assign Response Modal -->
@endsection
@push('script')
<script>
function resetForm(){
    $('#response').val('');
    $('#remark').val('');
    //$('#reason').val('');
    
    // $("#div2").hide();
    // $("#div3").hide();
   // selectize.clearOptions();
    //selectize.clear();
    //selectize.refreshOptions(true);
//    /$('#modelForm').reset();
   //$("#modelForm")[0].reset();
    //$('textarea,select').val('');
    // var element = jQuery('#response');
  
    //     if(element[0].selectize){
    //         element[0].selectize.destroy();
    //     }
    
   }

    $(document).on("click",".assignbtn",function(){
        //alert('1');
        var deposit_id = $(this).data('id');//console.log(deposit_id);
        $('#d_id').val(deposit_id);
    }); 

    function submitContactForm(){
    // $('#btn5').attr("disabled","disabled");
    //console.log(service_id);
    var deposit_id = $('#d_id').val();     
    var response = $('#response').val();
    var remark = $('#remark').val();
   // var reason = $('#reason').val();
    var token='{{ csrf_token() }}';
 //console.log(staff_id);
        $.ajax({
            type:'POST',
            url:"{{url('approve_hr_deposit')}}",
            data:'_token='+token+'&deposit_id='+deposit_id+'&response='+response+'&remark='+remark,
            beforeSend: function () {
                $('.submitBtn').attr("disabled","disabled");
                // $('.modal-body').css('opacity', '.5');
            },
            success:function(msg){
                //console.log(msg);
                if(msg =='ok'){
                    $('#btn1').hide();
                    
                    $('.statusMsg').html('<span style="color:green;">Deposit Request Approved Successfully...</p> Redirecting....');
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 1000);
                }else{
                    $('.statusMsg').html('<span style="color:red;">'+ msg +'</span>');
                    setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 1000);
                }
                
            }
        });
     
}
$(document).on("change","#response",function(){
    // $("#div2").hide();
    // $("#div3").hide();

    // var res=$('#response').val();
        
    //     $("#div"+res).show();
       
    }); 

    // document.getElementById('response').addEventListener('change', function () {
    // var style = this.value == 3 ? 'block' : 'none';
    // document.getElementById('reasondiv').style.display = style;
    // });
</script>

<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/hr_deposit_requests?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });
</script>
@endpush

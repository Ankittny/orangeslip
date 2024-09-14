@extends('admin.layouts.app')

@section('content')

        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
        <div class="candidate_List_box">
                <h5>Search By</h5>
                <div class="candidate_List_box_inner">
                 
		            <form name="search" method="get" action="{{url('verificationlist')}}">
     			    
                    <div class="row">                     

                        <div class="col-md-4 mb-4">
                            <label class="control-label">{{ __('Verification Type') }}</label>
                            <select  id="ver_type" name="ver_type" >
                                    <option value='' >Select </option>
                                    @foreach($verification_types as $s)
                                    <option value='{{$s->title}}' @isset($searchData['ver_type']){{  $searchData['ver_type'] == $s->title ? "selected" : "" }}@endif>{{$s->title}}</option>
                                    @endforeach
                            </select>
                        </div>

                        @isset($staff)
                        <div class="col-md-4 mb-4">
                            <label class="control-label">{{ __('Assign To') }}</label>
                            <select  id="staff" name="staff" >
                                    <option value='' >Select </option>
                                    @foreach($staff as $s)
                                    <option value='{{$s->id}}'  @isset($searchData['staff']){{  $searchData['staff'] == $s->id ? "selected" : "" }}@endif>{{$s->first_name}}</option>
                                    @endforeach
                            </select>
                        </div>
                        @endif

                        
                        <div class="col-md-4 mb-4">
                            <label class="control-label" >{{ __('Status') }}</label>
                            <select name="status" id="status" >
                            <option value=''>{{ __('Select') }}</option>
                                <option value="1" @isset($searchData['status']){{  $searchData['status'] == 1 ? "selected" : "" }}@endif>{{ __('Pending') }}</option>
                                <option value="2" @isset($searchData['status']){{  $searchData['status'] == 2 ? "selected" : "" }}@endif>{{ __('Assign to Staff') }}</option>
                                <option value="3"  @isset($searchData['status']){{  $searchData['status'] == 3 ? "selected" : "" }}@endif>{{ __('Verified') }}</option>
                                <option value="4"  @isset($searchData['status']){{  $searchData['status'] == 4 ? "selected" : "" }}@endif>{{ __('Unverified') }}</option>
                                <option value="5"  @isset($searchData['status']){{  $searchData['status'] == 5 ? "selected" : "" }}@endif>{{ __('Reject Request') }}</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                        <label class="control-label" >&nbsp;</label>
                            <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                             
                        </div>
                        <div class="col-md-2">
                        <label class="control-label" >&nbsp;</label>
                             
                            <button type="button" class="btn rounded-pill btn-block pxp-section-cta" id="reset" value="true">{{ __('Reset') }}</button>
                        </div>
                        
			        </div>
                        
		            </form>
                </div>
                </div>
    <div class="pxp-dashboard-content-details custom_chk">
        <h1>Verification List</h1>

        <div class="d-flex justify-content-between mb-3">
            <div>
                <button type="button" class="btn expBtn btn-sm btn-rounded btn-warning" >Export</button>
                @if((Auth::user()->account_type=='superadmin') || (Auth::user()->account_type=='verification head'))
                <button type="button" data-bs-toggle="modal"  data-bs-target="#myModal"  class="btn noasnBtn sm_btn rounded-pill pxp-section-cta"  >Assign To</button>
                @endif
            </div>
            <div>
            @if((Auth::user()->account_type=='superadmin') || (Auth::user()->account_type=='verification head'))
            <input type="checkbox" name="slAll" id="slAll">
            <label class="form-label">Select All</label>
            @endif
            </div>
        </div>

        
       
        <div class="table-responsive">
            <table class="table footable  align-middle">
                <thead>
                    <tr>
                                    
                        <th>Candidate Name</th>                    
                        <th>Verification Type</th>
                        <th data-breakpoints="xs sm">HR Name</th>
                        <th data-breakpoints="xs sm md">Staff Name</th>
                        @if((Auth::user()->account_type=='superadmin') || (Auth::user()->account_type=='verification head'))
                        <th data-breakpoints="xs sm md">Assign</th>
                        @endif
                        <th data-breakpoints="xs sm md lg">Status</th>
                        <th data-breakpoints="xs sm md lg">Action</th>        
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($verifications as $v)
                    <tr>
                        
                        <td>
                            <a class="text-success" href="{{route('candidateview',['id'=>base64_encode($v->candidate_id)])}}" target="_blank">{{strtoupper($v->candidate->name)}}</a>
                        </td>
                        <td>{{$v->verification_type}}</td>                   
                        <td>@if($v->hr_id!=null){{strtoupper($v->hr->first_name)}}@endif</td>
                        <td>@if($v->staff_id!=null){{strtoupper($v->staff->first_name)}}@endif</td>
                        @if((Auth::user()->account_type=='superadmin') || (Auth::user()->account_type=='verification head'))
                        <td>                      
                            @if($v->status!=3)                                 
                                <input type="checkbox" name="sl"  value="{{$v->id}}">
                            @endif
                        </td>
                        @endif
                           

                        <td>
                            @if($v->status==1)
                                Pending
                            @elseif($v->status==2)
                                Assign to Staff
                            @elseif($v->status==3)
                                Verified
                            @elseif($v->status==4)
                                Unverified
                            @elseif($v->status==5)
                                Reject Request
                                
                            @endif
                        </td>
                        <td> 
                        <button type="button" class="docviewtbtn btn btn-sm btn-rounded btn-success" data-bs-toggle="modal" data-bs-target="#docViewModal" data-id="{{$v->id}}">View Document</button>
                            
                            @if(Auth::user()->account_type=='verification staff' && $v->status==2 )
                                <a class="reportbtn btn btn-sm btn-rounded btn-danger" href="{{route('rejectRequest',['id'=>$v->id])}}" onclick="return confirm('Are you sure you want to reject request?');">Reject Request</a>
                            @endif

                           {{-- 
                            @if((Auth::user()->account_type=='superadmin') || (Auth::user()->account_type=='verification head'))
                                @if($v->status==1 || $v->status==5 || $v->status==2) 
                                <button type="button" class="assignbtn btn btn-sm btn-rounded btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" data-id="{{$v->id}}" onclick="getStaff({{$v->id}})">Assign</button>
                                @endif
                            @endif
                            --}}

                            @if((Auth::user()->account_type=='verification staff') || (Auth::user()->account_type=='verification head'))
                                @if($v->status==2 )
                                <button type="button" class="reportbtn btn btn-sm btn-rounded btn-warning" data-bs-toggle="modal" data-bs-target="#reportModal" data-id="{{$v->id}}">Submit Report</button>  
                                @endif
                            @endif

                            @if($v->status==3 || $v->status==4)
                                <button type="button" class="reporviewtbtn btn btn-sm btn-rounded btn-success" data-bs-toggle="modal" data-bs-target="#reportViewModal" data-id="{{$v->id}}">View Report</button>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                    
                    {{$verifications->links()}}
                    

                </tbody>
            </table>
        </div>
    </div>
   

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header ">
            <button type="button" class="btn-close" data-bs-dismiss="modal" onClick="clearData()"></button>            
        </div>        
        <div class="modal-body">
            <h4 class="modal-title text-center">Assign </h4>
            <p class="statusMsg"></p>
                <form role="form" method="post" enctype="multipart/form-data" id="assignForm">
                    @csrf
                    <input type="hidden" id="v_id" name="v_id" value="">
                    <div class="form-group">
                        <label for="inputName">Select Staff </label>
                        <Select   id="staff_id" name="staff_id"  required> 
                            <option value=''>select</option>
                            @isset($staff)
                            @foreach($staff as $vstaff)
                            <option value="{{$vstaff->id}}">{{$vstaff->first_name}} {{$vstaff->last_name}}</option>       
                            @endforeach    
                            @endif                                         
                        </select>
                        <label class="error_staff_id qerr"></label>
                    </div>                     
                </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" onClick="clearData()" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success " id="btn1" onClick="submitPopupForm()">SUBMIT</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="reportModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">        
            <div class="modal-body">
                <h4 class="modal-title text-center">Submit Verification Report </h4>
                <p class="reportstatusMsg"></p>
                <form role="form" method="post" id="reportSubmit" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="v_id1" name="v_id1" value="">                   
                    <div class="form-group">
                        <label for="v_status">Status </label>
                        <select  id="v_status" name="v_status">                            
                            <option value="">Select</option>
                            <option value="3">Verified</option>
                            <option value="4">Unverified</option>
                        </select>
                        <label class="error_v_status qerr"></label> 

                        <label for="comment">Remark </label>
                        <textarea  class="form-control" id="comment" name="comment" row="3"></textarea>
                        <label class="error_comment qerr"></label> 
                        
                        <div class="table-responsive">                      
                            <table class="table" id="dynamicReport"> 
                            <label for="document">Documents</label>                                 
                                <tr>
                                    <td>
                                        <label >Name *</label>
                                        <input type="text" name="doc[0][name]" class="form-control" required>
                                        <label class="error_name qerr"></label> 
                                    </td>
                                    <td>
                                        <label>File (jpg/jpeg)*</label>
                                        <div class="custom-file">
                                        <input type="file" id="doc[0][file]" name="doc[0][file]" class="custom-file-input">
                                        <label class="custom-file-label" for="doc[0][file]">Choose file</label>
                                        </div>
                                        <label class="error_file qerr"></label> 
                                    </td>
                                    <td >
                                        <label >&nbsp;</label>
                                        <button type="button" name="add" id="dynamic-report" class="btn btn-sm btn-block btn-outline-success"><i class="fa fa-plus"></i>Add more</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onClick="clearData()">Close</button>
                <button type="button" class="btn btn-success " id="btn1" onClick="submitReportForm()">SUBMIT</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reportViewModal">
    <div class="modal-dialog">
        <div class="modal-content">       
            <div class="modal-body">
                <h4 class="modal-title text-center" > Verification Report </h4>
                <p class="statusMsg"></p>
                <form role="form" method="post">
                    @csrf
                    <input type="hidden" id="v_id2" value="">                   
                    <div class="form-group">
                        Status : <p style="text-align: justify;" id="report_status"></p>                          
                        Remark : <p style="text-align: justify;" id="report"></p> 
                        <div class="table-responsive">  
                            <table class="table table-bordered m-t-30 table-hover contact-list">
                                <thead>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Doc Name</th>
                                        <th>Doc</th>                                        
                                    </tr>
                                </thead>
                                <tbody id="tBody"></tbody>
                            </table>    
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>           
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="docViewModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="modal-title text-center" > Documents </h4>        
                <form role="form" method="post">
                    @csrf                   
                    <input type="hidden" id="v_id3" value="">
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table m-t-30 contact-list">
                                <thead>
                                    <tr>
                                        <th>Doc Name</th>
                                        <th>Doc</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tBodyDoc"></tbody>
                            </table>    
                        </div>   
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>           
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

<script>
$('#slAll').click(function(){
    if($(this).is(':checked')){
        $('input[name="sl"]').prop('checked', true);
    } else {
        $('input[name="sl"]').prop('checked', false);
    }
});
    
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/verificationlist?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });
</script>
<script>
    $('#reset').on('click',function(){
         
        $("#ver_type")[0].selectize.clear();
        $("#staff")[0].selectize.clear();
        $("#status")[0].selectize.clear();
    });
</script>

<script type="text/javascript">
    var i = 0;
    //$(document).on("click",".dynamic-ar",function(){
   $("#dynamic-report").click(function () {
        ++i;
        $("#dynamicReport").append('<tr><td><input type="text" name="doc['+ i +'][name]" class="form-control" required></td><td> <div class=""><input type="file" id="doc['+ i +'][file]" name="doc['+ i +'][file]" class=""> </div></td><td><button type="button" class="btn btn-outline-danger btn-sm remove-report-field">Delete</button></td></tr>'
            );
    });
    $(document).on('click', '.remove-report-field', function () {
        $(this).parents('tr').remove();
    });
/*
var j = 0;
    //$(document).on("click",".dynamic-ar",function(){
   $("#dynamic-assign").click(function () {
        ++j;
        $("#dynamicAssign").append('<tr><td><input type="text" name="doc['+ j +'][name]" class="form-control" required></td><td><div class="custom-file"><input type="file" id="doc['+ j +'][file]" name="doc['+ j +'][file]" class="custom-file-input"><label class="custom-file-label" for="doc['+ j +'][file]">Choose file</label></div></td><td><button type="button" class="btn btn-outline-danger remove-assign-field">Delete</button></td></tr>'
            );
    });
    $(document).on('click', '.remove-assign-field', function () {
        $(this).parents('tr').remove();
    });
*/

</script>
<script>
    function clearData(){
        
        $('.qerr').html('');
        $('.reportstatusMsg').html('');
        $('.statusMsg').html('');
        $('#comment').val('');
        $('#doc').val('');
        $('#assignForm').trigger("reset");

        $("#staff_id")[0].selectize.clear();
        $("#staff_id")[0].selectize.refreshOptions();

        $("#v_status")[0].selectize.clear();
        $("#v_status")[0].selectize.refreshOptions();



        // var element = jQuery('#staff_id');
  
        // // if(element[0].selectize){
        // //     element[0].selectize.destroy();
        // // }
        // var ver_status = jQuery('#v_status');
  
        // if(ver_status[0].selectize){
        //     ver_status[0].selectize.destroy();
        // }
    }
    // $( document ).ready(function() {
      
        function getStaff(ver_id)
        {
            alert(1);
            var verification_id = ver_id;
            var token='{{ csrf_token() }}';
            //console.log(verification_id);
            var $select = $($('#staff_id')).selectize();
            var selectize = $select[0].selectize;
            
            $.ajax({
                type:'GET',
                url:"{{url('get_staff')}}",
                data:'verification_id='+verification_id,
                success: function(response) {   
                    
                    $.each(response,function (i, staff){
                        selectize.addOption({value: staff.user_id, text: staff.first_name+' '+ staff.last_name+' / '+staff.email+' / '+staff.department});                  
                    });               
                    selectize.refreshOptions();              
                    //selectize.clear();              
                    //console.log(response);
                }
            });
        }
    // });



</script>




<script>
     $(document).on("click",".assignbtn",function(){
    
    var verification_id = $(this).data('id');//console.log(service_id);
    $('#v_id').val(verification_id);
});



function submitPopupForm(){
// $('#btn5').attr("disabled","disabled");
//console.log(service_id);
$('.qerr').html('');
$('.statusMsg').html('');
// var verification_id = $('#v_id').val();     
var staff_id = $('#staff_id').val();
var token='{{ csrf_token() }}';

// var form = $('#assignForm')[0];
var v_id=[];
            $("input[name='sl']:checked").each(function(){
                v_id.push(this.value);
        });
        console.log(v_id);
// Create an FormData object 
// var data = new FormData(form);
// //console.log(staff_id);
    $.ajax({
        type:'POST',
        // enctype: 'multipart/form-data',
        // processData: false,
        url:"{{ url('verification_assign') }}",
        // data:data,
        data: {_token:token,v_id:v_id,staff_id:staff_id},
                dataType: 'JSON',
        // contentType: false,
        // cache: false,
        // timeout: 600000,

        // type:'POST',
        // url:"{{ url('verification_assign') }}",
        // data:'_token='+token+'&staff_id='+staff_id+'&verification_id='+verification_id,
        
        beforeSend: function () {
            $('.submitBtn').attr("disabled","disabled");
            // $('.modal-body').css('opacity', '.5');
        },
        success:function(msg){
            console.log(msg.errors);
            if( msg.status == false ) {
               //console.log(reject);                
               $.each(msg.errors, function (errors_key, errors_val) {
                   console.log(errors_key,errors_val);
                   $('.error_'+errors_key).html(errors_val[0]).css("color","red","display","show");
                   $( errors_key ).text(errors_val[0]);
               });
           }
            if(msg.status == true){
                $('#btn1').hide();
                clearData();
                
                $('.statusMsg').html('<span style="color:green;">Service Successfully Assign to Staff.</p>');
                    setTimeout(function() 
                    {
                        location.reload();  //Refresh page
                    }, 1000);
            }
            // else{
            //     $('.statusMsg').html('<span style="color:red;">'+ msg.errors +'</span>');
            // }
            
        },
         
    });
 
}
</script>
<script>
     $(document).on("click",".reportbtn",function(){
    
    var verification_id = $(this).data('id');//console.log(service_id);
    $('#v_id1').val(verification_id);
});

function submitReportForm(){
    $('.reportstatusMsg').html('');
// $('#btn5').attr("disabled","disabled");
//console.log(service_id);
// var verification_id = $('#v_id1').val();     
// var report = $('#comment').val();
// var v_status = $('#v_status').val();
//var document = $('#document').val();
// var document = $('#document')[0].files[0];
//var token='{{ csrf_token() }}';
//var temp =    $("#reportSubmit").serialize();

        var form = $('#reportSubmit')[0];
		// Create an FormData object 
        var data = new FormData(form);

        //data.append("token", "{{ csrf_token() }}");
        
    $.ajax({
        type:'POST',
        enctype: 'multipart/form-data',
        processData: false,
        url:"{{ url('submit_verification_report') }}",
        data:data,
        contentType: false,
            cache: false,
            timeout: 600000,
        // data:'_token='+token+'&report='+report+'&verification_id='+verification_id+'&v_status='+v_status+'&document='+document,
        beforeSend: function () {
            $('.submitBtn').attr("disabled","disabled");
            // $('.modal-body').css('opacity', '.5');
        },
        success:function(msg){
            if( msg.status === false ) {
               //console.log(reject);                
               $.each(msg.errors, function (errors_key, errors_val) {
                  
                   $('.error_'+errors_key).html(errors_val[0]).css("color","red","display","show");
                   $( errors_key ).text(errors_val[0]);
               });
           }
            //console.log(msg);
            if(msg.status === true){
                $('#btn1').hide();
                
                $('.reportstatusMsg').html('<span style="color:green;">Report Submitted Successfully .</p>');
                    setTimeout(function() 
                    {
                        location.reload();  //Refresh page
                    }, 1000);
             
            }
        }
    });
 
}
</script>
<script>
     $(document).on("click",".reporviewtbtn",function(){
        $('#report').html('');
        $('#tBody').html('');
    var verification_id = $(this).data('id');//console.log(service_id);
    $('#v_id2').val(verification_id);
    var token='{{ csrf_token() }}';

    $.ajax({
        type:'POST',
        url:"{{ url('view_verification_report') }}",
        data:'_token='+token+'&verification_id='+verification_id,
        beforeSend: function () {
            $('.submitBtn').attr("disabled","disabled");
           // $('.modal-body').css('opacity', '.5');
        },
        success:function(res){
        //    console.log(res.msg.details);
        $.each(res.msg, function (i, msgDetail) {
            if( msgDetail.status==3)
            {
               var rep_status='Verified';
            }
            else if(msgDetail.status==4){
                var rep_status='Unverified';
            }
            $('#report_status').html(rep_status);
            $('#report').html(msgDetail.details);
        });
            var trHTML = '';
            $.each(res.ver_doc, function (i, msgData) {
               
               
                 
                trHTML +=
                                    '<tr><td>'
                                    + msgData.staff_id
                                    + '</td><td>'
                                    + msgData.doc_name
                                    + '</td><td> <img src="/images/'+ msgData.doc_file +'" alt="No Image" style="width: 100px; height: 100px;"/>'
                                    +'<a href="/images/'+ msgData.doc_file +'" download> Download</a></td></tr>';
             });
             $('#tBody').html(trHTML);
            
            
        }
    });
});


$(document).on("click",".docviewtbtn",function(){
    $('#tBodyDoc').html('');
    var verification_id = $(this).data('id');//console.log(service_id);
    $('#v_id3').val(verification_id);
    var token='{{ csrf_token() }}';

    $.ajax({
        type:'POST',
        url:"{{ url('view_verification_doc') }}",
        data:'_token='+token+'&verification_id='+verification_id,
        
        success:function(response){
        
            var trHTMLdoc = '';
            $.each(response, function (i, docData) {
                trHTMLdoc +=
                                    '<tr><td>'
                                    + docData.doc_name
                                    + '</td><td> <img src="/images/'+ docData.doc_file +'" alt="No Image" style="width: 100px; height: 100px;"/></td><td>'
                                    // + '<a href="/images/'+ docData.doc_file +'"  download/>Download</a></td></tr>';
                                    +'<a href="/images/'+ docData.doc_file +'" download> Download</a></td></tr>';
             });
             $('#tBodyDoc').html(trHTMLdoc)
            
            
        }
    });
});

</script>
@endpush
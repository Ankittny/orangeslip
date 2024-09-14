@extends('admin.layouts.app')
@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
<div class="pxp-dashboard-content-details">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Follow Up Business</h4>
            <p class="statusMsg"></p>  
            
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/enroll_list') }}" >Back</a></li>
                    
                        
                </ol>
                
            </div>
        </div>
    </div>
     <hr>
    <div class="row mb-3">
        <div class="col-md-2">
            <label class="btn-block">Business Name:</label>
            <label><strong>{{$empDetail->business_name}}</strong></label>
        </div>
        <div class="col-md-4">
            <label class="btn-block">Business Email:</label>
            <label><strong>{{$empDetail->email}}</strong></label>
        </div>
        <div class="col-md-4">
            <label class="btn-block">Business Mobile:</label>
            <label><strong>{{$empDetail->mobile_no}}</strong></label>
        </div>
        
    </div>
    <hr>
    <form action="{{ url('follow_up')}}/{{$lead_id}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        
        <div class="row mb-5">
            <div class="col-md-4">                
                    <label for="first_name" class="form-label {{ $errors->has('remarks')?' has-error':' has-feedback' }}">Remarks *</label>
                    <textarea id="remarks" name="remarks" class="form-control" row="1" required></textarea>
                    @if ($errors->has('remarks'))
                        <label class="text-danger">{{ $errors->first('remarks') }}</label>
                    @endif                 
            </div>
            <div class="col-md-3">                 
                    <label for="last_name {{ $errors->has('next_date')?' has-error':' has-feedback' }}" class="form-label">Next Contact Date *</label>
                    <input type="date" id="next_date" name="next_date" class="form-control" value="{{ old('next_date')}}">
                    @if ($errors->has('next_date'))
                        <label class="text-danger">{{ $errors->first('next_date') }}</label>
                    @endif                 
            </div>
            <div class="col-md-3">                 
                    <label for="last_name {{ $errors->has('next_time')?' has-error':' has-feedback' }}" class="form-label">Time *</label>
                    <input type="time" id="next_time" name="next_time" class="form-control" value="{{ old('next_time')}}">
                    @if ($errors->has('next_time'))
                        <label class="text-danger">{{ $errors->first('next_time') }}</label>
                    @endif     
                            
            </div>
            <div class="col-md-2">
                <div >       
                          
                    <label class="form-label">&nbsp;</label>
                    <input type="hidden" name="maxstatus" value="{{$maxstatus}}">
                    <button class="btn rounded-pill pxp-section-cta" style="display:block;">Save</button>
                     
                </div>                 
            </div>
        </div>  
    </form>
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Business</th>
                <th>SubAdmin</th>
                <th>Date</th>
                <th>Remarks</th>
                <th>Next Contact Date</th>
                <th>Next Time</th>
                <th>Status</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            
            <tr>
                @foreach($all_fup as $fup)
                <td>{{$fup->leadDetails->business_name}}</td>
                <td>{{$fup->user->first_name}}</td>
                <td>{{$fup->date}}</td>
                <td>{{$fup->remarks}}</td>
                <td>{{$fup->next_contact_date}}</td>
                <td>{{$fup->next_time}}</td>
                <td> @if($fup->status==1)
                    Pending
                    @elseif($fup->status==2)
                    Verified
                    @endif</td>
                    <td>{{$fup->note}}</td>
                <td>
                    @if($fup->status==1)
                    <button  data-bs-toggle="modal" data-id="{{$fup->id}}" class=" modelBtn btn btn-sm btn-rounded btn-warning" data-bs-target="#actionModal"  >Action <i class="fa fa-plus"></i></button>

                        
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
     
</div>

 <!--Language Details-->
<div class="modal fade" id="actionModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick=resetForm();></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title text-center">Action</h4>
              
                <form method="post" action="" id="modelForm">
                @csrf
                    <input type="hidden" id="fup_id" value="">                    
                    
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea class="form-control" id="note" name="note" ></textarea>
                        <p class="error_language qerr"></p>
                    </div>                   
                     
                    <button type="button" id="btn4" class="btn rounded-pill pxp-section-cta btn-block" onClick="addNote();">Complete Followup</button>
                    <p class="statusMsg"></p>
                </form>
            </div>
            </div>
        </div>
</div>
    <!--Language Details-->

 
@endsection

@push('js')

<script>

$(document).on("click",".modelBtn",function(){
    
    var folup_id = $(this).data('id');//console.log(service_id);
    $('#fup_id').val(folup_id);
});

    function addNote()
{
    $('.qerr').html('');
    $('#note').html('');
    $('.statusMsg').html('');
    //alert(1);
    //e.preventDefault();
    var token='{{ csrf_token() }}';
    var fup_id=$('#fup_id').val();
    var note=$('#note').val();
     
    console.log(fup_id);
    console.log(note);

    $.ajax({
        type:"POST",
        url:"{{url('/follow_up_status')}}",
        data:'_token='+token+'&fup_id='+fup_id+'&note='+note,
        success:function(response)
        {
            if(response==1)
            {
                $('#btn4').hide();
                $('.statusMsg').html('<span style="color:green;">Action Saved Successfully!.</span> Redirecting....');  
                        setTimeout(function() 
                        {
                            location.reload();  //Refresh page
                        }, 1000);               
            }            
            else
            {
                $('.statusMsg').html('<span style="color:red;">'+response+'</span>');
            }
        },
        error: function (reject) {
           
                if( reject.status === 422 ) {
                    //console.log(reject);
                    var resp = $.parseJSON(reject.responseText);
                    $.each(resp.errors, function (key, val) {
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
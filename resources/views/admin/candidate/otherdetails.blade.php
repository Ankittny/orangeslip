@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
    <div class="pxp-dashboard-content-details">

        <div class="d-flex justify-content-between">
            <h4 class="text-themecolor">Other's Details ({{$candidate->candidate_code}})</h4>
            @if(Auth::user()->account_type=='candidate')
            <a href="{{url('candidate_profile')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
            @else
            <a href="{{url('edit_candidate')}}/{{base64_encode($candidate->id)}}"><i class="fa fa-long-arrow-left"></i> Back</a>
            @endif
        </div>
        <div class="alert alert-info">
            <div class="row">
                <div class="col-md-4">
                    <strong>Name: {{$candidate->name}}</strong>
                </div>
                <div class="col-md-5">
                    <strong>Email: {{$candidate->email}}</strong>
                </div>
                <div class="col-md-3 text-right">
                    <strong>Phone: {{$candidate->phone}}</strong>
                </div>                
            </div>        
        </div>  
        @php
        $otherTypes=DB::table('others_types_masters')->orderBy('id','ASC')->get();
        @endphp
            <form role="form" method="post">
            @csrf
                @foreach($otherTypes as $ot)
                    @php
                        $ddData=DB::table($ot->dbname)->orderBy('name','ASC')->pluck('name','id')->toArray();
                        $ddData[9999]="Other";
                            // dd($ddData);
                    @endphp
               
                <div class="row align-items-center">
                  
                    <div class="col-md-4">                    
                        
                            <div class="row">
                                <div class="col-md-9">                                     
                                    <div class="mb-3">
                                        <label  class="form-label"> {{$ot->title}} *</label>
                                        <select type="text" id="val_{{$ot->name}}" name="val_{{$ot->name}}" class="selcted_dd" data-db="{{$ot->dbname}}"  required>
                                            <option value="">Select</option>
                                            
                                            @foreach($ddData as $key=>$data)
                                           
                                            <option value="{{$data}}">{{$data}}</option>
                                            @endforeach
                                            
                                        </select>
                                        <input type="text" id="add_to_{{$ot->dbname}}"  name="add_to_{{$ot->dbname}}"  style="display:none;" class="form-control" required>
                                    </div>  
                                    <p class="error_{{$ot->dbname}}"></p>                     
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-block btn-success" onClick="addOthers('{{$ot->name}}','{{$ot->dbname}}')"><i class="fa fa-plus"></i>ADD</button>
                                    </div>                        
                                </div>
                            </div>                         
                    </div>

                    <div class="col-md-8" id="{{$ot->name}}Body">                    
                        @foreach($others as $p)  
                            @if($p->type==$ot->name)                                                
                            <span class="pxp-active">{{ $p->value}}<button type="button"  data-id="{{$p->id}}" data-otype="{{$ot->name}}" class="btn btn-sm mx-1 mb-1 delBtn"> <i class="fa fa-close"></i></button> </span>
                            @endif                                                          
                        @endforeach 
                    </div>
                </div>
                <hr>
                @endforeach
                {{--<div class="row">
                    <div class="col-md-6">                    
                        
                            <div class="row">
                                <div class="col-md-9">
                                     
                                    <div class="mb-3">
                                        <label  class="form-label">Add Industry *</label>
                                        <select type="text" id="val_industry" name="val_industry"  required>
                                            <option value="">Select</option>
                                            @foreach($industries as $industry)
                                            
                                            <option value="{{$industry->name}}">{{$industry->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>                       
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-block btn-success" onClick="addOthers('industry')"><i class="fa fa-plus"></i> Add</button>
                                    </div>                        
                                </div>
                            </div>                         
                    </div>

                    <div class="col-md-6" id="industryBody">                    
                        @foreach($others as $p)          
                            @if($p->type=='industry')                                                
                            <button type="button"  data-id="{{$p->id}}" data-otype="industry"  class="btn btn-outline-success delBtn">{{ $p->value}} <i class="fa fa-close"></i></button>    
                            @endif                                                       
                        @endforeach 
                    </div>
                </div>--}}
            </form>
      
    </div>
     

    

@endsection

@push('js')

<script >
     
    $( document ).ready(function() {
         
    });

    function addOthers(typ,dbname)
    {
        //alert(1);
        var token='{{ csrf_token() }}';       
        var candidate_id = '{{base64_encode($candidate->id)}}';
        var typ = typ;
        var val_id='#val_'+typ;
        var val = $(val_id).val();
        var dbname = dbname;
        var addtomaster = $('#add_to_'+dbname).val();
        console.log(dbname);
        $.ajax({
            type:'POST',
            url:"{{url('addothersdetails')}}",
            data:{_token:token,candidate_id:candidate_id, typ:typ, val:val,dbname:dbname,addtomaster:addtomaster},             
            success: function(response) {
                if(response.status === true){
                    var trHTML = '';
                    $.each(response.data, function (i, item) {
                    trHTML +='<span class="pxp-active">'+ item.value+' <button type="button"  data-id="'+item.id+'" data-otype="'+item.type+'"   class="btn btn-sm mx-1 mb-1 delBtn"><i class="fa fa-close"></i></button></span';
                    });
                    $('#'+typ+'Body').html(trHTML);
                    // $('#val_'+typ).val('');
                    $('#val_'+typ).refreshOptions(true);
                    addtomaster(sel_val,sel_db);
                }              
               else{
                    $('.error_'+dbname).html('<span style="color:red;">'+response.msg+'</span>');
                    
                }              
            }
        });
        
    }

    $(document).on("click", ".delBtn", function() {
        var itemId=$(this).data('id');
        var itemType= $(this).data('otype');
        deleteOthers(itemId,itemType);
    });


    function deleteOthers(itemId,typ)
    {
                     
        var typ = typ;
        var itemId = itemId;
       // console.log(itemId);
        $.ajax({
            type:'GET',
            url:"{{url('deleteother')}}",
            data:{id:itemId},             
            success: function(response) {
                if(response.status === true){
                    var trHTML = '';
                    $.each(response.data, function (i, item) {
                    trHTML +='<span class="pxp-active">'+ item.value+'<button type="button"  data-id="'+item.id+'" data-otype="'+item.type+'" class="btn btn-sm mx-1 mb-1 delBtn"><i class="fa fa-close"></i></button></span>';
                    });
                    $('#'+typ+'Body').html(trHTML);
                }
            }
        });
    }
    $(document).on("change", ".selcted_dd", function() {
        var sel_val = $(this).find(":selected").val(); 
        var sel_db= $(this).data('db'); 
            // var inputvalues = $(this).attr('selected');  
            //console.log(sel_val,sel_db);
            addtomaster(sel_val,sel_db);
         
    });

    function addtomaster(sel_val,sel_db){
        if(sel_val=='Other'){
                $('#add_to_'+sel_db).val('');
                $('.error_'+sel_db).html('');
                $('#add_to_'+sel_db).show();
                
            }else{
                $('#add_to_'+sel_db).val('');
                $('.error_'+sel_db).html('');
                $('#add_to_'+sel_db).hide();
            }
    }
     
</script>
@endpush
    

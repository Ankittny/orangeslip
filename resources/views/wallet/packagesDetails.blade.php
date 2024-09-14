@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}");</script> 
        @endif
             
        <div class="pxp-dashboard-content-details">
        <div class="">
        <h5>Search By</h5>
            <div class="">
                
            <form name="search" method="get" action="{{url('packages_details')}}">
                <div class="row">
               
                    
                    <div class="col-md-4 mb-4">
                        <label class="control-label">{{ __('Package') }}</label>
                        <select name="pack" id="pack" >
                            <option value="" selected>{{ __('All') }}</option>
                            @foreach($allPack as $pack)
                            <option value="{{$pack->id}}" @isset($searchData['pack']){{  $searchData['pack'] == $pack->id ? "selected" : "" }}@endif>{{ $pack->pack_name }}</option>
                            @endforeach                            
                        </select>						
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="control-label">{{ __('Expire Date From') }}</label>
                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ isset($searchData['from_date']) ? $searchData['from_date'] : "" }}">							
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="control-label">{{ __('Expire Date To') }}</label>
                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ isset($searchData['to_date']) ? $searchData['to_date'] : "" }}">							
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="control-label">{{ __('Status') }}</label>
                        <select name="status" id="status" >
                            <option value="">{{ __('All') }}</option>
                            <option value="1" @isset($searchData['status']){{  $searchData['status'] == 1 ? "selected" : "" }}@endif>{{ __('Active') }}</option>
                            <option value="2"  @isset($searchData['status']){{  $searchData['status'] == 2 ? "selected" : "" }}@endif>{{ __('Expired') }}</option>                                
                        </select>						
                    </div>
                   
                                                   

                    @if(Auth::user()->account_type=='superadmin')
                    <div class="col-md-4 mb-3">
                        <label class="control-label" >{{ __('Business') }}</label>
                        <select name="business" id="business"  >
                            <option value="" selected>{{ __('All') }}</option>
                            @foreach($allBusiness as $business)
                            <option value="{{$business->id}}" @isset($searchData['business']){{  $searchData['business'] == $business->id ? "selected" : "" }}@endif>{{ $business->business->business_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">&nbsp;</label>
                        <button type="submit" class="btn rounded-pill btn-block pxp-section-cta" name="search" value="true">{{ __('Search') }}</button>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" class="btn rounded-pill btn-block pxp-section-cta" id="reset" value="true">{{ __('Clear') }}</button>
                    </div>
               
                </div>
                </form>
            </div>
        <hr>
        <h1>Packages History</h1>      
        <button type="button" class="btn expBtn btn-sm btn-rounded" >Export</button>          
            <div class="mt-4">
                <div class="">
                                
                    
                    <div class="table-responsive">
                        <table id="demo-foo-addrow" class="footable table contact-list">
                            <thead>
                                <tr>
                                    <th >Company</th>    
                                    <th>Package Name</th>                                    
                                    <th>Valid Upto</th>                                   
                                    <th >Remaining Qty.</th>
                                    <th >Used Qty.</th>
                                    <th >Status</th>
                                    
                                  
                                   
                                    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($subscribedPack as $spack)
                                <tr>
                                    <td>{{$spack->business_name}}</td>
                                    <td>{{strtoupper($spack->pack_name)}}</td>
                                    <td>{{$spack->expire_date}}</td>
                                    <td>{{$spack->remain_qty}}</td>
                                    <td>{{$spack->used_qty}}</td>
                                    <td>{{ $spack->status == '1' ? "Active":"Expired"}}</td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                       
                    </div>
                    {{$subscribedPack->links()}}
                </div>
            </div>
        
    </div>
    @if(Auth::user()->account_type!='superadmin')
    <div class="pxp-dashboard-content-details">
        <h1>Packages</h1>
       
        <p class="statusMsg"></p>
        @foreach($allPack as $pack)
            <div class="candidate_List_box">
             
                <div class="candidate_List_box_inner">
                    <div class="row">
                        
                        <div class="col-md-10 col-sm-9 col-lg-10">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="line_holder">
                                        <p class="d-item_one">Name</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($pack->pack_name)}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Pack Price</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">Rs.-{{$pack->price}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Offer Price</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">Rs.-{{$pack->offer_price}}</p>
                                    </div>
                                    
                                    
                                    
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <div class="line_holder">
                                        <p class="d-item_one">Duration</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{strtoupper($pack->duration)}} Days</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Quantity</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$pack->quantity}}</p>
                                    </div>
                                    <div class="line_holder">
                                        <p class="d-item_one">Description</p>
                                        <div class="dashboard_item_line"></div>
                                        <p class="d-item_two">{{$pack->description}}</p>
                                    </div>
                                     
                                     
                                     
                                </div>
                            </div>
                           
                        </div>
                        @if(Auth::user()->account_type=='business')
                        <div class="col-md-2 col-sm-3 col-lg-2 text-center">
                            <a href="{{url('/package_subscription')}}/{{$pack->id}}" class="assignbtn btn btn-sm btn-rounded btn-primary" >Subscribe</a>
                        </div>
                        @endif

                    </div>
                </div>
                
               
            </div>

        @endforeach

        
       
    </div>
    @endif

       
 
@endsection
@push('js')

<script>
$(document).ready(function(){
		$('.expBtn').click(function() {

            var formData = $('form[name=search]').serialize(); 
            var file_path = url + '/packages_details?' + formData + '&export=true';  
            var a = document.createElement('A');
            a.href = file_path; 

            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

        });
    });
</script>

 
@endpush
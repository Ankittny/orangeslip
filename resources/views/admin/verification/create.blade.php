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
            <h4 class="text-themecolor">Verification Request</h4>
            <a href="{{url('candidate_list')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
        </div>
                 
        <form method="post" action="{{url('verification')}}" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                 
                <div class="col-md-6">                            
                            <label for="inputName">Candidates</label>
                        <Select   id="candidate" name="candidate" required>
                            <option value="" selected>Select </option>
                            @foreach($candidates as $vt)
                            <option value="{{$vt->id}}" {{old('candidate')==$vt->id ? 'selected':''}}>{{$vt->name}}</option>
                            @endforeach
                        </select>                            
                </div>    
               
                <div class="col-md-6 ">                            
                            <label for="inputName">HR</label>
                        <Select   id="hr_id" name="hr_id" required>
                          
                            @foreach($allHR as $hr)
                            <option value="{{$hr->id}}" {{old('hr_id')==$hr->id ? 'selected':''}}>{{$hr->first_name}}</option>
                            @endforeach
                        </select>                            
                </div>   
                
            </div>

            <div class="row mt-4">
                                           
                        <label for="inputName">Verification Type </label>
                    
                    @foreach($verification_types as $key=>$access)
                    <div class="col-md-3">
                            <input type="checkbox"  name="v_type[{{$access->name}}]" value="{{$access->name}}" > 
                            <label  class="form-label">{{$access->title}} ({{$access->amount}})</label>
                    </div>
                    @endforeach                      
                    
            </div>
            

            <div class="mt-3 mt-lg-3">
                <button class="btn rounded-pill pxp-section-cta">Save</button>
            </div>
        </form>
    </div>

@endsection

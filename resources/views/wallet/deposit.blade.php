    @extends('admin.layouts.app')

    @section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
        <div class="pxp-dashboard-content-details">
            <h1>Deposit</h1> 
            @if(Auth::user()->account_type=='business') 
            <form method="POST" action="{{ url('deposit') }}" enctype="multipart/form-data">
                @csrf
                                        
                <div class="row">
                    
                    <div class="col-md-4 mb-3">
                        <label >Bank  (<i class="fa fa-inr"></i>)*</label>  
                        <select id="bank_id"  name="bank_id" required>
                            @foreach($bank_details as $bank)  
                            <option value="{{$bank->id}}">Bank Name:{{$bank->bank_name}}, Account Number:{{$bank->ac_no}},Account Name:{{$bank->ac_name}},IFSC:{{$bank->ifsc}},Branch Code:{{$bank->branch_code}},Branch Address:{{$bank->branch_address}}</option>
                            @endforeach
                        </select>       

                        @if ($errors->has('bank_id'))
                        <label class="text-danger">{{ $errors->first('bank_id') }}</label>
                        @endif                                    
                    </div>

                    <div class="col-md-4 mb-3">
                        <label >Amount (<i class="fa fa-inr"></i>)*</label>  
                        <input type="number"  id="amount"  name="amount" value="{{old('amount')}}" class="form-control"  required>                                        
                        @if ($errors->has('amount'))
                        <label class="text-danger">{{ $errors->first('amount') }}</label>
                        @endif                                    
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label >Transaction Id *</label>  
                        <input type="text"  id="tid"  name="tid" value="{{old('tid')}}"class="form-control" required>                                        
                        @if ($errors->has('tid'))
                        <label class="text-danger">{{ $errors->first('tid') }}</label>
                        @endif                                         
                    </div>
                    
                </div>
                                        
                <div class="row">
                <div class="col-md-4 mb-3">
                        <label >File (jpg,jpeg)</label>  
                        <div class="custom-file">
                            <input type="file" id="doc" name="doc" class="custom-file-input" >
                            <label class="custom-file-label" for="doc">Choose file</label>
                        </div>
                        
                        @if ($errors->has('doc'))
                            <label class="text-danger">{{ $errors->first('doc') }}</label>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationServer01">Comment </label>  
                        <textarea type="text"  id="comment"  name="comment" class="form-control" >  </textarea>                                   
                    </div>
                    <div class="col-md-4 mb-3">
                        <label >&nbsp; </label>  
                        <button class="btn rounded-pill pxp-section-cta btn-block" type="submit">Submit</button>                                     
                    </div>
                </div>
            </form>
            @else
            <form method="POST" action="{{ url('deposit') }}" enctype="multipart/form-data">
                @csrf
                                        
                <div class="row">
                    
                     

                    <div class="col-md-4 mb-3">
                        <label >Amount (<i class="fa fa-inr"></i>)*</label>  
                        <input type="number"  id="amount"  name="amount" value="{{old('amount')}}" class="form-control"  required>                                        
                        @if ($errors->has('amount'))
                        <label class="text-danger">{{ $errors->first('amount') }}</label>
                        @endif                                    
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationServer01">Comment </label>  
                        <textarea type="text"  id="comment"  name="comment" class="form-control" >  </textarea>                                   
                    </div>
                    <input type="hidden"  id="tid"  name="tid" value="hrreq"class="form-control" required>                                        
                    <div class="col-md-4 mb-3">
                        <label >&nbsp; </label>  
                        <button class="btn rounded-pill pxp-section-cta btn-block" type="submit">Submit</button>                                     
                    </div>
                </div>
            </form>
            @endif
        </div>

    @endsection

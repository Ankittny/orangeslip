@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
        <div class="candidate_List_box">
            <h4>Settings / <span>Kyc</span></h4>
                        
        </div>
            

    <div class="pxp-dashboard-content-details">
        <div class="candidate_List_box_inner">
      
            <div class="table-responsive">
            <form method="post" action="{{route('setting.updateKyc')}}" enctype="multipart/form-data">
            @csrf
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Name</th>
                            <th>Amount</th>
                        </tr>
                    </thead>                    
                    <tbody>                  
                    @foreach($allKyc as $kyc)
						<tr>
							<td><input type="text" name="{{ 't_'.$loop->iteration }}" class="form-control" value="{{ $kyc->title }}"></td>
							<td class="text-primary"> <input type="text" name="{{ 'n_'.$loop->iteration }}" class="form-control" value="{{ $kyc->name }}"></td>
							<td class="text-primary"><input type="text" name="{{ 'a_'.$loop->iteration }}" class="form-control" value="{{ $kyc->amount }}"> </td>					
							
						</tr>
					@endforeach
 
                    </tbody>          
                             
                                                    
                     
                </table>
                <div class="col-md-3">
                    <div class="mt-3 mt-lg-3">
                        <button class="btn rounded-pill pxp-section-cta">Update</button>
                    </div> 
                </div> 
            </form>   
            </div>
        </div>
    </div>
       
 
<!-- Popup Modal-->
 
 
@endsection
@push('js')
  
  
@endpush
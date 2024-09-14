    @extends('admin.layouts.app')

    @section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif

        <div class="pxp-dashboard-content-details">
            <h5>Upload Bulk Data</h5>
            
            <form name="upload" method="post" action="{{url('upload')}}" enctype="multipart/form-data">
                @csrf
                <div class="row mt-3 mb-3">

                    <div class="col-md-4">
                        <label class="form-label">Import File * (xlsx)</label>
                        <div class="custom-file mb-2">
                            <input type="file" id="upload" name="upload" class="custom-file-input" required>
                            <label class="custom-file-label" for="upload">Choose file</label>
                        </div>
                                                
                        @if($errors->has('upload'))
                            <label class="text-danger">{{ $errors->first('upload') }}</label>
                        @endif				
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn rounded-pill pxp-section-cta btn-block" name="uploadbtn" value="true">{{ __('Upload') }}</button>	
                    </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <a href="/new/CandidatesBulkUploadDemo.xlsx" class="btn btn-sm btn-block  btn-primary " download>Download Demo File</a>	
                    </div>
                    
                </div>
                
            </form>
            <hr>
            
            <div class="table-responsive">
                <table class="table align-middle footable">
                    <thead>
                        <tr> 
                            <th>Name</th>
                            <th>Email</th>
                            <th data-breakpoints="xs">Phone</th>                          
                            
                            <th data-breakpoints="xs sm">Experience</th>
                          
                            <th data-breakpoints="xs sm md lg">Gender</th>
                                                
                            <th data-breakpoints="xs sm md lg">Date</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allData as $key=>$d)
                        <tr>                            
                            <td>{{$d->name}}</td>
                            <td>{{$d->email_id}}</td>
                            <td>{{$d->mobile_no}}</td>
                            
                            <td>{{$d->work_experience}}</td>
                           
                            <td>{{$d->gender}}</td>
                                                  
                            <td>{{$d->created_at}}</td>                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>

               
            {{ $allData->links() }}
            </div> 

                @if (count($allData) > 0) 
                <a href="{{ url('import_bulk_data') }}" class="btn btn-sm  btn-warning mt-2" style="float: right;">Import All Data</a> 
                @endif
        </div> 
 
    @endsection

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
            <h4 class="text-themecolor">Document Upload ({{$candidate->candidate_code}})</h4>
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
                               
        <form method="post" action="{{url('upload_document')}}/{{base64_encode($candidate->id)}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="candidate_id" value="{{$candidate->id}}">     
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                                    <label  class="form-label">Document Type *</label>
                                    <select  id="doc_type" name="doc_type"  required>
                                        <option value=""></option>
                                        @foreach($allTypes as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                    </div>
                    @if($errors->has('doc_type'))
                        <label class="text-danger">{{ $errors->first('doc_type') }}</label>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                                    <label  class="form-label">Document Name *</label>
                                    <input type="text" id="doc_name" name="doc_name" class="form-control" value="{{old('doc_name')}}" required>
                    </div>
                    @if($errors->has('doc_name'))
                        <label class="text-danger">{{ $errors->first('doc_name') }}</label>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">File (jpg/jpeg)*</label>
                        <div class="custom-file">
                            <input type="file" id="doc_file" name="doc_file" class="custom-file-input"  required>
                            <label class="custom-file-label" for="doc_file">Choose file</label>
                        </div>
                    </div>
                    @if($errors->has('doc_file'))
                        <label class="text-danger">{{ $errors->first('doc_file') }}</label>
                    @endif
                </div>
            </div>
             
            <div class="row">
                
                <div class="col-md-12 text-center">
                    <button class="btn rounded-pill pxp-section-cta">Add</button>
                </div>
                
            </div>
             
        </form>
        <hr>
        <div class="table-responvive">
            <table class="table footable ">  
                <thead>
                    <tr>
                        <th>Candidate Name</th>
                        <th>Document Type</th>
                        <th>Document Name</th>
                        <th>Document File</th>  
                        <th>Action</th>
                    </tr> 
                </thead>
                <tbody>                                   
                    @foreach($documents as $ed)
                    <tr>
                        <td>                                                  
                            {{ $ed->candidateDetails->name}}
                        </td>
                        <td>                                                  
                            {{ $ed->docType->name}}
                        </td>
                        <td>                                                  
                            {{ $ed->doc_name}}
                        </td>
                        
                        <td>          
                        <img class="candidate_List_img1" width="100" height="100" src="{{ ($ed->doc_file!='')?(url('images/'.$ed->doc_file)):(url('/new/images/noimage.png')) }}" alt="">

                        <!-- <img src="{{ ($ed->doc_file!='')?(url('images/'.$ed->doc_file)):(url('images/candidate/user.png')) }}" alt="No Image" style="width: 100px; height: 100px;"/> -->
                        </td>
                        <td><a href="{{url('deletedocument')}}/{{$ed->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a> | <a href="{{URL::to('/images/'.$ed->doc_file)}}" target="_blank">Download File</a></td>
                    </tr>
                    @endforeach 
                </tbody>                                     
            </table>
        </div>
    </div>
@endsection
     
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
        <h4 class="text-themecolor">Follow Up Candidate</h4>
        <a href="{{url('candidate_list')}}"><i class="fa fa-long-arrow-left"></i> Back</a>
    </div>
        
        <hr>
    <div class="row mb-3">
        <div class="col-md-2">
            <label class="btn-block">Candidate Code:</label>
            <label><strong>{{$candidate->candidate_code}}</strong></label>
        </div>
        <div class="col-md-4">
            <label class="btn-block">Candidate Name:</label>
            <label><strong>{{$candidate->name}}</strong></label>
        </div>
        <div class="col-md-4">
            <label class="btn-block">Candidate Email:</label>
            <label><strong>{{$candidate->email}}</strong></label>
        </div>
        <div class="col-md-2">
            <label class="btn-block">Candidate Phone:</label>
            <label><strong>{{$candidate->phone}}</strong>   
        </div>
    </div>
    <hr>
     

    <form action="{{ url('candidate_follow_up')}}/{{base64_encode($id)}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="first_name" class="form-label {{ $errors->has('remarks')?' has-error':' has-feedback' }}">Remarks *</label>
                    <textarea id="remarks" name="remarks" class="form-control"  required></textarea>
                    @if ($errors->has('remarks'))
                        <label class="text-danger">{{ $errors->first('remarks') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="last_name {{ $errors->has('next_date')?' has-error':' has-feedback' }}" class="form-label">Next Contact Date *</label>
                    <input type="date" id="next_date" name="next_date" class="form-control" >
                    @if ($errors->has('next_date'))
                        <label class="text-danger">{{ $errors->first('next_date') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="last_name {{ $errors->has('next_time')?' has-error':' has-feedback' }}" class="form-label">Time *</label>
                    <input type="time" id="next_time" name="next_time" class="form-control" >
                    @if ($errors->has('next_time'))
                        <label class="text-danger">{{ $errors->first('next_time') }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-3">
                <input type="hidden" name="maxstatus" value="{{$maxstatus}}">
                    <label  class="form-label">&nbsp;</label>
                    <button class="btn rounded-pill pxp-section-cta btn-block" {{ ($maxstatus==1) ? "disabled" : "" }}>Save</button>
                </div>
            </div>
        </div>
        
       
        
    </form>
<hr>
                <h1>Follow Up List</h1>
               
                <p class="statusMsg"></p> 
                <div class="table-responvive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>HR</th>
                                <th>Date</th>
                                <th>Remarks</th>
                                <th>Next Contact Date</th>
                                <th>Next Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            <tr>
                                @foreach($all_fup as $fup)
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
                                <td>
                                    @if($fup->status==1)
                                    <a href="{{url('candidate_follow_up_status')}}/{{$fup->id}}" class="btn btn-sm btn-rounded btn-warning" onclick="return confirm('Are you sure?');">verify</a> 
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        
                        </tbody>
                    </table>
                </div>
            </div>
@endsection
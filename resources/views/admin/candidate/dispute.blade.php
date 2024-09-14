@extends('admin.layouts.app')

@section('content')
<div class="pxp-dashboard-content-details" >
    <div class="text-end">
            
            
        </div>
        <h1>Dispute Details</h1>
        <p class="pxp-text-light">Submit Dispute for Candidate</p>
        @if(session('success'))                                
        <div class="alert alert-success" role="alert">
        {{session('success')}}
        </div>
        @endif
        @if(session('error'))                                
        <div class="alert alert-danger" role="alert">
        {{session('error')}}
        </div>
        @endif

        <form method="post" action="{{ route('disputestore',['id'=>base64_encode($candidate_id)])}}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
            <input type="hidden" id="candidate_id" name="candidate_id"   value="{{$candidate_id}}" >
            <input type="hidden" id="user_id" name="user_id"   value="{{$user_id}}">
                <div class="col-md-6">
                    <div class="mb-6">
                        <label for="pxp-candidate-new-password" class="form-label">Comment</label>
                        <textarea id="comment" name="comment" class="form-control" row="3" placeholder="Enter Your Comment"></textarea>
                    </div>
                    @if($errors->has('comment'))
                        <div class="alert-danger">{{ $errors->first('comment') }}</div>
                    @endif
                </div>
                
            
                
            
            </div>
            
           
           
            

            <div class="mt-3 mt-lg-3">
                <button class="btn rounded-pill pxp-section-cta">Save</button>
            </div>
        </form>
    </div>
    @endsection
   
@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif

<div class="pxp-dashboard-content-details">
                <h1>Review Details</h1>
                <p class="pxp-text-light">Review Details Of Candidate</p>
                
                <form method="post" action="{{url('reviewdetails')}}/{{base64_encode($candidate->id)}}" enctype="multipart/form-data">
                    @csrf

                   
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                 <label for="inputName">Rating </label>
                                <Select  class="form-control" id="rating" name="rating" required>
                                    <option value="" selected>Select </option>
                                    <option value="1" {{$candidate->rating==1?"selected":""}} >1</option>
                                    <option value="2" {{$candidate->rating==2?"selected":""}}>2</option>
                                    <option value="3" {{$candidate->rating==3?"selected":""}}>3</option>
                                    <option value="4" {{$candidate->rating==4?"selected":""}}>4</option>
                                    <option value="5" {{$candidate->rating==5?"selected":""}}>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="inputName">Behaviour </label>
                                <input type="text"  class="form-control" id="behaviour" name="behaviour" value="{{$candidate->behaviour}}">   
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="inputName">Timely Response </label>
                                <Select  class="form-control" id="timely_response" name="timely_response" required>
                                    <option value="" selected>Select </option>
                                    <option value="1" {{$candidate->timely_response==1?"selected":""}}>Yes</option>
                                    <option value="2" {{$candidate->timely_response==2?"selected":""}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="inputName">Communication Skill </label>
                                <Select  class="form-control" id="communication_skill" name="communication_skill" required>
                                    <option value="" selected>Select </option>
                                    <option value="Good" {{$candidate->communication_skill=='Good' ? "selected":""}}>Good</option>
                                    <option value="Very Good" {{$candidate->communication_skill=='Very Good' ? "selected":""}}>Very Good</option>
                                    <option value="Wrost" {{$candidate->communication_skill=='Wrost' ? "selected":""}}>Wrost</option>
                                   
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="inputName">Review </label>
                                <input type="text"  class="form-control" id="review" name="review" value="{{$candidate->review}}">   
                            </div>
                        </div>
                       
                    </div>
                   
                    

                    

                    <div class="mt-3 mt-lg-3">
                        <button class="btn rounded-pill pxp-section-cta">Save</button>
                    </div>
                </form>
            </div>

@endsection
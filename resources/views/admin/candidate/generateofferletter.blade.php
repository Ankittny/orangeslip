@extends('admin.layouts.app')

@section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif

<div class="pxp-dashboard-content-details">
                <h1>Generate Offer Letter</h1>
                <p class="pxp-text-light">Generate Offer Letter</p>
                 
                <form method="post" action="{{url('generateofferletter')}}/{{base64_encode($candidate->id)}}" enctype="multipart/form-data">
                    @csrf
                   
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Job Role *</label>
                                <select id="post" name="post" class="form-control" required>
                                    <option value="" selected>Select</option>
                                    @foreach($job_role as $role)
                                <option  value="{{$role->id}}">{{$role->name}}</option>                                
                                @endforeach
                                </select>
                            </div>
                            @if($errors->has('post'))
                                <span class="alert-danger">{{ $errors->first('post') }}</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Place Of Joining *</label>
                                <input type="text" id="place_of_joining" name="place_of_joining" class="form-control" placeholder="Enter Place Of Joining" value="{{old('place_of_joining')}}" required>
                            </div>
                            @if($errors->has('place_of_joining'))
                                <span class="alert-danger">{{ $errors->first('place_of_joining') }}</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Time Of Joining *</label>
                               
                                <input type="time"  id="time_of_joining" name="time_of_joining" class="form-control" placeholder="Enter Time Of Joining" required>
                            </div>
                            @if($errors->has('time_of_joining'))
                                <span class="alert-danger">{{ $errors->first('time_of_joining') }}</span>
                            @endif
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Joining Date *</label>
                                <input type="date"  class="form-control" id="joining_date" name="joining_date" required>   
                            </div>
                            @if($errors->has('joining_date'))
                                <span class="alert-danger">{{ $errors->first('joining_date') }}</span>
                            @endif
                        </div>
                    
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Annual CTC *</label>
                                <input type="number"  id="annual_ctc" name="annual_ctc" class="form-control" placeholder="Enter Annual CTC" required>
                            </div>
                            @if($errors->has('annual_ctc'))
                                <span class="alert-danger">{{ $errors->first('annual_ctc') }}</span>
                            @endif
                        </div>
                        
                  
                        {{--<div class="col-md-3">
                            <div class="mb-3">
                                <label  class="form-label">Offer Letter</label>
                                <input type="file" id="offer_letter" name="offer_letter" class="form-control" >
                            </div>
                            @if($errors->has('offer_letter'))
                                <span class="alert-danger">{{ $errors->first('offer_letter') }}</span>
                            @endif
                        </div>--}}
                      
                        <label>Salary Breakup:</label>
                        <label  class="form-label">Earnings</label>
                        <div class="table-responvive">
                            <table class="table table-bordered" id="dynamicEarning"> 
                                
                              
                                <tr>
                                    <td>
                                        <label for="document">Components</label>
                                        <select  name="salary[earning][0][component]" class="form-control" required>
                                            <option value="" selected>Select</option>
                                            @foreach($earnings as $earning)    
                                            <option value="{{$earning->component}}">{{$earning->component}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('components'))
                                            <span class="alert-danger">{{ $errors->first('components') }}</span>
                                        @endif
                                        
                                    </td>
                                    <td>
                                        <label class="form-label">Amount</label>
                                        <input type="number" name="salary[earning][0][amount]" class="form-control" required>
                                        @if($errors->has('amount'))
                                            <span class="alert-danger">{{ $errors->first('amount') }}</span>
                                        @endif
                                    </td>
                                    <td >
                                        <button type="button" name="add" id="dynamic-earning" class="btn btn-outline-primary">Add more</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="table-responvive">
                        <label class="form-label">Deductions</label>
                            <table class="table table-bordered" id="dynamicDeduction"> 
                                 
                               
                                <tr>
                                    <td>
                                        <label class="form-label">Components</label>
                                        <select   name="salary[deduction][0][component]" class="form-control" required>
                                            <option value="" selected>Select</option>
                                            @foreach($deductions as $deduction)    
                                            <option value="{{$deduction->component}}">{{$deduction->component}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('components'))
                                            <span class="alert-danger">{{ $errors->first('components') }}</span>
                                        @endif
                                        
                                    </td>
                                    <td>
                                        <label class="form-label">Amount</label>
                                        <input type="number" name="salary[deduction][0][amount]" class="form-control" required>
                                        @if($errors->has('amount'))
                                            <span class="alert-danger">{{ $errors->first('amount') }}</span>
                                        @endif
                                    </td>
                                    <td >
                                        <button type="button" name="add" id="dynamic-deduction" class="btn btn-outline-primary">Add more</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                               
                       
                        

                        
                        
                       
                    </div>

                    <div class="mt-3 mt-lg-3">
                        <button class="btn rounded-pill pxp-section-cta">Save</button>
                    </div>
                </form>
            </div>

@endsection


@push('js')
<script type="text/javascript">
    var i = 0;
    //$(document).on("click",".dynamic-ar",function(){
   $("#dynamic-earning").click(function () {
        ++i;
        $("#dynamicEarning").append('<tr><td><select  name="salary[earning]['+ i +'][component]" class="form-control" required> <option value="" selected>Select</option>@foreach($earnings as $earning)<option value="{{$earning->component}}">{{$earning->component}}</option>@endforeach</select></td><td><input type="number" name="salary[earning]['+ i +'][amount]" class="form-control" required></td><td><button type="button" class="btn btn-outline-danger remove-earning-field">Delete</button></td></tr>'
        );
    });
    $(document).on('click', '.remove-earning-field', function () {
        $(this).parents('tr').remove();
    });
 
    
    var j = 0;
    //$(document).on("click",".dynamic-ar",function(){
   $("#dynamic-deduction").click(function () {
        ++j;
        $("#dynamicDeduction").append('<tr><td><select  name="salary[deduction]['+ j +'][component]" class="form-control" required> <option value="" selected>Select</option>@foreach($deductions as $deduction)<option value="{{$deduction->component}}">{{$deduction->component}}</option>@endforeach</select></td><td><input type="number" name="salary[deduction]['+ j +'][amount]" class="form-control" required></td><td><button type="button" class="btn btn-outline-danger remove-deduction-field">Delete</button></td></tr>'
        );
    });
    $(document).on('click', '.remove-deduction-field', function () {
        $(this).parents('tr').remove();
    });
</script>
@endpush
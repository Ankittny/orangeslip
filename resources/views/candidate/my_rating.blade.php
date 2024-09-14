@extends('admin.layouts.app')

@section('content')

        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
        
                
    <div class="pxp-dashboard-content-details">
        <h1>Rating And Review</h1>
        <div class="table-responsive">
        @if(Auth::user()->account_type=='candidate')
            <table class="table footable  align-middle">
                <thead>
                    <tr>
                        <th>Business Name</th>
                        <th>Rating</th>
                        <th>Review</th>                              
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $v)
                    <tr>          
                        <td>{{$v->companyName}}</td>                   
                        <td>{{$v->rating}}</td>                   
                        <td>{{$v->review}}</td> 
                    </tr>
                    @endforeach 
                </tbody>
            </table>
            @else
            <table class="table footable  align-middle">
                <thead>
                    <tr>
                        <th>Candidate Name</th>
                        <th>Rating</th>
                        <th>Review</th>                              
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $v)
                    <tr>          
                        <td>{{$v->firstName}} {{$v->lastName}}</td>                   
                        <td>{{$v->review}}</td>                   
                        <td>{{$v->comment}}</td> 
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
   




@endsection


@extends('admin.layouts.app')

        @section('content')
        @if(session('success'))                                     
            <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
        @endif
        @if(session('error'))                                
            <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
        @endif
    <style>
        .rating {
        float:left;
        }

        /* :not(:checked) is a filter, so that browsers that don’t support :checked don’t 
        follow these rules. Every browser that supports :checked also supports :not(), so
        it doesn’t make the test unnecessarily selective */
        .rating:not(:checked) > input {
            position:absolute;
            top:-9999px;
            clip:rect(0,0,0,0);
        }

        .rating:not(:checked) > label {
            float:right;
            width:1em;
            padding:0 .1em;
            overflow:hidden;
            white-space:nowrap;
            cursor:pointer;
            font-size:200%;
            line-height:1.2;
            color:#ddd;
            text-shadow:1px 1px #bbb, 2px 2px #666, .1em .1em .2em rgba(0,0,0,.5);
        }

        .rating:not(:checked) > label:before {
            content: '★ ';
        }

        .rating > input:checked ~ label {
            color: #f70;
            text-shadow:1px 1px #c60, 2px 2px #940, .1em .1em .2em rgba(0,0,0,.5);
        }

        .rating:not(:checked) > label:hover,
        .rating:not(:checked) > label:hover ~ label {
            color: gold;
            text-shadow:1px 1px goldenrod, 2px 2px #B57340, .1em .1em .2em rgba(0,0,0,.5);
        }

        .rating > input:checked + label:hover,
        .rating > input:checked + label:hover ~ label,
        .rating > input:checked ~ label:hover,
        .rating > input:checked ~ label:hover ~ label,
        .rating > label:hover ~ input:checked ~ label {
            color: #ea0;
            text-shadow:1px 1px goldenrod, 2px 2px #B57340, .1em .1em .2em rgba(0,0,0,.5);
        }

        .rating > label:active {
            position:relative;
            top:2px;
            left:2px;
        }

        /* end of Lea's code */

        /*
        * Clearfix from html5 boilerplate
        */

        .clearfix:before,
        .clearfix:after {
            content: " "; /* 1 */
            display: table; /* 2 */
        }

        .clearfix:after {
            clear: both;
        }

        /*
        * For IE 6/7 only
        * Include this rule to trigger hasLayout and contain floats.
        */

        .clearfix {
            *zoom: 1;
        }

        /* my stuff */
        #status, button {
            margin: 20px 0;
        }

    </style>
    <div class="pxp-dashboard-content-details" >

        <div class="d-flex justify-content-between">
            <h4 class="text-themecolor">Professional Feedback ({{$candidate->candidate_code}})</h4>
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
                <div class="col-md-5" >
                    <strong>Email: {{$candidate->email}}</strong>
                </div>
                <div class="col-md-3 text-right">
                    <strong>Phone: {{$candidate->phone}}</strong>
                </div>                
            </div>        
        </div>     

        <form id="ratingForm" method="post" action="{{url('professional-feedback')}}/{{base64_encode($candidate->id)}}" enctype="multipart/form-data">
            @csrf
            @foreach($attributes as $c=>$attribute)
            
            @php
                $pf_count = count($professional_feedback);
                $point =0;
                
                if( $pf_count > 0){
                    $specific_value = $attribute->title;
                    $filtered_array = array_filter($professional_feedback, function($pf) use ($specific_value) {                       
                        return $pf->attribute == $specific_value;
                    });
                    $point = $filtered_array[$c]->point;
                }
            @endphp


            <div class="row">
                <div class="col-md-6">
                <legend>{{$attribute->name}}</legend>
                </div>
 
                <div class="col-md-6">
                @if(($point == 0)) 
                    <fieldset class="rating">
                        <input type="hidden" name="attribute[{{$attribute->title}}]" value="0"/>
                        @for($j=0;$j<$attribute->max_point;$j++)
                        <input type="radio" id="{{$attribute->title}}_star{{$j}}" name="attribute[{{$attribute->title}}]" value="{{$j}}"/><label for="{{$attribute->title}}_star{{$j}}" title="{{$j}} stars">{{$j}} stars</label>
                        @endfor
                        {{--<input type="radio" id="{{$attribute->title}}_star9" name="attribute[{{$attribute->title}}]" value="9" /><label for="{{$attribute->title}}_star9" title="9 stars">9 stars</label>
                        <input type="radio" id="{{$attribute->title}}_star8" name="attribute[{{$attribute->title}}]" value="8" /><label for="{{$attribute->title}}_star8" title="8 stars">8 stars</label>
                        <input type="radio" id="{{$attribute->title}}_star7" name="attribute[{{$attribute->title}}]" value="7" /><label for="{{$attribute->title}}_star7" title="7 stars">7 stars</label>
                        <input type="radio" id="{{$attribute->title}}_star6" name="attribute[{{$attribute->title}}]" value="6" /><label for="{{$attribute->title}}_star6" title="6 star">6 star</label>

                        <input type="radio" id="{{$attribute->title}}_star5" name="attribute[{{$attribute->title}}]" value="5" /><label for="{{$attribute->title}}_star5" title="5 stars">5 stars</label>
                        <input type="radio" id="{{$attribute->title}}_star4" name="attribute[{{$attribute->title}}]" value="4" /><label for="{{$attribute->title}}_star4" title="4 stars">4 stars</label>
                        <input type="radio" id="{{$attribute->title}}_star3" name="attribute[{{$attribute->title}}]" value="3" /><label for="{{$attribute->title}}_star3" title="3 stars">3 stars</label>
                        <input type="radio" id="{{$attribute->title}}_star2" name="attribute[{{$attribute->title}}]" value="2" /><label for="{{$attribute->title}}_star2" title="2 stars">2 stars</label>
                        <input type="radio" id="{{$attribute->title}}_star1" name="attribute[{{$attribute->title}}]" value="1" /><label for="{{$attribute->title}}_star1" title="1 stars">1 star</label>--}}

                    </fieldset>
                @else

                @for($i=0;$i<$filtered_array[$c]->point;$i++)
                    <span class="fa fa-star checked" style="color: gold; font-size:190%;"></span>
                @endfor
                        
                @endif
                </div>
            </div>
            @endforeach
            <div class="clearfix"></div>
            <div class="col-md-2">
                <button class="submit clearfix btn rounded-pill btn-block pxp-section-cta">Submit</button>
            </div>
        </form>
    </div>
    
    
@endsection

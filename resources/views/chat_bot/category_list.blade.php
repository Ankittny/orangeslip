@extends('layouts.app')

@section('content')
    <div class="pt-4">
        <div class="container">

            <div class="position-relative back_btn_padding">
                <a href="{{ route('chat-category.index',$current_category->sub_category) }}" class="back_btn position">
                    <img src="/assets/img/back.png" alt="">
                </a>
                <h5 class="text-center mb-0">{{ __('Add Chat Questions') }}</h5>
            </div>

           
            <div class="card">
                <div class="card-body">
                    @include('flash::message')

                    <div class="">
                        <div class="form-body ">
                            <div class="row">

                                <div class="col-md-6 col-sm-12 col-lg-6">
                                    <h6> Sub Categories of {{$current_category->category}}</h6>
                                    <div class="add_field add_btn">
                                        <p class="btn btn-primary btn-sm add_field_button">+</p>
                                    </div>
                                    <ul class="list-group mt-4">
                                        @foreach ($sub_categories as $category)
                                            <li class="list-group-item"><a href="{{ route('chat-category.index', $category->id) }}" id="{{$category->id}}" class="custom_list" >{{$category->category}}</a>
                                                <button type="button" id={{$category->id}} class="btn btn-danger btn-sm ajaxCall close" style="">×</button>
                                                <a title="Edit" style="position: absolute;left: 30rem;top: 0.2rem;" class="btn btn-sm btn-success" href="{{route('chat-category.edit',$category->id)}}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            </li>
                                        @endforeach  
                                    </ul>  
                                </div>
                                <div class="col-md-6 col-sm-12 col-lg-6">
                                    <form action="{{ route('chat-category.store',$current_category->id) }}" method="post" class="sub_dis">
                                        {{ csrf_field() }}
                                        @if ($errors->any())
                                            @foreach ($errors->all() as $error)
                                                <div class="help-block text-danger">{{ $error }}</div>
                                            @endforeach
                                        @endif

                                        <div class="ajax_data"></div>
                                    </form>   
                                </div>

                            </div>
                        </div>
                    </div>
                         
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        
        $(document).on('click', '.add_field', function(e) {
            var id={{ $current_category->id }} ;
        
            $('.ajax_data').append(
                '<label class="control-label mt-4">Add Sub Category</label>' +
                '<textarea class="form-control  mb-2" rows="2" name="sub_category" placeholder="Enter Sub Category"></textarea>'+
                '<textarea class="form-control mb-2" rows="2" name="desc" placeholder="Enter Description"></textarea>'+
                '<input type="hidden" name="sub_category_id" ' + ' value=' + id + ' />'+
                '<button type="submit" class="btn theme_btn action_btn mt-1">Submit</button>');
            $('.add_field').prop('disabled', true);
        });
    </script>
    <script>
        $(document).ready(function() {
            var base = $('base').attr('href');
            url = base.slice(0, base.lastIndexOf('/'));
            $(".ajaxCall").click(function() {
                var element = $(this).attr('id');
                var data = {
                    _token: $('meta[name=_token]').attr('content'),
                    _method: 'POST',
                    element: element
                };
                bootbox.confirm(
                    "The category and it's sub categories(if any) will be unavailable from now. Are you sure you want to do this?",
                    function(result) {
                        if (result) {
                            
                            $.ajax({
                                url: url + '/chat/category/remove/' + element,
                                type: 'POST',
                                data: data,
                                dataType: 'json',
                                success: function(result) {
                                   
                                    if (result.status == true) {
                                        bootbox.alert(result.message);
                                        window.location.reload();
                                    } else {
                                        bootbox.alert(result.message);
                                    }
                                },
                            });
                        }
                    }
                );
            });
        });
    </script>
@endpush 

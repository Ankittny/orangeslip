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
                        <form action="{{ route('chat-category.store',$current_category->id) }}" method="post" class="sub_dis">
                            {{ csrf_field() }}
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="help-block text-danger">{{ $error }}</div>
                                @endforeach
                            @endif
                            <div class="form-body ">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <label class="control-label">{{ __('Add Sub Category of ' . $current_category->category ) }} : </label>
                                        <textarea class="form-control" rows="2" name="sub_category" value="{{ old('sub_category') }}" placeholder="{{ __('Enter Sub Category') }}"></textarea>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <label class="control-label">{{ __('Add Description') }} : </label>
                                        <textarea class="form-control" rows="2" name="desc" value="{{ old('desc') }}"
                                        placeholder="{{ __('Enter Description') }}"></textarea>
                                        <span class="help-block text-danger">{{ $errors->first('desc') }}</span>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <input type="hidden" name="sub_category_id" value={{$current_category->id}}>
                                    </div>
                                
                                    <div class="text-center col-md-12 mt-4">
                                        <button type="submit"
                                            class="btn btn-primary action_btn">{{ __('Submit') }}</button>
                                        <a href="{{ route('chat-category.index',$current_category->sub_category) }}" class="btn btn-danger">{{ __('Cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </form>    
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

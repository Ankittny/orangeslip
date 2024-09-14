@extends('admin.layouts.app')
@section('content')
    <!-- Page content -->
    <div class="secondary-nav">
        <ul class="nav nav-tabs">
            <li><a class="active" href="{{ url(Config::get('app.locale').'/home') }}">{{ __('Home') }}</a></li>
            <li>{{ __('Manage Ability') }}</li>
        </ul>
    </div>
    <br>
    <div class="page-content">
        <div class="container-fluid">
            @include('flash::message')
            <h6 class="white-color">{{ __('List of abilities') }}</h6>
            <div class="card-widget white">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Ability') }}</th>
                        {{--<th>Action</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($abilities as $ability)
                        <tr data-id="{{ $ability->id }}">
                            <th>{{ $ability->id }}</th>
                            <th>{{ $ability->title }}</th>
                            <th>{{ $ability->name }}</th>
                            {{--<th>
                                <a name="manage-role" class="btn btn-warning">
                                    <i class="icon icon-edit"></i>
                                </a>
                                <a name="manage-role" class="btn btn-danger">
                                    <i class="icon icon-trash-o"></i>
                                </a>
                            </th>--}}
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right">{{ $abilities->links() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
<!-- Modal -->
<div id="myModal" class="modal fade myModal" role="dialog"></div>
<div id="myLoader" class="modal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{asset('img/ajax-loader.gif')}}">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@endsection
@push('scripts')
<script src="{{asset('assets/global/plugins/bootbox/bootbox.min.js')}}"></script>
<script>
    $(document).ready(function(){

        $('a[name="manage-role"]').click(function() {

            var id = $(this).closest('tr').data('id');
            var url = $('base').attr('href');

            $.ajax({
                type:'GET',
                url:url + '/acl/role/' + id + '/manage-ability',
                dataType:'html',
                beforeSend: function () {
                    $('div[id=myLoader]').modal({backdrop:false});
                },
                success: function (result) {
                    $('div[id=myModal]')
                            .empty().html(result);
                    $('div[id=myLoader]').modal('hide');
                    $('div[id=myModal]').modal({backdrop:false});

                },
                error: function (result) {
                    $('div[id=myLoader]').modal('hide');
                    console.log(result);
                }
            })
        });

        $('a[id=changeStatus]').click(function(event){
            event.preventDefault();
            var element = $(this);
            bootbox.confirm({
                title: "Change Member Status",
                message: "Do you really want to change status of this member?",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm'
                    }
                },
                callback: function (result) {
                    if(result) {
                        window.location = element.attr('href');
                    }
                }
            });
        });
    });
</script>
@endpush
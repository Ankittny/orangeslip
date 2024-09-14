@extends('admin.layouts.app')
@section('content')
    <!-- Page content -->
  
<section class="container-fluid">
    
 <div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">{{ __('Manage Roles') }}</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			{{--<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/"> {{ __('Home') }}</a></li>
				<li class="breadcrumb-item active">{{ __('Manage Roles') }}</li>
			</ol>--}}
		</div>
	</div>
</div>
    <div class="card">
    {{--@include('flash::message')--}}
        
        <div class="card-body new-user order-list">
        <h4 class="card-title">
                {{ __('List of roles') }} 
                {{--@can('access-manage-role-create')--}}
                <span class="float-right"><a href="{{ route('acl.role.create') }}" class="btn-orange-outline">{{ __('Create Role') }}</a></span>
                <span class="float-right"><a href="{{ route('acl.assign_role') }}" class="btn-orange-outline">{{ __('Assign Role') }}</a></span>
                {{--@endcan--}}
                </h4>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr data-id="{{ $role->id }}"> 
                        <th>{{ $role->id }}</th>
                        <th>{{ $role->title }}</th>
                        <th class="text-success">{{ $role->name }}</th>
                        <th>
							{{--@can('access-manage-permission-set')--}}
                            <a name="manage-role" class="btn btn-info btn-sm">{{ __('Settings') }}</a>
                            {{--@endcan--}}
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </div>
</section>
                  

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

{{--<div class="modal" tabindex="-1" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>--}}

<!-- Modal -->
@endsection
@push('js')
<script>
    $(document).ready(function(){

        $('a[name="manage-role"]').click(function() {

            var id = $(this).closest('tr').data('id');
            var url = $('base').attr('href');

            $.ajax({
                type:'GET',
                url:url + '/acl/role/' + id + '/manage-permission',
                dataType:'html',
                beforeSend: function () {
                    $('div[id=myLoader]').modal({backdrop:false});
                },
                success: function (result) {
                    $('div[id=myModal]')
                            .empty().html(result);
                    //$('div[id=myLoader]').modal('hide');
                    $('#myModal').modal('show');
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

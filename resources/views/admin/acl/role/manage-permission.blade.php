<div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form name="setPermission" method="post" action="{{ route('acl.role.manage.permission.set', $role->id) }}">
                    {{ csrf_field() }}
                    @foreach($permissions as $key=>$permission)
						<div class="col-md-12 form-group form-check">
							<input type="checkbox" id="md_checkbox_{{$key}}" name="abilities[]" value="{{ $permission->name }}" class="filled-in chk-col-blue" {{ in_array($permission->name, $selected)?"checked":"" }}>
							<label for="md_checkbox_{{$key}}">{{ $permission->title}}</label>
						</div>
                       
                    @endforeach
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="button" class="btn btn-success" name="submit">{{ __('Submit') }}</button>                        
                    </div>
                </form>                        
			</div>            
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("button[name='submit']").click(function(){
            var form = $(this).closest('form');  
            var action = form.attr('action');
                      
            $.ajax({
                type:'POST',
                url:action,
                data:form.serialize(),
                dataType:'json',
                beforeSend: function () {
                    $('div[id=myLoader]').modal({backdrop:false});
                },
                success: function (result) {
                    $('div[id=myModal]')
                            .empty().html(result);
                    $('div[id=myLoader]').modal('hide');
                    $('div[id=myModal]').modal({backdrop:false});
                    location.reload();
                },
                error: function (result) {
                    $('div[id=myLoader]').modal('hide');
                    console.log(result);
                }
            });
        });
    });
</script>

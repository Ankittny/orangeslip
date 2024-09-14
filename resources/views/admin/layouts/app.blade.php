<!doctype html>
<html lang="en" class="pxp-root">
<base href="{{ url('/')}}">  
@include('layouts.htmlheader')


<body style="background-color: var(--pxpMainColorLight);">
    @include('layouts.adminheader')

    <div class="pxp-dashboard-content">

        @yield('content')


        @include('layouts.adminfooter')
    </div>


    @include('layouts.script')
    @stack('js')

    </body>
</html>

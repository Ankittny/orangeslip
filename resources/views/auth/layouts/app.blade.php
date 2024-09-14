<!doctype html>
<html lang="en" class="pxp-root">
 
@include('layouts.htmlheader')

<style>
    .pxp-header.pxp-has-border{border-bottom:none}
    footer.mt-70{margin-top:0px}
</style>



<body>

    @include('layouts.homeheader')

        @yield('content')

  <footer>
  <div class="pxp-main-footer-bottom">
        <div class="pxp-container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-auto">
                    <div class="pxp-footer-copyright pxp-text-light">© <script>document.write(new Date().getFullYear())</script> Orangeslip. All Right Reserved.</div>
                </div>
                <div class="col-lg-auto">
                    <div class="pxp-footer-social mt-3 mt-lg-0">
                        <ul class="list-unstyled">
                            <li><a href="#"><span class="fa fa-facebook"></span></a></li>
                            <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                            <li><a href="#"><span class="fa fa-instagram"></span></a></li>
                            <li><a href="#"><span class="fa fa-linkedin"></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </footer>


@include('layouts.script')

</body>

</html>

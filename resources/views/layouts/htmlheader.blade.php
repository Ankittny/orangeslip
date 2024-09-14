<head>


    <meta charset="utf-8">
    @php
    $url=Request::path();
    //dd($url);
    $metaData=DB::table('meta_data')->where('url',$url)->first();
    //dd($metaData);
    @endphp

  


    <title>{{$metaData!=null ? $metaData->meta_title : 'Orangeslip'}}</title>
    <meta name="description" content="{{$metaData!=null ? $metaData->meta_description : 'Orangeslip'}}">
    <meta name="keywords" content="{{$metaData!=null ? $metaData->meta_keywords : 'Orangeslip'}}">
    <meta name="author" content="Orangeslip">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="google-site-verification" content="r91bcDhyvSNpqclIxD086YrKvNqsYqiL9MAs1t8zSWY" />
    <link rel="canonical" href="https://orangeslip.com/" />

    <link rel="shortcut icon" href="/new/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&amp;display=swap">
    <link rel="stylesheet" href="/new/css/bootstrap.min.css">
    <link rel="stylesheet" href="/new/css/font-awesome.min.css">
    <link rel="stylesheet" href="/new/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/new/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/new/css/animate.css">
    <link rel="stylesheet" href="/new/css/selectize.css">
    <link rel="stylesheet" href="/new/css/toastr.css">
    <link rel="stylesheet" href="/new/css/magnific-popup.css?v={{time()}}">
    <link rel="stylesheet" href="/new/css/footable.css?v={{time()}}">
    <link rel="stylesheet" href="/new/css/style.css?v={{time()}}">
    <!-- <link rel="stylesheet" href="/new/css/masking-input.css?v={{time()}}"> -->

        <script src="/new/js/jquery-3.4.1.min.js"></script>
    <!-- <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script> -->
    <script src="/new/js/toastr.js?v={{time()}}"></script>

      <!--  googletagmanager  code -->
      <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-T4W3XBFT');</script>
    <!--  googletagmanager  code end -->


    <script type="application/ld+json">
      {
      "@context": "https://schema.org/",
      "@type": "WebSite",
      "name": "Orange Slip",
      "url": "https://orangeslip.com/"
      }
    </script>

</head>
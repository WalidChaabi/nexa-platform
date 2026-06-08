<!doctype html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SEO Meta description -->
    <meta name="description"
        content="AppCo App Landing Page Template. agency landing page template helps you easily create websites for your business, marketing landing page template form promotion and many more.">
    <meta name="author" content="ThemeTags">

    <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
    <meta property="og:site_name" content="" /> <!-- website name -->
    <meta property="og:site" content="" /> <!-- website link -->
    <meta property="og:title" content="" /> <!-- title shown in the actual shared post -->
    <meta property="og:description" content="" /> <!-- description shown in the actual shared post -->
    <meta property="og:image" content="" /> <!-- image link, make sure it's jpg -->
    <meta property="og:url" content="" /> <!-- where do you want your post to link to -->
    <meta property="og:type" content="article" />

    <!--title-->
    <title>SHERIN.MA | LOGIN</title>
    <style>
        .form-group{
            display: block !important;
        }
        .text-danger{
            position:initial !important;
        }
    </style>

    <!--favicon icon-->
    <link rel="icon" href="../../public/favicon.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.14/dist/sweetalert2.min.css">
    <!--google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700%7COpen+Sans&display=swap"
        rel="stylesheet">

    <!--Bootstrap css-->
    <link rel="stylesheet" href="{{ asset('new/css/bootstrap.min.css') }}">
    <!--Magnific popup css-->
    <link rel="stylesheet" href="{{ asset('new/css/magnific-popup.css') }}">
    <!--Themify icon css-->
    <link rel="stylesheet" href="{{ asset('new/css/themify-icons.css') }}">
    <!--animated css-->
    <link rel="stylesheet" href="{{ asset('new/css/animate.min.css') }}">

    <!--Owl carousel css-->
    <link rel="stylesheet" href="{{ asset('new/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('new/css/owl.theme.default.min.css') }}">
    <!--custom css-->
    <link rel="stylesheet" href="{{ asset('new/css/style.css') }}">
    <!--responsive css-->
    <link rel="stylesheet" href="{{ asset('new/css/responsive.css') }}">

    <script src="{{ asset('new/js/jquery-3.5.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.14/dist/sweetalert2.min.js"></script>

</head>

<body>
    <div class="overlay d-none">
        <div class="sk-chase">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>
    </div>

    <!--body content wrap start-->
    <div class="main">

        @yield('content')

    </div>


    <!--jQuery-->

    <!--Popper js-->

    <script src="{{ asset('new/js/popper.min.js') }}"></script>
    <!--Bootstrap js-->
    <script src="{{ asset('new/js/bootstrap.min.js') }}"></script>
    <!--Magnific popup js-->
    <script src="{{ asset('new/js/jquery.magnific-popup.min.js') }}"></script>
    <!--jquery easing js-->
    <script src="{{ asset('new/js/jquery.easing.min.js') }}"></script>

    <!--wow js-->
    <script src="{{ asset('new/js/wow.min.js') }}"></script>
    <!--owl carousel js-->
    <script src="{{ asset('new/js/owl.carousel.min.js') }}"></script>
    <!--countdown js-->
    <script src="{{ asset('new/js/jquery.countdown.min.js') }}"></script>
    <!--validator js-->
    <script src="{{ asset('new/js/validator.min.js') }}"></script>
    <!--custom js-->
    <script src="{{ asset('new/js/scripts.js') }}"></script>
</body>

</html>

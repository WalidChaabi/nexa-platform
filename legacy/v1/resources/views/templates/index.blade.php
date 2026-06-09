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
    <title>NEXA | ACCUEIL</title>

    <!--favicon icon-->
    <link rel="icon" href="../../../../favicon.ico" type="image/png" sizes="16x16">
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

    <style>
        .overlay {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1000000000000000000000000000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
        }

        .sk-chase {
            z-index: 19574;
            width: 40px;
            height: 40px;
            position: relative;
            animation: sk-chase 2.5s infinite linear both;
        }

        .sk-chase-dot {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            animation: sk-chase-dot 2.0s infinite ease-in-out both;
        }

        .sk-chase-dot:before {
            content: '';
            display: block;
            width: 25%;
            height: 25%;
            background-color: #4420b1;
            border-radius: 100%;
            animation: sk-chase-dot-before 2.0s infinite ease-in-out both;
        }

        .sk-chase-dot:nth-child(1) {
            animation-delay: -1.1s;
        }

        .sk-chase-dot:nth-child(2) {
            animation-delay: -1.0s;
        }

        .sk-chase-dot:nth-child(3) {
            animation-delay: -0.9s;
        }

        .sk-chase-dot:nth-child(4) {
            animation-delay: -0.8s;
        }

        .sk-chase-dot:nth-child(5) {
            animation-delay: -0.7s;
        }

        .sk-chase-dot:nth-child(6) {
            animation-delay: -0.6s;
        }

        .sk-chase-dot:nth-child(1):before {
            animation-delay: -1.1s;
        }

        .sk-chase-dot:nth-child(2):before {
            animation-delay: -1.0s;
        }

        .sk-chase-dot:nth-child(3):before {
            animation-delay: -0.9s;
        }

        .sk-chase-dot:nth-child(4):before {
            animation-delay: -0.8s;
        }

        .sk-chase-dot:nth-child(5):before {
            animation-delay: -0.7s;
        }

        .sk-chase-dot:nth-child(6):before {
            animation-delay: -0.6s;
        }

        @keyframes sk-chase {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes sk-chase-dot {

            80%,
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes sk-chase-dot-before {
            50% {
                transform: scale(0.4);
            }

            100%,
            0% {
                transform: scale(1.0);
            }
        }
    </style>

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

    <!--header section start-->
    @include('components.header')
    <!--header section end-->

    <!--body content wrap start-->
    <div class="main">

        @yield('content')

    </div>
    <!--body content wrap end-->

    <!--shape image start-->
    <div class="shape-img subscribe-wrap">
        <img src="{{ asset('new/images/footer-top-shape.webp') }}" alt="footer shape" class="img-fluid">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    {{-- <form action="#" method="post"
                        class="subscribe-form subscribe-form-footer d-none d-md-block d-lg-block">
                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control input" id="email-footer" name="email"
                                placeholder="info@yourdomain.com">
                            <input type="submit" class="button btn solid-btn" id="submit-footer" value="Subscribe">
                        </div>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>
    <!--shape image end-->

    <!--footer section start-->
    @include('components.footer')
    <!--footer section end-->

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

    <script>
        function submit(e, _form) {
            e.preventDefault();
            e.stopPropagation();
            const action = _form.getAttribute('action');
            const csrf = $('meta[name="csrf-token"]').attr('content');

            const form = new FormData(_form);

            displayLoading(true);

            console.log('------------------------------------');
            console.log('ACTIONNNNNNNNNNNNNNNNNNNNNNNN');
            console.log('------------------------------------');

            $.ajax(action, {
                type: 'POST',
                dataType: 'jsonp',
                cors: true,
                enctype: 'application/x-www-form-urlencoded',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: form,
                processData: false,
                contentType: false,
                success: function(s, status, xhr) {
                    //toastr.success("Votre message est envoyé avec succès");
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Votre message est envoyé avec succès!",
                        showConfirmButton: false,
                        iconColor: "#9629e6",
                        confirmButtonColor: "#3085d6",
                        timer: 1500,
                    });
                },
                error: function(e) {
                    const errors = e.responseJSON?.errors;

                    if (errors) {

                        console.log(Object.keys(errors));

                        Object.keys(errors).forEach(key => {
                            const value = errors[key];

                            displayError(key, true);
                        });
                    }

                    displayLoading(false);

                },
                complete: (_) => displayLoading(false),
            });

            return false;
        }

        const form = document.getElementById('form-contact');

        form.addEventListener('submit', function(e) {
            submit(e, form);
        });

        function displayError(el, err) {
            const span = $('div:not(.form-group) > span.' + el)
            if (!err) span.addClass('d-none');
            else span.removeClass('d-none');
        }

        $('#form-contact input').on('input', function(e) {
            const el = e.target.getAttribute('name');
            displayError(el, false);
        })

        function displayLoading(isLoading) {
            const div = $('body > .overlay');
            if (isLoading) div.removeClass('d-none');
            else div.addClass('d-none');
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="SHERIN admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, SHERIN admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <title>Sherin</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/datatables.css">
    <link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @include('layouts.simple.css')
    @yield('style')
</head>

<body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        @include('layouts.simple.header')
        <!-- Page Header Ends   -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper sidebar-icon">
            <!-- Page Sidebar Start-->
            @if (auth()->user()->is_admin == 1)
                @include('layouts.admin.sidebar')
            @elseif(auth()->user()->is_admin == 2)
                @include('layouts.owner.sidebar')
            @elseif(auth()->user()->is_admin == 3)
                @include('layouts.employe.sidebar')
            @endif
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-6">
                                @yield('breadcrumb-title')
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        {{-- <a href="{{ route('index') }}"> <i data-feather="home"></i></a> --}}
                                    </li>
                                    @yield('breadcrumb-items')
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                @yield('content')
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            @include('layouts.simple.footer')
        </div>
    </div>
    <!-- latest jquery-->
    @include('layouts.simple.script')
</body>

</html>
<style>
    .page-wrapper.compact-wrapper .page-body-wrapper.sidebar-icon div.sidebar-wrapper .sidebar-main .sidebar-links li a span {
        text-transform: none;
    }
</style>
<script>
    if (localStorage.getItem('mode') == 'dark') {
        $('body').toggleClass('dark-only')
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>

    @if (Session::has('message'))
        var notify = $.notify('<i class="fa fa-check"></i><strong>Loading</strong> page Do not close this page...', {
            type: 'theme',
            allow_dismiss: true,
            delay: 2000,
            showProgressbar: true,
            timer: 300,
            color: "#fff",
            background: "#D44950",
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
        });

        setTimeout(function() {
            notify.update('message',
                '<i class="fa fa-check"></i><strong>Succes :</strong> Reste est payer avec Succes');
        }, 1000);
    @endif
</script>

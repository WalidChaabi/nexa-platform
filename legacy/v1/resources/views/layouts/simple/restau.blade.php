<!DOCTYPE html>
<html lang="fr">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="NEXA admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
  <meta name="keywords" content="admin template, NEXA admin template, dashboard template, flat admin template, responsive admin template, web app">
  <meta name="author" content="pixelstrap">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
  <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
  <title>Nexa</title>
  <!-- Google font-->
  <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/datatables.css">
  <link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/scrollbar.css">
  @include('layouts.simple.css')
  @yield('style')
</head>

<body>
  <!-- tap on top starts-->
  <div class="tap-top"><i data-feather="chevrons-up"></i></div>
  <!-- tap on tap ends-->
  <!-- page-wrapper Start-->
  <div class="page-wrapper compact-wrapper" id="pageWrapper">
    @include('layouts.simple.headerPos')

    <div class="page-body-wrapper sidebar-icon">
      <div class="page-body" style="margin-top: 0px;margin-left: 0px;">
        {{-- <div class="container-fluid">
          <div class="page-title">
            <div class="row">
              <div class="col-6">
                @yield('breadcrumb-title')
              </div>
              <div class="col-6">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                    <a href="{{ route('index') }}"> <i data-feather="home"></i></a>
                  </li>
                  @yield('breadcrumb-items')
                </ol>
              </div>
            </div>
          </div>
        </div> --}}
        <!-- Container-fluid starts-->
        @yield('content')
        <!-- Container-fluid Ends-->
      </div>
      <!-- footer start-->
    </div>
  </div>
  <!-- latest jquery-->
  @include('layouts.simple.script')
</body>

</html>
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
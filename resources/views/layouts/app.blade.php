@php 
use App\GeneralSettings;
$general_settings = GeneralSettings::where('setting_type',1)->get()->toArray();
@endphp
<!DOCTYPE html>
<html class="loading" lang="de" data-textdirection="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="apple-touch-icon" href="images/favicon/apple-touch-icon-152x152.png">
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon/favicon-32x32.png">

  <!-- Include core + vendor Styles -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/dist/css/adminlte.min.css') }}">
    @toastr_css 
    @yield('css')
</head>
<body class="hold-transition login-page">
    @yield('content')
<script src="{{ asset('theme/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('theme/dist/js/adminlte.min.js')}}"></script>
<!-- jquery-validation -->
<script src="{{ asset('theme/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('theme/plugins/jquery-validation/additional-methods.min.js')}}"></script>

@yield('js')
</body>
@toastr_js
@toastr_render
</html>
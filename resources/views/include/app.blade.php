@php 
use App\User;
use App\GeneralSettings;
$users_data = User::find(Auth::id());
$general_settings = GeneralSettings::where('setting_type',1)->get()->toArray();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $general_settings[2]['field_value'] }}</title>

  @if(\File::exists(public_path('upload/images/general_settings/'.$general_settings[0]['field_value'])) && !empty($general_settings[0]['field_value']))
    <link rel="icon" type="image/png" href="{{asset('public/upload/images/general_settings/'.$general_settings[0]['field_value'])}}"/>
  @endif

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('theme/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/summernote/summernote-bs4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('theme/sweetalert/sweetalert.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/datepicker/datepicker.css')}}">
  <link rel="stylesheet" href="{{ asset('theme/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('theme/dist/css/style.css')}}">
  <link rel="stylesheet" href="{{ asset('theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

  @toastr_css 
  @yield('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  {{--<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('theme/dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
  </div>--}}
    @include('include/header')
    <!-- Left side column. contains the logo and sidebar -->
    @include('include/sidebar')
    <!-- Content Wrapper. Contains page content -->
    @yield('content')
    <!-- Footer part -->
    @include('include/footer')

  

  
  

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('theme/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datepicker/datepicker.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  // $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('theme/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('theme/plugins/sparklines/sparkline.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('theme/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('theme/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('theme/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('theme/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('theme/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('theme/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('theme/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="{{ asset('theme/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('theme/dist/js/demo.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('theme/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('theme/plugins/jquery-validation/additional-methods.min.js')}}"></script>
<script src="{{ asset('theme/sweetalert/sweetalert.js')}}"></script>
<script src="{{ asset('theme/sweetalert/sweetalert-extra.js')}}"></script>

<!-- Select2 -->
<script src="{{ asset('theme/plugins/select2/js/select2.full.min.js')}}"></script>

@yield('js')

<script type="text/javascript">
  $(function(){
    //Initialize Select2 Elements
    // $('.select2').select2();
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    $('.numbers').on('keypress',function(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) 
        {
                   return false;
        }
    })
    $('.numbers-decimal').on('keypress',function(event){
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
            ((event.which < 48 || event.which > 57) &&
              (event.which != 0 && event.which != 8))) {
            event.preventDefault();
          }
          var text = $(this).val();
          if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
          }
    })

    $.validator.addMethod('filesize', function(value, element, param) {
      return this.optional(element) || (element.files[0].size <= param) 
    });
    var base_url = "{{\URL::to('/')}}"+"/";
  });
    $('.all-check').on('click',function(){
       if($(this).prop("checked") == true){
            $('.single-check').each(function(){
                this.checked = true;
            });
        }
        else{
            $('.single-check').each(function(){
                this.checked = false;
            });
        }
    });
    $('#page-length-option').delegate('.single-check','click', function(e){
        if($(this).prop("checked") == true){
            $('.all-check').prop( "checked", true );
        }
        else{
            if($('.single-check:checked').length<1){
                $('.all-check').prop( "checked", false ); 
            }
        }  
    });

    $('#page-length-option').delegate('.status_change', 'change', function(e) { 
      //console.log("sfgsdgfsd");
      
        var array = {'id':$(this).data('id'),'data':$(this).data('data'),"_token": "{{ csrf_token() }}"};
        
         $.ajax({
             type: "POST",
             url: "{{route('status-change')}}",
             data: array, // serializes the form's elements.
             success: function(data)
             {
                  toastr.clear();
                  toastr.success("Status Changed !!");
             }
           });
      });

    $('#page-length-option').delegate('.feature_change', 'change', function(e) { 
      //alert("heyyyyy");
      //var id = $(this).attr('sid');
        var array = {'id':$(this).attr('sid'),'data':$(this).data('data'),"_token": "{{ csrf_token() }}"};
        console.log(array);
         $.ajax({
             type: "POST",
             url: "{{route('feature-change')}}",
             data: array, // serializes the form's elements.
             success: function(data)
             {
                  toastr.clear();
                  toastr.success("Feature Changed !!");
             }
           });
      });
      

      $('.delete-datas').on('click',function(){
            var check_array = [];
            $('.single-check:checked').each(function(k,v){
                check_array.push($(v).val());
            })
            var array = {'check':check_array,'data':$(this).data('data'),"_token": "{{ csrf_token() }}"};
            if($('.single-check:checked').length<1){
                swal("Please minimum one record check!")
            }else{
               swal({
                title: "Are you sure?",
                text: "You will want to delete this data?",
                icon: 'warning',
                dangerMode: true,
                buttons: {
                  cancel: 'No',
                  delete: 'Yes'
                }
                  }).then(function (willDelete) {
                    if (willDelete) {
                       $.ajax({
                           type: "POST",
                           url: "{{route('delete-all')}}",
                           data: array, // serializes the form's elements.
                           success: function(data)
                           {
                                location.reload();
                           }
                         });
                    } else {
                      swal("Your imaginary data is safe", {
                        title: 'Cancelled',
                        icon: "error",
                      });
                    }
                  });
            }

        })

</script>
</body>
@toastr_js
@toastr_render
</html>

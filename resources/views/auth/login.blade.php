@extends('layouts.app')
@php 
use App\GeneralSettings;
$general_settings = GeneralSettings::where('setting_type',1)->get()->toArray();
@endphp
@section('content')
<div class="login-box">
    <div class="login-logo">
      <a href="{{url('/')}}"><b>{{ $general_settings[2]['field_value'] }}</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('Login') }}</p>

        <form novalidate="novalidate" id="login" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group input-group mb-3">
                <input id="phone" type="text" class="form-control required" placeholder="Mobile No." name="phone" value="{{ old('phone') }}"  autocomplete="phone" autofocus>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-phone"></span>
                  </div>
                </div>
            </div>
            <div class="form-group input-group mb-3">
                <input id="password" type="password" class="form-control required" name="password"  autocomplete="current-password" placeholder="Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
            </div>
          <div class="row">
            <div class="col-8">
              {{--<div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>--}}
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>

        <p class="mb-1">
          @if (Route::has('forget.password.get'))
              <a href="{{ route('forget.password.get') }}">
                  {{ __('Forgot Your Password?') }}
              </a>
          @endif
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>

@endsection

@section('js')
<script type="text/javascript">
  $(document).ready(function(){
  $('#login').validate({
    rules: {
      phone: {
        required: true,
        phone: true,
      },
      password: {
        required: true,
        minlength: 5
      },
      terms: {
        required: true
      },
    },
    messages: {
      phone: {
        required: "Please enter a mobile no.",
        phone: "Please enter a vaild mobile no."
      },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      terms: "Please accept our terms"
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
  })
</script>
@endsection

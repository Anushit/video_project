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
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

        <form id="reset-password" method="POST" action="{{ route('reset.password.post') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group input-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"  autofocus placeholder="Email">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>
            

            <div class="form-group input-group mb-3">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="New Password" name="password" required >
                               
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
             

            <div class="form-group input-group mb-3">
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Change password</button>
              </div>
              <!-- /.col -->
            </div>
        </form>

      <p class="mt-3 mb-1">
        <a href="login.html">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
  $(document).ready(function(){
  $('#reset-password').validate({
    rules: {
      email: {
        required: true,
        email: true,
      },
      password:{
        required:true,
        minlength:8,
      },
      password_confirmation:{
        required:true,
        minlength:8,
        equalTo:'#password'
      }

    },
    messages: {
      email: {
        required: "Please enter a email address",
        email: "Please enter a vaild email address"
      }
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

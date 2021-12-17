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
        <p class="login-box-msg">Forgot Password</p>

        <form id="forgot-password" method="POST" action="{{ route('forget.password.post') }}">
            @csrf
            <div class="form-group input-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autofocus placeholder="Email">
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
            
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">{{ __('Send Password Reset Link') }}</button>
            </div>
          </div>
        </form>
        <p class="mt-3 mb-1">
        <a href="{{ route('login')}}">Login</a>
      </p>
      </div>
      <!-- /.login-card-body -->
    </div>
</div>
@endsection
@section('js')

<script type="text/javascript">
  $(document).ready(function(){
  $('#forgot-password').validate({
    rules: {
      email: {
        required: true,
        email: true,
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

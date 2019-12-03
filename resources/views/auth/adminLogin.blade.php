@extends('admin.layouts.front_master')
@section('title', config('app.name').' | Login')
@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="javascript:;"><b>{{config('app.name')}}</b></a>
  </div>
      @if (session('error'))
          <div class="alert alert-error" role="alert">
              {{ session('error') }}
          </div>
      @endif
  <div class="login-box-body">
     <p class="login-box-msg admin-login-title">Sign in to Admin</p>
      <!--  <h1 class="admin-loginlogo">
        <img src="{{URL::to('img/logo.png')}}" width="100%" height="40" alt="" />
       </h1> -->
        <form role="form" method="POST" action="{{ route('admin.auth') }}" id="validateForm">
        @csrf
      <div class="form-group has-feedback err_email">
         <input type="email" class="form-control uname" name="email" title="Email is required!" required placeholder="Email" value="{{ old('email') }}" >
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
        @if ($errors->has('email'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('email') }}</strong>
            </span>
        @endif
      <div class="form-group has-feedback err_password">
         <input type="password" class="form-control pword" name="password" placeholder="Password" title="Password is required!" required >
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
        @if ($errors->has('password'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('password') }}</strong>
            </span>
        @endif

        <!-- <div class="form-group has-feedback {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
          <label class=" control-label">{{trans('menu.sidebar.users.form.captcha')}} <span class="asterisk">*</span></label>
          <span class="err_captcha" >
                 {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
              @if ($errors->has('g-recaptcha-response'))
              <span class="help-block">
                  <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
              </span>
              @endif
          </span>
        </div> -->
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember"> Remember Me
            </label>
          </div>
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
      </div>
    </form>

   <!--  <div class="social-auth-links text-center">
    <a class="btn btn-block btn-social btn-facebook btn-flat" href="{{ route('admin.password.request') }}">
    <span class="fa-passwd-reset fa-stack">
      <i class="fa fa-undo fa-stack-2x faundo-admin"></i>
      <i class="fa fa-lock fa-stack-1x"></i>
    </span>
    I forgot my password</a><br>
    </div> -->

  </div>
</div>
@endsection
@section('uniquePageScript')
{!! NoCaptcha::renderJs() !!}
<script>
   jQuery(document).ready(function () {
        // Basic Form
        jQuery("#validateForm").validate({
            highlight: function (element) {
                jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            errorPlacement: function (error, element) {
                error.insertAfter('.err_'+element.attr("name"));
            },
            success: function (label, element) {
                // mark the current input as valid and display OK icon
                //$(element).closest('.form-control').removeClass('has-error');
                label.remove();
            }
        });
    });
   $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
@endsection

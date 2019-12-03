@extends('admin.layouts.front_master')
@section('title', config('app.name').' | Admin Reset Password')
@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="javascript:;"><b>{{config('app.name')}}</b>LARA</a>
  </div>
      @if (session('error'))
          <div class="alert alert-error" role="alert">
              {{ session('error') }}
          </div>
      @endif
  <div class="login-box-body">
     <p class="login-box-msg">{{ __('Admin Reset Password') }}</p>
       <h1 class="logo"><img src="{{URL::to('assets/admin/images/logo.png')}}" width="210" height="40" alt="" /></h1>
        <form role="form" method="POST" action="{{ route('admin.password.request') }}" id="validateForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
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

        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }} has-feedback err_password_confirmation">
         <input type="password" class="form-control pword" name="password_confirmation" placeholder="Confirm Password" title="Confirm Password is required!" required >
        <span class="glyphicon glyphicon-lock form-control-feedback" id='password-confirm'></span>
      </div>
        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('Reset Password') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('uniquePageScript')
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



























<?php /*

@extends('admin.layouts.front_master')
@section('title', config('app.name').' | Admin Reset Password')
@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href="javascript:;"><b>{{config('app.name')}}</b>LARA</a>
  </div>
      @if (session('error'))
          <div class="alert alert-error" role="alert">
              {{ session('error') }}
          </div>
      @endif 
      @if (session('success'))
          <div class="alert alert-success" role="alert">
              {{ session('success') }}
          </div>
      @endif
  <div class="login-box-body">
     <p class="login-box-msg">{{ __('Admin Reset Password') }}</p>
       <h1 class="logo"><img src="{{URL::to('assets/admin/images/logo.png')}}" width="210" height="40" alt="" /></h1>
         <form method="POST" action="{{ route('admin.password.request') }}" id="validateForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
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

      <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }} has-feedback err_password_confirmation">
         <input type="password" class="form-control pword" name="password_confirmation" placeholder="Password" title="Confirm Password is required!" required >
        <span class="glyphicon glyphicon-lock form-control-feedback" id='password-confirm'></span>
      </div>
        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong class="error">{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat"> {{ __('Reset Password') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('uniquePageScript')
<script>
   jQuery(document).ready(function(){
     // Basic Form
     jQuery("#validateForm").validate({
       highlight: function(element) {
         jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
       },
       success: function(label,element) {
         jQuery(element).closest('.form-group').removeClass('has-error');
        label.remove();

       }
     }); 
   });
</script>
@endsection
*/ ?>

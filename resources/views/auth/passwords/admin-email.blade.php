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
        <form role="form" method="POST" action="{{ route('admin.password.email') }}" id="validateForm">
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
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-send-o"></i> {{ __('Send Password Reset Link') }}</button>
        </div>
      </div>
    </form>
    <div class="social-auth-links text-center">
    <a class="btn btn-block btn-social btn-facebook btn-flat" href="{{ route('admin.login') }}">
    <i class="fa fa-sign-in" aria-hidden="true"></i>
    {{ __('Back To Login') }}</a><br>
    </div>
  </div>
</div>
@endsection
@section('uniquePageScript')
<script>
   jQuery(document).ready(function () {
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
</script>
@endsection

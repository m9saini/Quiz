<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="author" content="">
  <title>@yield('title', config('app.name'))</title>
  <meta name="description" content="@yield('description')">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{URL::to('/public/favicon.ico')}}" type="image/png">
  <link rel="stylesheet" href="{{URL::to('/css/admin.css')}}">

</head>
  <body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    @include('admin.page.header')
    @include('admin.page.sidebar')
    <div class="content-wrapper">
       @yield('content')
    </div>
    @include('admin.page.footer')
    @include('admin.page.control-sidebar')
  </div>
      
      @include('admin.page.message')
      <script src="{{URL::to('js/admin.js')}}"></script>
      <script src="{{URL::to('js/bootstrap.min.js')}}"></script>
     
       @yield('uniquePageScript')
       @yield('script') 
  </body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>@yield('title', config('app.name'))</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{URL::to('/public/favicon.ico')}}" type="image/png">
  <link rel="stylesheet" href="{{URL::to('css/admin.css')}}">
</head>
<body class="hold-transition login-page">
  <?php /*  @include('admin.page.preloader') */ ?>
    <section>
        @yield('content')
    </section>
     @include('admin.page.message')
     <script src="{{URL::to('js/admin.js')}}"></script>
     <script src="{{URL::to('js/bootstrap.min.js')}}"></script>
     @yield('uniquePageScript')
     @yield('script')     
</body>
</html>
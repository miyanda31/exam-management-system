<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />

      <!-- Site favicon -->
      <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">

      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
      <!-- CSS -->
      <link rel="stylesheet" type="text/css" href="{{asset('css/core.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('css/icon-font.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('css/fullcalendar.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('css/vue-datetime.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('css/vue-multiselect.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.mCustomScrollbar.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-editable.css')}}">
      <link href="{{asset('css/bootstrap-select.min.css')}}" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="{{asset('css/style.min.css')}}">



      @inertiaHead
  </head>
  <body>
  @routes
  @inertia
  <script src="{{asset('js/jquery.min.js') }}"  ></script>
  <script src="{{asset('js/bootstrap.bundle.min.js') }}" ></script>
  <script src="{{asset('js/jquery.mousewheel.min.js')}}" ></script>
    <script src="{{asset('js/jquery.mCustomScrollbar.concat.min.js') }}" ></script>
    <script src="{{asset('js/sweetalert.min.js') }}" ></script>
  <script src="{{asset('js/moment.min.js') }}" ></script>
  <script src="{{asset('js/vue-multiselect.min.js') }}" ></script>
  <script src="{{asset('js/Chart.min.js') }}" ></script>
  <script src="{{asset('js/vue-chartkick.min.js') }}" ></script>
{{--  <script src="{{asset('js/tagsinput.js') }}" ></script>--}}
  <script src="{{asset('js/script.js')}}" ></script>
  <script src="{{ asset('js/app.js') }}" ></script>
  </body>
</html>

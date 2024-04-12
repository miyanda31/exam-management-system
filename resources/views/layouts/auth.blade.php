<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Eduket - {{$title}}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">


    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/core.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/icon-font.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.min.css')}}">

    @yield('header')

</head>
<body class="{{$page}}">
    @yield('content')

    @yield('footer')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Eduket - Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="token" content="{{isset(Auth::user()->api_token) ? Auth::user()->api_token :''}}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/core.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/icon-font.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.min.css')}}">
    <link href="{{asset('css/vue-multiselect.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/vue-datetime.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/sweetalert.min.css')}}" rel="stylesheet">

    @yield('header')

</head>
<body>

<div id="app">
    <router-view></router-view>
</div>

@auth
    <script>
        window.permissions = @json(\App\Models\Permission::whereHas('role',function ($q){$q->whereId(Auth::user()->role_id);})->get(['value','table_name']))
    </script>
    <script>
        window.user = @json(\App\Models\User::profile()->find(Auth::id()))
    </script>
@endauth

<script src="{{asset('js/core.js')}}"></script>
<script src="{{asset('js/script.min.js')}}"></script>
<script src="{{asset('js/process.js')}}"></script>
<script src="{{asset('js/layout-settings.js')}}"></script>

<script src="{{asset('js/polyfill.js')}}"></script>

<script src="{{asset('js/axios.min.js')}}"></script>
{{--<script src="{{asset('js/vue.min.js')}}"></script>--}}
{{--<script src="{{asset('js/vue-router.min.js')}}"></script>--}}

<script src="{{asset('js/lodash.min.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>

<script src="{{asset('js/vue-chartkick.min.js')}}"></script>
<script src="{{asset('js/vue-multiselect.min.js')}}"></script>
<script src="{{asset('js/torchadmins.js')}}"></script>

@yield('footer')

</body>
</html>

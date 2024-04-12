@extends('layouts.auth')
@php($title = 'Login')
@php($page = 'login-page')
@section('content')
    <div class="login-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="{{route('login')}}">
                    <img src="{{asset('img/logots.png')}}" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="{{asset('images/login-page-img.png')}}" alt="">
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Login</h2>
                        </div>

                        <form class="form" action="{{route('login')}}" method="post">
                            {{csrf_field()}}

                            <div class="input-group custom">
                                <input value="{{old('username')}}" name="username" type="text" class="form-control form-control-lg" placeholder="Username">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            @if ($errors->has('username'))
                                <span class="help-block">
                                        <span class="text-danger"><strong>{{ $errors->first('username') }}</strong></span>
                                    </span>
                            @endif
                            <div class="input-group custom">
                                <input name="password" type="password" placeholder="Password" class="form-control">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <span class="text-danger"><strong>{{ $errors->first('password') }}</strong></span>
                                    </span>
                            @endif
                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input {{ old('remember') ? 'checked' : '' }} name="remember" type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Remember</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
                                    </div>
                                    <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373"></div>
                                    <div class="input-group mb-0">
                                        <a class="btn btn-outline-primary btn-lg btn-block" href="{{ route('register') }}">Register</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


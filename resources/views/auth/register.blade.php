@extends('layouts.auth')
@php($title = 'Register')
@php($page = 'register-page')
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
                    <img src="{{asset('images/forgot-password.png')}}" alt="">
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Registration</h2>
                        </div>

                        <form class="form" action="{{route('register')}}" method="post">
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

                            <div class="input-group custom">
                                <input name="password_confirmation" type="password" placeholder="Confirm Password" class="form-control">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                        <span class="text-danger"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                    </span>
                            @endif
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="d-flex">
                                    <div class="custom-control custom-radio mb-5 mr-20">
                                        <input checked value="Male" type="radio" id="customRadio4" name="gender" class="custom-control-input">
                                        <label class="custom-control-label weight-400" for="customRadio4">Male</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-5">
                                        <input value="Female" type="radio" id="customRadio5" name="gender" class="custom-control-input">
                                        <label class="custom-control-label weight-400" for="customRadio5">Female</label>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group custom">
                                <input name="code" type="text" placeholder="Registration Code" class="form-control">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-key"></i></span>
                                </div>
                            </div>
                            @if ($errors->has('code'))
                                <span class="help-block">
                                        <span class="text-danger"><strong>{{ $errors->first('code') }}</strong></span>
                                    </span>
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Register</button>
                                    </div>
                                    <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373"></div>
                                    <div class="input-group mb-0">
                                        <a class="btn btn-outline-primary btn-lg btn-block" href="{{ route('login') }}">Login</a>
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


@extends('layout.default')
@section('content')
    @if (!Auth::check())
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-default register">
                        <div class="panel-body">
                            <h3>{{ trans('user.login') }}</h3>
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('auth/login') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{ trans('user.loginEmail') }}</label>
                                    <div class="col-md-8">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">{{ trans('user.loginPassword') }}</label>
                                    <div class="col-md-8">
                                    <input type="password" class="form-control" name="password">
                                </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                    <div class="remember-me">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember"> {{ trans('user.loginRememberMe') }}
                                            </label>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="form-group login-btn center">
                                    <button type="submit" class="btn">
                                        {{ trans('user.login') }}
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 login-links text-left">
                                        <a href="{{ url('password/email') }}">
                                            {{ trans('user.forgotPassword') }}
                                        </a>
                                    </div>
                                    <div class="col-xs-6 login-links text-right">
                                        <a href="{{ url('auth/register') }}">
                                            {{ trans('user.register') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="social-login row">
                                {{--<div class="social-header">
                                    {{ trans('user.loginBy') }}
                                </div>--}}
                                <div class="social-buttons row">
                                    <div class="col-md-12 align-center">
                                        <a href="{{ url('/login/facebook') }}" class="social-facebook-button"><img src="{{ asset('/images/fb.png') }}"></a>
                                    </div>
                                </div>
                            </div>
                                <div class="conditions">
                                    {{ trans('user.loginText1') }}
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop
@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="section">
    <div class="row">
        <div class="columns four card">&nbsp;</div>
        <div class="columns four">
            <div class="">
                <form class="" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="align-left">E-Mail Address</label>

                        <div class="">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <span class="error">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="align-left">Password</label>

                        <div class="">
                            <input id="password" type="password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="error">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="align-left">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="align-right">
                        <button type="submit" class="">
                            <i class=""></i> Login
                        </button>
                    </div>
                    <br>
                    <div>
                            <a class="" href="https://collivery.net/forgot-password">Forgot Your Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

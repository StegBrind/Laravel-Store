@extends('layouts.app')

@section('title')
    <title>{{ __('auth.reset_password1') }}</title>
@endsection
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
@section('content')
    <div class="container" style="padding-top: 30px;">
        <div class="col-sm-11">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        <h3><i class="fa fa-lock fa-4x"></i></h3>
                        <p>{{ __('auth.enter_new') }}</p>
                        <div class="panel-body">
                            @if($errors->any())
                                <div name="errors" style="color: red">
                                    @foreach($errors->all() as $error)
                                        <label>{{ $error }}</label><br>
                                    @endforeach
                                </div>
                            @endif
                            <form id="reset_form" role="form" autocomplete="off" class="form" action="{{ url('forgot_password/resetting') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control-sm" style="width: 300px" id="login" name="login" placeholder="{{ __('auth.login') }}" value="{{ old('login') }}">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control-sm" style="width: 300px" id="password" name="password" placeholder="{{ __('auth.password') }}" value="{{ old('password') }}">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control-sm" style="width: 300px" id="password_confirmation" name="password_confirmation" placeholder="{{ __('auth.repeat_password') }}" value="{{ old('password_confirmation') }}">
                                </div>
                                <input type="hidden" id="token" name="token" value="{{ $token }}">
                                <input type="hidden" id="email" name="email" value="{{ $email }}">
                                <div class="form-group">
                                    <input name="recover-submit" class="btn btn-lg btn-primary" value="{{ __('auth.change_credentials') }}" type="submit">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

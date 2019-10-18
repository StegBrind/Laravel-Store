@extends('layouts.app')

@section('title')
    <title>Регистрация</title>
@endsection

@section('content')
    <center><h1><br><p>Регистрация</p></h1></center><hr>
    <center>
    <h4><p>{{ __('auth.fill_in_fields') }}</p></h4>
        <form action="registration" method="POST" style="width: 300px;">
            @csrf
            <div class="form-group row">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ __('auth.first_name') }}</span>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" aria-describedby="basic-addon1">
                    @error('name')
                        <label style="color: red">{{ $message }}</label>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ __('auth.last_name') }}</span>
                    </div>
                    <input type="text" name="surname" value="{{ old('surname') }}" class="form-control" aria-describedby="basic-addon1">
                    @error('surname')
                        <label style="color: red">{{ $message }}</label>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ __('auth.login') }}</span>
                    </div>
                    <input type="text" name="login" value="{{ old('login') }}" class="form-control" aria-describedby="basic-addon1">
                    @error('login')
                        <label style="color: red">{{ $message }}</label>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ __('auth.password') }}</span>
                    </div>
                    <input type="password" name="password" value="{{ old('password') }}" class="form-control" aria-describedby="basic-addon1">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ __('auth.repeat_password') }}</span>
                        </div>
                        <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control" aria-describedby="basic-addon1">
                    </div>
                    @error('password')
                        <label style="color: red">{{ $message }}</label>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ __('auth.email') }}</span>
                    </div>
                    <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control" aria-describedby="basic-addon1">
                </div>
                @error('email')
                    <label class="label-danger" for="email" style="color: red">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group row">
                <div class="offset-sm-3">
                    <button type="submit" class="btn btn-primary">{{ __('auth.sign_up2') }}</button>
                </div>
            </div>
        </form>
    </center>
    <a style="margin-left: 20%" href="/"><--{{ __('general.back_to_main_page') }}</a>
@endsection

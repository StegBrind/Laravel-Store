<div>
    <vue-headful title="{{ __('auth.sign_in') }}"/>
    <br>
    <form action="/api/login" method="POST" style="width: 400px; margin: auto">
        @csrf
        @error('auth_failed')<label class="form-text" style="color: red; text-align: center">{{ $message }}</label>@enderror
            <label for="inputLogin3">{{ __('auth.login') }}</label>
            <input type="text" class="form-control" name="login" id="inputEmail3" value="{{ old('login') }}" placeholder="{{ __('auth.login') }}">
            @error('login')<small class="form-text" style="color: red">{{ $message }}</small>@enderror
        <div class="form-group">
            <label for="inputPassword3">{{ __('auth.password') }}</label>
            <input type="password" class="form-control" name="password" id="inputPassword3" value="{{ old('password') }}" placeholder="{{ __('auth.password') }}">
            @error('password')<small style="color: red">{{ $message }}</small>@enderror
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember">
                <label class="form-check-label" for="exampleCheck1">{{ __('auth.remember_me') }}</label>
            <router-link style="float: right" to="{{ url('forgot_password') }}">{{ __('auth.question_reset') }}</router-link>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('auth.sign_in') }}</button>
    </form>
    <router-link style="margin-left: 20%" to="/"><--{{ __('general.back_to_main_page') }}</router-link>
</div>
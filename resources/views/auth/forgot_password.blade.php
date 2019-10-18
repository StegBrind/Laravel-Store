@extends('layouts.app')

@section('title')
    <title>{{ __('auth.reset_password1') }}</title>
@endsection
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
@section('content')
    <div class="container" style="padding-top: 30px;">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        <h3><i class="fa fa-lock fa-4x"></i></h3>
                        <h2 class="text-center">{{ __('auth.question_reset') }}</h2>
                        <p>{!! __('auth.forgot_password_sentence1') !!}</p>
                        <div class="panel-body">
                            @error('email')<small style="color: red">{{ $message }}</small>@enderror
                            <label id="status" name="status" style="color: dodgerblue">{{ \Illuminate\Support\Facades\Session::get('status') }}</label>
                            <form id="reset_form" role="form" autocomplete="off" class="form" method="post">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control-sm" style="width: 300px" id="email" name="email" placeholder="{{ __('auth.email') }}" value="{{ old('email') }}">
                                    <small class="form-control-plaintext" style="color: dimgrey">{!! __('auth.forgot_password_sentence2') !!}</small>
                                </div>
                                <div class="form-group">
                                    <input name="recover-submit" class="btn btn-lg btn-primary" value="{{ __('auth.reset_password2') }}" type="submit">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a style="margin-left: 20%" href="/"><--{{ __('general.back_to_main_page') }}</a>
@endsection

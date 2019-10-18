@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('email.verify_email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('email.fresh_verification_text') }}
                        </div>
                    @endif

                    {{ __('email.verify_text1') }}
                    {{ __('email.verify_text2') }}, <a href="{{ route('verification.resend') }}">{{ __('email.fresh_verification_link') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

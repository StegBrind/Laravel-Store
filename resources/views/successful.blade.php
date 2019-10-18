@extends('layouts.app')

@section('content')
<div style="text-align: center"><br>
    <h5>
        @if(session('success_message'))
            {!! session('success_message') !!}
        @else
            {!! __('general.error_success') . ' <a href="/">' . __('general.back_to_main_page') . '</a>' !!}
        @endif
    </h5>
</div>
<hr>
@endsection

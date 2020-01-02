@extends('layouts.app')

@section('title')
    <title>Переписка с {{ $companion_name }}</title>
@endsection

@section('content')
    <h2 style="text-align: center; padding-top: 15px">Переписка с {{ $companion_name }}</h2>
    <hr>
    <center>
        <conversation-component
                history-conversation-data="{{ $messages }}"
                companion_ids="{{ $user_ids }}"
                companion_name="{{ $companion_name }}"
                token="{{ $token }}"></conversation-component>
    </center>
    <p>
        <a href="/"><--- {{ __('general.back_to_main_page') }}</a>
    </p>
@endsection
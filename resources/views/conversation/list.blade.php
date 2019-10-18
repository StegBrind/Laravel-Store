@extends('layouts.app')

@section('title')
    <title>Мои сообщения</title>
@endsection

@section('content')
    <h2 style="text-align: center; padding-top: 15px">Мои переписки</h2>
    <hr>
    <ul>
        {!! $conversation_list !!}
    </ul>
    <p>
        <a href="/"><--- {{ __('general.back_to_main_page') }}</a>
    </p>
@endsection

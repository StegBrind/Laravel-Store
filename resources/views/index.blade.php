@extends('layouts.app')

@section('title')
    <title>Shop</title>
@endsection

@section('content')
    <div id="header" style="width: 99vw;">
        <p style="margin: 4px">{{ __('messages.Hello') }},
        @auth
            {{ \Illuminate\Support\Facades\Auth::user()['name_surname'] }}</p>
            <form action="/" method="POST" style="display: inline-block">
                @csrf
                <input type="submit" name="LogOut" class="btn btn-info" style="margin-left: 5px" value="{{ __('auth.logout') }}">
            </form>
            <a href="{{ url('conversation/list') }}"><button class="btn btn-info">Мои сообщения</button></a>
        @else
            {{ __('messages.anonym') }}</p> <a href="login">{{ __('auth.sign_in') }}</a> <a href="registration">{{ __('auth.sign_up1') }}</a>
        @endauth
        <form action="/" method="POST" style="float: right; display: inline-block">
            @csrf
            <input type="submit" name="submit" class="btn btn-info" style="margin-right: 5px" value="RU">
            <input type="submit" name="submit" class="btn btn-info" style="margin-right: 5px" value="EN">
        </form>
    </div>
    <hr style="margin-top: 25px">
    <p>
        <div style="text-align: center">
            <h1>{{ __('categories.categories') }}</h1>
        </div>
    </p>
    <div style="text-align: center; margin: 0 auto; width: 300px">
        <h3>
            @foreach(\App\Category::all() as $category)
                <a href="category/{{ $category->id }}">{{ $category->name }}</a>
            @endforeach
        </h3>
    </div>
@endsection

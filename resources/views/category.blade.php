@extends('layouts.app')

@section('title')
    <title>{{ $category_name }}</title>
@endsection

@section('content')

    <product-component loading-bar-url="{{ asset('img/loading_bar.gif') }}"
                       category-id="{{ $category_id }}"
                       translate-search-word="{{ __('general.search_by_name') }}"
                       translate-search-word-button="{{ __('general.search') }}"
    ></product-component>

    <p>
        <a href="/"><--- {{ __('general.back_to_main_page') }}</a>
    </p>

@endsection
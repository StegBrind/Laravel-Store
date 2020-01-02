<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Global site tag (gtag.js) - Google Analytics -->
{{--    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-152805491-1"></script>--}}
{{--    <script>--}}
{{--        window.dataLayer = window.dataLayer || [];--}}
{{--        function gtag(){dataLayer.push(arguments);}--}}
{{--        gtag('js', new Date());--}}
{{--        gtag('config', 'UA-152805491-1');--}}
{{--    </script>--}}
    @yield('title')
</head>

<body>
    <div id="vue-app">
        @yield('content')
    </div>
</body>

<footer>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('footer-scripts')
</footer>

</html>
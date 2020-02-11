<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta token name="csrf-token">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    {{--    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-152805491-1"></script>--}}
    {{--    <script>--}}
    {{--        window.dataLayer = window.dataLayer || [];--}}
    {{--        function gtag(){dataLayer.push(arguments);}--}}
    {{--        gtag('js', new Date());--}}
    {{--        gtag('config', 'UA-152805491-1');--}}
    {{--    </script>--}}
{{--    @yield('title')--}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div id="app"></div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
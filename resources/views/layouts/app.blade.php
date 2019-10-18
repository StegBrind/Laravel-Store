<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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


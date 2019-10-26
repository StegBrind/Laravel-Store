<!DOCTYPE html>
<html lang="en">
<title>{{ $title }}</title>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('packages/sleepingowl/default/css/admin-app.css') }}">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
@php
    if (Cookie::get('menu-state') == 'close')
        $menu_class = 'sidebar-collapse';
    else
        $menu_class = 'sidebar-open';
@endphp
<body class="hold-transition sidebar-mini {{ $menu_class }}">
    <div class="wrapper" id="vue-app">
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <ul class="nav navbar-nav" style="padding: 0; margin: 0">
                <li class="nav-item">
                    <a data-widget="pushmenu" class="nav-link">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            @include(AdminTemplate::getViewPath('_partials.navigation'))
        </aside>
        <div class="content-wrapper">
            <div class="content body">
                {!! $content !!}
            </div>
        </div>
    </div>
    @include(AdminTemplate::getViewPath('helper.autoupdate'))
</body>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('packages/sleepingowl/default/js/admin-app.js') }}"></script>
</html>

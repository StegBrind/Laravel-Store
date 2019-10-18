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
<body class="skin-blue sidebar-mini">
    <div id="vue-app">
        <header class="main-header">
            @include(AdminTemplate::getViewPath('_partials.header'))
        </header>

        <aside class="main-sidebar">
            @include(AdminTemplate::getViewPath('_partials.navigation'))
        </aside>
        <div class="content-wrapper">
            <div class="content body">
                {!! $content !!}
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('packages/sleepingowl/default/js/admin-app.js') }}"></script>
</html>

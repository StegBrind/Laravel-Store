<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #13232f;
        height: 100vh;
    }
    #login .container #login-row #login-column #login-box {
        margin-top: 120px;
        max-width: 450px;

        border: 1px solid #9C9C9C;
        background-color: #13232f;

    }
    #login .container #login-row #login-column #login-box #login-form {
        padding: 20px;
        color: #2f4a2d;
    }
    #login .container #login-row #login-column #login-box #login-form #register-link {
        margin-top: -85px;
        color: #2f4a2d;
    }
</style>

<title>Admin Login</title>

<div id="login">
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">
                    <form id="login-form" class="form" action="admin" method="post">
                        @csrf
                        <h3 class="text-center" style="color: #00a65a">Админ Вход</h3>

                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                <h6 class="fa-align-left" style="color: darkred">
                                    <li>{{ $error }}</li>
                                </h6>
                                @endforeach
                            @endif

                        <div class="form-group">
                            <label for="email" style="color: #1b6d85">Эл. почта:</label><br>
                            <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="form-group">
                            <label for="password" style="color: #1b6d85">Пароль:</label><br>
                            <input type="password" name="password" id="password" class="form-control" value="{{ old('password') }}">
                        </div>
                        <div class="text-center form-group">
                            <input type="submit" name="submit" class="btn btn-info btn-md" style="color: #1b6d85; background-color: #1d1d1d" value="Войти">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Hello Amaze UI</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <link rel="stylesheet" href="/assets/dep/amazeui/dist/css/amazeui.min.css">
</head>
<body>
<header style="width: 100%; border-bottom: solid 1px #999; padding: 20px; text-align: center; margin-bottom: 20px">
    <h1>Workorder Management</h1>
    <p>Please Login</p>
</header>
<div class="am-container">

    <div class="am-u-lg-8 am-u-lg-centered">

        @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                <div class="am-alert am-alert-danger" data-am-alert>
                    <button type="button" class="am-close">&times;</button>
                    <p>{{ $error }}</p>
                </div>
            @endforeach
        @endif

        @if(Session::get('register.success'))
            <div class="am-alert am-alert-success" data-am-alert>
                <button type="button" class="am-close">&times;</button>
                <p>注册成功！赶快登录吧！</p>
            </div>
        @endif

        <form class="am-form" method="post">
            {{ csrf_field() }}
            <div class="am-form-group">
                <label for="login-email">邮件</label>
                <input type="email" class="" id="login-email" name="email" placeholder="请输入您电子邮件" required>
            </div>

            <div class="am-form-group">
                <label for="login-password">密码</label>
                <input type="password" class="" id="login-password" name="password" placeholder="请输入您的密码" required>
            </div>

            <div class="am-checkbox">
                <label>
                    <input type="checkbox" name="remember" value="1"> Remember Me
                </label>
            </div>

            <button type="submit" class="am-btn am-btn-primary am-btn-lg">Sign in</button>
            <a class="am-btn am-btn-default am-btn-lg" href="{{ route('host.register') }}">Register</a>
        </form>
    </div>
</div>


<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/dep/amazeui/dist/js/amazeui.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    @extends('HJGL.admin.layouts.app')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>环境监测管理系统 | 管理后台</title>
    <link href="{{ URL::asset('img/favor.ico') }}" rel="shortcut icon" type="image/x-icon"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ URL::asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ URL::asset('plugins/iCheck/square/blue.css') }}">
    <!-- common -->
    <link rel="stylesheet" href="{{ URL::asset('css/common.css') }}">
    <!--login-->
    <link rel="stylesheet" href="{{ URL::asset('css/login.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            background: url("{{URL::asset('/img/web_login_bg.jpg')}}") no-repeat center;
            background-size: cover;
        }

        #darkbannerwrap {
            background: url("{{URL::asset('/img/aiwrap.png')}}");
            width: 18px;
            height: 10px;
            margin: 0 0 20px -58px;
            position: relative;
        }
    </style>
</head>
<body>

<div class="login">
    <div class="message font-size-22 text-white" >环境监测管理系统-后台登录</div>
    <div id="darkbannerwrap"></div>

    <form action="" method="post" onsubmit="return checkValid()">
        {{csrf_field()}}
        <input id="phone" name="phone" placeholder="输入账号" required="" type="text" >
        <hr class="hr15">
        <input id="password" name="password_chache" placeholder="密码" required="" type="password">
        <input  name="password" type="hidden" id="password_pass">
        <hr class="hr15">
        {{--<div  class="loginbutton" onclick="checkValid()">登&nbsp;&nbsp;录</div>--}}
       <input value="登录" style="width:100%;" type="submit">
        <hr class="hr20">
    </form>
    @if($msg)
        <div id="error_msg" class="text-danger">
            *{{$msg}}
        </div>
    @endif
</div>


</body>
</html>
<!-- jQuery 3 -->
<script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ URL::asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ URL::asset('plugins/iCheck/icheck.min.js') }}"></script>
{{--aui--}}
<script type="text/javascript" src="{{ URL::asset('/js/aui/aui-dialog.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/aui/aui-toast.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/aui/aui-popup.js') }}"></script>
<!-- common -->
<script src="{{ URL::asset('js/common.js') }}"></script>
<!-- md5 -->
<script src="{{ URL::asset('js/md5.js') }}"></script>
<script>
    //进行表单校验
    function checkValid() {
        var phone = $("#phone").val();
        var password = $("#password").val();
        //对密码进行md5加密
        $("#password_pass").val(hex_md5(password));

    }

</script>
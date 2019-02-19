@extends('HJGL.user.layouts.app')
<style type="text/css">
    .input1{
        border: solid 1px #555555;
        height:30px;
        margin-bottom: 30px;
    }
    .s1{
        width:80%;
        height:35px;
        border: solid 1px #555555;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    .s2{
        margin-left: 10px;
        border: solid 1px #555555;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    .f1{
        margin-left: 10%;
        margin-top: 15%;
        font-size:30px;
    }
    .f2{
        margin-left: 10%;
        margin-top: 15px;
    }
    .from1{
        width:100%;
        margin-left: 10%;
        margin-top: 15px;
    }
</style>
@section('content')
    <div class="hui-header">
        <h1>绑定手机号</h1>
    </div>
    <div class="hui-wrap" style="width:100%;">
            <div class="f1">手机绑定</div>
            <div class="f2">请输入您的手机号码，</div>
            <div class="f2">绑定您的环境检测帐号</div>
            <form class="from1">
                <input id="phone" name="phone"  class="input1" placeholder="请输入手机号码" style="width:80%;"><br/>
                <div style="width:80%;">
                    <input class="input1" placeholder="验证码" style="width:45%;float:left">
                    <div class="s2" style="float:right;line-height: 30px;width: 35%;text-align: center;cursor: pointer;" id="getVcode"
                         onclick="sendMsg()">获取短信验证码
                    </div>
                </div>
                <div class="s2" style="float:left;width:30%;display: none;line-height: 30px;text-align: center;background: #9a9898 !important" id="cannotgetVcode">获取短信验证码
                </div>
                <input class="s1" type="submit" value="确定">
            </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var countdown = 60;
        function sendMsg(){
            alert('1232132');
        }
        /*
        * 倒计时
        * */
        function settime() {
            if (countdown == 0) {
                $("#getVcode").css('display','block');
                $("#cannotgetVcode").css('display','none');
                countdown = 60;
            } else {
                $("#getVcode").css('display','none');
                $("#cannotgetVcode").css('display','block');
                $("#cannotgetVcode").html("重新发送(" + countdown + ")");
                countdown--;
                setTimeout(function() {
                    settime()
                },1000)
            }
        }
        //进行表单校验
        function sendMsg() {
            consoledebug.log("sendMsg");
            layer.msg('发送中...', {icon: 1, time: 1000});
            settime();
            // var id = $("#id").val();
            // //手机号是否为空
            // if (judgeIsAnyNullStr(id)) {
            //     layer.msg('账号不能为空！', {icon: 2, time: 1000});
            //     return false;
            // }
            // var  phone= $("#phone").val();
            // if (phone == null || phone.length == 0 || judgeIsNullStr(phone)) {
            //     layer.msg('新手机号不能为空！', {icon: 2, time: 1000});
            //     return false;
            // }
            {{--sendMassage('{{ URL::asset('admin/admin/validateNewPhone')}}', {phone: phone},--}}
                {{--function (res) {--}}
                    {{--consoledebug.log("发送验证码接口的返回为", res);--}}
                    {{--toast_hide();--}}
                    {{--if (res.result) {--}}
                        {{--layer.msg('发送中...', {icon: 1, time: 1000});--}}
                        {{--settime();--}}
                    {{--}--}}
                    {{--else{--}}
                        {{--layer.msg(res.message, {icon: 2, time: 1000});--}}
                        {{--return false;--}}
                    {{--}--}}
                {{--})--}}
        }
    </script>
@endsection
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
        margin: 10%;
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
                <input id="hj_phone" name="hj_phone" class="input1" placeholder="请输入手机号码" style="width:80%;background-color:transparent;"><br/>
                <div style="width:80%;">
                    <input class="input1" id="sm_validate" name="sm_validate"  style="width:45%;background-color:transparent;" placeholder="验证码" style="float:left">
                    <div class="s2" style="float:right;line-height: 30px;width: 110px;text-align: center;cursor: pointer;" id="getVcode"
                         onclick="sendMsg()">获取短信验证码
                    </div>
                    <div class="s2" style="float:right;width:110px;display: none;line-height: 30px;text-align: center;background: #9a9898 !important" id="cannotgetVcode">获取短信验证码
                    </div>
                </div>
                <span type="button" onclick="submit()" class="s1 hui-button" style="background-color:transparent;border: solid 1px #555555;">确定</span>
            </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var countdown = 60;
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
                    settime();
                },1000)
            }
        }
        //进行表单校验
        function sendMsg() {
            //手机号是否为空
            var  hj_phone= $("#hj_phone").val();
            if (hj_phone == null || hj_phone.length == 0 || judgeIsNullStr(hj_phone)) {
                hui.iconToast('手机号不能为空', 'warn');
                return false;
            }
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('api/validateNewPhone')}}",
                dataType: 'json',
                data: {
                    'hj_phone' : hj_phone,
                },
                success: function (data) {
                    console.log(data);
                    if (data.code == 200) {
                        hui.iconToast('发送中...', 'warn');
                        settime();
                    } else {
                        hui.iconToast(data.message, 'warn');
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }

        function submit(){
            //手机号是否为空
            var  hj_phone= $("#hj_phone").val();
            if (hj_phone == null || hj_phone.length == 0 || judgeIsNullStr(hj_phone)) {
                hui.iconToast('手机号不能为空', 'warn');
                return false;
            }
            //
            var  sm_validate= $("#sm_validate").val();
            if (sm_validate == null || sm_validate.length == 0 || judgeIsNullStr(sm_validate)) {
                hui.iconToast('验证码不能为空', 'warn');
                return false;
            }
            $.ajax({
                type: 'post',
                url: "{{URL::asset('api/perfect_phone_save')}}",
                dataType: 'json',
                data: {
                    'hj_phone' : hj_phone,
                    'sm_validate' : sm_validate,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    console.log(data);
                    if (data.code == 200) {
                        window.location="{{URL::asset('api/perfect_info')}}";
                    } else {
                        hui.iconToast(data.message, 'warn');
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }
    </script>
@endsection
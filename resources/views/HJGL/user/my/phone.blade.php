@extends('HJGL.user.layouts.app')
<style type="text/css">
    .input1{
        border: solid 0px #555555;
        height:30px;
        margin-bottom: 30px;
    }
    .s2{
        /*border: solid 1px #555555;*/
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
</style>
@section('content')
    <div class="hui-header">
        <div id="hui-back"></div>
        <h1>更换手机号</h1>
    </div>
    <div class="hui-wrap">
        <div style="margin:20px 10px; margin-bottom:15px;" class="hui-form" id="form1">
            <div class="hui-form-items">
                <div class="hui-form-items-title">手机号</div>
                <input class="hui-input hui-input-clear" placeholder="请输入手机号" checkType="string" checkData="11,11" checkMsg="手机号应为11个数字" />
            </div>
            <div class="hui-form-items">
                <div class="hui-form-items-title">图片验证码</div>
                <input class="hui-input hui-pwd-eye" placeholder="请输入图片验证码" checkType="string" id="pwd" checkData="6,20" checkMsg="密码应为6-20个字符" />
                <div style="height:22px;">
                    <img src="" width="100px" />
                </div>
            </div>
            <div class="hui-form-items">
                <div class="hui-form-items-title">验证码</div>
                <input type="number" placeholder="请输入短信验证码" class="hui-input" name="yzm" value="" checkType="reg" checkData="^\d{4,4}$" checkMsg="验证码应该为4个数字" />
                <div style="width:240px;height:40px;">
                    <div class="hui-primary s2" style="float:right;line-height: 30px;width: 100px;text-align: center;cursor: pointer;" id="getVcode"
                         onclick="sendMsg()">获取验证码
                    </div>
                    <div class="s2" style="float:right;width:100px;display: none;line-height: 30px;text-align: center;background: #9a9898 !important" id="cannotgetVcode">获取短信验证码
                    </div>
                </div>
            </div>
        </div>
        <div style="padding:15px 8px;">
            <a href="javascript:hui.back();" type="button" class="hui-button hui-primary hui-wrap" id="submitBtn" style="margin-top:10%;">确定</a>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function s1() {
            //验证
            var res = huiFormCheck('#form1');
            //提交
            if(res){hui.iconToast('验证通过！');}
        }
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
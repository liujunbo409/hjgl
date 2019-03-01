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
        <h1>验证手机号</h1>
    </div>
    <div class="hui-wrap">
        <div style="margin:20px 10px; margin-bottom:15px;" class="hui-form" id="form1">
            <div class="hui-form-items">
                <input type="hidden" name="openid" id="openid" value="{{isset($user_info->openid)?$user_info->openid : '' }}">
                <div class="hui-form-items-title">手机号</div>
                <input class="hui-input hui-input-clear" id="phone" name="phone" value="{{isset($user_info->hj_phone)?$user_info->hj_phone: '' }}" checkType="string" checkData="11,11" checkMsg="手机号应为11个数字" />
            </div>
            <div class="hui-form-items">
                <div class="hui-form-items-title">图片验证码</div>
                <input class="hui-input hui-pwd-eye" placeholder="请输入图片验证码" checkType="string" id="img_code" checkData="6,20" checkMsg="图片验证码应为6-20个字符" />
                <div style="height:22px;">
                    <img src="" width="100px" />
                </div>
            </div>
            <div class="hui-form-items">
                <div class="hui-form-items-title">短信验证码</div>
                <input type="number" placeholder="请输入短信验证码" class="hui-input" id="sm_validate" name="sm_validate" value="" checkType="reg" checkData="^\d{4,4}$" checkMsg="短信验证码应该为4个数字" />
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
            <span type="button" onclick="submit()" class="hui-button hui-primary hui-wrap" id="submitBtn" style="margin-top:10%;">确定</span>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function submit() {
            //验证
            var res = huiFormCheck('#form1');
            //提交
            if(res){
                var  phone= $("#phone").val();
                var  img_code= $("#img_code").val();
                var  sm_validate= $("#sm_validate").val();

                $.ajax({
                    type: 'post',
                    url: "{{URL::asset('api/QRcode/orderPhoneSave')}}",
                    dataType: 'json',
                    data: {
                        'phone' : phone,
                        'img_code' : img_code,
                        'sm_validate' : sm_validate,
                        '_token': '{{csrf_token()}}'
                    },
                    success: function (data) {
                        console.log(data);
                        if (data.code == 200) {
                            hui.iconToast(data.message, 'warn');
                            settime();
                            window.location="{{URL::asset('api/QRcode/paying')}}";
                        } else {
                            hui.iconToast(data.message, 'warn');
                        }
                    },
                    error: function (data) {
                        console.log(data)
                    }
                });
            }
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
            //是否为空
            var  phone= $("#phone").val();
            if (phone == null || phone.length == 0 || judgeIsNullStr(phone)) {
                hui.iconToast('手机号不能为空', 'warn');
                return false;
            }
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('api/validateNewPhone')}}",
                dataType: 'json',
                data: {
                    'hj_phone' : phone,
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
    </script>
@endsection
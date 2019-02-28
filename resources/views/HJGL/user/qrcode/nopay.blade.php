@extends('HJGL.user.layouts.app')
<style type="text/css">
</style>
@section('content')
    <div class="hui-header">
        <h1>扫一扫</h1>
    </div>
    <div class="hui-wrap" style="width:100%;">
        <div style="margin:20px 10px; margin-bottom:15px;" class="hui-form" id="form1">
            <div class="hui-form-items">
                <div class="hui-form-items-title">用户名</div>
                <input type="text" class="hui-input hui-input-clear" placeholder="如：hcoder" checkType="string" checkData="5,20" checkMsg="用户名应为5-20个字符" />
            </div>
            <div class="hui-form-items">
                <div class="hui-form-items-title">登录密码</div>
                <input type="password" class="hui-input hui-pwd-eye" placeholder="登录密码" checkType="string" id="pwd" checkData="6,20" checkMsg="密码应为6-20个字符" />
            </div>
            <div class="hui-form-items">
                <div class="hui-form-items-title">确认密码</div>
                <input type="password" class="hui-input hui-pwd-eye" placeholder="确认密码" checkType="sameWithId" checkData="pwd" checkMsg="两次密码不一致"  />
            </div>
            <div class="hui-form-items">
                <div class="hui-form-items-title">验证码</div>
                <input type="number" class="hui-input" name="yzm" value="" checkType="reg" checkData="^\d{4,4}$" checkMsg="验证码应该为4个数字" />
                <div style="width:120px;">
                    <img src="../img/yzm.png" width="100%" />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php use Illuminate\Support\Facades\Config;
            use EasyWeChat\Factory; $config = Config::get("wechat.official_account.default");
            $app = Factory::officialAccount($config); echo $app->jssdk->buildConfig(array('scanQRCode'), false) ?>);
        {{--wx.config({--}}
            {{--debug: true,--}}
            {{--appId: "{{$data['appId']}}",--}}
            {{--timestamp: "{{$data['timestamp']}}",--}}
            {{--nonceStr: "{{$data['nonceStr']}}",--}}
            {{--signature: "{{$data['signature']}}",--}}
            {{--jsApiList: "{{$data['jsApiList']}}"--}}
        {{--});--}}
        // console.log(wx.config);
        wx.ready(function () {

        });
        wx.error(function(res){
            console.log(res);
        });
        function scanQRCode(){
            wx.scanQRCode({
                needResult: 1,
                scanType: ["qrCode", "barCode"],
                success: function (res) {
                    // console.log(res);
                    window.location=res.resultStr;
                    // alert(JSON.stringify(res));
                    var result = res.resultStr;
                },
                fail: function (res) {
                    // console.log(res);
                    // alert(JSON.stringify(res));
                }
            })
        }
    </script>
@endsection
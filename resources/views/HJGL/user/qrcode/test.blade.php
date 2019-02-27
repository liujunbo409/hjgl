@extends('HJGL.user.layouts.app')
<style type="text/css">
</style>
@section('content')
    <div class="hui-header">
        <h1>扫一扫</h1>
    </div>
    <div class="hui-wrap" style="width:100%;">

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
    <script type="text/javascript">

    </script>
@endsection
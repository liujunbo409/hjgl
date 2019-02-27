@extends('HJGL.user.layouts.app')
<style type="text/css">
</style>
@section('content')
    <div class="hui-header">
        <h1>个人信息</h1>
    </div>
    <div class="hui-wrap" style="width:100%;">
            123
    </div>
@endsection

@section('script')
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo $app->jssdk->buildConfig(array('onMenuShareQQ', 'onMenuShareWeibo'), true) ?>);
        {{--wx.config({--}}
            {{--debug: true,--}}
            {{--appId: "{{$data['appId']}}",--}}
            {{--timestamp: "{{$data['timestamp']}}",--}}
            {{--nonceStr: "{{$data['nonceStr']}}",--}}
            {{--signature: "{{$data['signature']}}",--}}
            {{--jsApiList: "{{$data['jsApiList']}}"--}}
        {{--});--}}
        console.log(wx.config);
        {{--wx.ready(function () {--}}
            {{--wx.checkJsApi({--}}
                {{--jsApiList: ['checkJsApi','openLocation'],--}}
                {{--success: function (res) {}--}}
            {{--});--}}
        {{--});--}}
        {{--wx.error(function(res){--}}
            {{--console.log(res);--}}
        {{--});--}}
    </script>
    <script type="text/javascript">

    </script>
@endsection
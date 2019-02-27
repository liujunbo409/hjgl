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
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config({
            debug: true,
            appId: 'wx3cf0f39249eb0e60',
            timestamp: 1430009304,
            nonceStr: 'qey94m021ik',
            signature: '4F76593A4245644FAE4E1BC940F6422A0C3EC03E',
            jsApiList: ['updateAppMessageShareData', 'updateTimelineShareData']
        });
        console.log(wx.config);
    </script>
    <script type="text/javascript">

    </script>
@endsection
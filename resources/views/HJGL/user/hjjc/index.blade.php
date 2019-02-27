@extends('HJGL.user.layouts.app')
<style type="text/css">
    .s2{
        width:20%;
        border: solid 1px black;
        /*border: solid 1px #00b3ee;*/
        /*color: #00b3ee;*/
        text-align:center;
        float:left;
        margin-right:4%;
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
    }
</style>
@section('content')
    <div class="hui-header">
        <h1>环境检测</h1>
        <div id="hui-header-menu"></div>
    </div>
    <div class="hui-wrap">
        <div style="float:left;margin:4%;">
            订单号: {{$infos['ordernumber']}}
        </div>
        <div style="float:right;margin:4%;">
            {{$infos['time1']}}
        </div>
        <br/><br/>
        @foreach($infos['tool_ss'] as $v)
            <div style="margin:4%;">
                <div>
                    检测器编号: {{$v['toolid']}}
                </div>
                <div>
                    <div>
                        检测时间: {{$v['time2']}}
                    </div>
                    <div>
                        检测时长: {{$v['time_long']}}小时
                    </div>
                </div>
                <div>
                    地点备注: {{$v['about']}}--<span>备注</span>
                </div>
                <div style="margin-top:4%;width:100%;">
                    <a onclick='getCH2O("{{$v['toolid']}}")'><div id="CH2O_{{$v['toolid']}}" class="s2">甲醛</div></a>
                    <a onclick='getC6H6("{{$v['toolid']}}")'><div id="C6H6_{{$v['toolid']}}" class="s2">甲苯</div></a>
                    <a onclick='getC8H10("{{$v['toolid']}}")'><div id="C8H10_{{$v['toolid']}}" class="s2">二甲苯</div></a>
                    <a onclick='getVOC("{{$v['toolid']}}")'><div id="VOC_{{$v['toolid']}}" class="s2">voc</div></a>
                </div>
                <div id="container_{{$v['toolid']}}" style="height:200px;"></div>
            </div>
        @endforeach
    </div>
    <div id="hui-footer">
        <a href="{{URL::asset('api/hjjc/index')}}" id="nav-tab" style="width:33%;">
            <div class="hui-footer-icons hui-icons-tab"></div>
            <div class="hui-footer-text">环境监测</div>
        </a>
        <a href="{{URL::asset('api/order/index')}}" id="nav-news" style="width:34%;">
            <div class="hui-footer-icons hui-icons-news"></div>
            <div class="hui-footer-text">订单</div>
        </a>
        <a href="{{URL::asset('api/my/index')}}" id="nav-my" style="width:33%;">
            <div class="hui-footer-icons hui-icons-my"></div>
            <div class="hui-footer-text">我的</div>
        </a>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        // hui('#nav-tab').pointMsg('hot', null, null, null, '16%');
        // hui('#nav-news').pointMsg(8, null, null, null, '25%');
        // hui('#nav-my').pointMsg(null, null, null, null, '25%');

        var tool_ids = JSON.parse("{{$tool_ids}}");
        for(var i=0;i<tool_ids.length;i++){
            getCH2O(tool_ids[i]);
        }

        function getCH2O(tool_id){
            var id = 'container_'+tool_id;
            $('#CH2O_'+tool_id).css({"color": "#00b3ee","border": "solid 1px #00b3ee"});
            $('#C6H6_'+tool_id).css({"color": "black","border": "solid 1px black"});
            $('#C8H10_'+tool_id).css({"color": "black","border": "solid 1px black"});
            $('#VOC_'+tool_id).css({"color": "black","border": "solid 1px black"});
            var dom = document.getElementById(id);
            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('api/hjjc/getCH2O')}}",
                dataType: 'json',
                data: {
                    'tool_id' : tool_id,
                },
                success: function (data) {
                    if (data.code == 200) {
                        show = JSON.parse(data.ret);
                        option = {
                            xAxis: {
                                type: 'category',
                                data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [
                                {
                                    data: show,
                                    type: 'line',
                                },
                            ]
                        };
                        if (option && typeof option === "object") {
                            myChart.setOption(option, true);
                        }
                    } else {
                        hui.iconToast(data.ret, 'warn');
                        // layer.msg(data.message, {icon: 2, time: 1000});
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }
        function getC6H6(tool_id){
            $('#C6H6_'+tool_id).css({"color": "#00b3ee","border": "solid 1px #00b3ee"});
            $('#CH2O_'+tool_id).css({"color": "black","border": "solid 1px black"});
            $('#C8H10_'+tool_id).css({"color": "black","border": "solid 1px black"});
            $('#VOC_'+tool_id).css({"color": "black","border": "solid 1px black"});
            var id = 'container_'+tool_id;
            var dom = document.getElementById(id);
            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('api/hjjc/getC6H6')}}",
                dataType: 'json',
                data: {
                    'tool_id' : tool_id,
                },
                success: function (data) {
                    if (data.code == 200) {
                        show = JSON.parse(data.ret);
                        option = {
                            xAxis: {
                                type: 'category',
                                data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [
                                {
                                    data: show,
                                    type: 'line',
                                },
                            ]
                        };
                        if (option && typeof option === "object") {
                            myChart.setOption(option, true);
                        }
                    } else {
                        hui.iconToast(data.ret, 'warn');
                        // layer.msg(data.message, {icon: 2, time: 1000});
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }
        function getC8H10(tool_id){
            $('#C8H10_'+tool_id).css({"color": "#00b3ee","border": "solid 1px #00b3ee"});
            $('#C6H6_'+tool_id).css({"color": "black","border": "solid 1px black"});
            $('#CH2O_'+tool_id).css({"color": "black","border": "solid 1px black"});
            $('#VOC_'+tool_id).css({"color": "black","border": "solid 1px black"});
            var id = 'container_'+tool_id;
            var dom = document.getElementById(id);
            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('api/hjjc/getC8H10')}}",
                dataType: 'json',
                data: {
                    'tool_id' : tool_id,
                },
                success: function (data) {
                    if (data.code == 200) {
                        show = JSON.parse(data.ret);
                        option = {
                            xAxis: {
                                type: 'category',
                                data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [
                                {
                                    data: show,
                                    type: 'line',
                                },
                            ]
                        };
                        if (option && typeof option === "object") {
                            myChart.setOption(option, true);
                        }
                    } else {
                        hui.iconToast(data.ret, 'warn');
                        // layer.msg(data.message, {icon: 2, time: 1000});
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }
        function getVOC(tool_id){
            $('#VOC_'+tool_id).css({"color": "#00b3ee","border": "solid 1px #00b3ee"});
            $('#C6H6_'+tool_id).css({"color": "black","border": "solid 1px black"});
            $('#CH2O_'+tool_id).css({"color": "black","border": "solid 1px black"});
            $('#C8H10_'+tool_id).css({"color": "black","border": "solid 1px black"});
            var id = 'container_'+tool_id;
            var dom = document.getElementById(id);
            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('api/hjjc/getVOC')}}",
                dataType: 'json',
                data: {
                    'tool_id' : tool_id,
                },
                success: function (data) {
                    if (data.code == 200) {
                        show = JSON.parse(data.ret);
                        option = {
                            xAxis: {
                                type: 'category',
                                data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [
                                {
                                    data: show,
                                    type: 'line',
                                },
                            ]
                        };
                        if (option && typeof option === "object") {
                            myChart.setOption(option, true);
                        }
                    } else {
                        hui.iconToast(data.ret, 'warn');
                        // layer.msg(data.message, {icon: 2, time: 1000});
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }

    </script>
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
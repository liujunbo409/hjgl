@extends('HJGL.user.layouts.app')
<style type="text/css">
    .s1{
        width:80px;
        height:22px;
        text-align: center;
        color:white;
        background-color: #00b3ee;
        /*border: solid 1px #555555;*/
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;
        border-bottom-left-radius: 25px;
        border-bottom-right-radius: 25px;
    }
</style>
@section('content')
    <div class="hui-header">
        <h1>待支付订单</h1>
    </div>
    <div class="hui-wrap" style="width:100%;">
        <div style="margin:20px 10px; margin-bottom:15px;" class="hui-form" id="form1">
            @foreach($tools as $v)
                <div class="hui-form-items">
                    <div class="hui-form-items-title" style="width:30%;">检测器编号:</div>
                    <input type="text" class="hui-input hui-input-clear" id="number_{{$v->number}}" value="{{$v->number}}" readonly />
                </div>
                <div class="hui-form-items">
                    <div class="hui-form-items-title" style="width:30%;">开始检测时间</div>
                    <input type="date" id="date_{{$v->number}}" class="hui-button hui-button-large hui-date-picker" value="2018-09-01" style="width:50%;height:30px;" />
                </div>
                <div class="hui-form-items">
                    <div class="hui-form-items-title" style="width:30%;"></div>
                    <input type="time" id="time_{{$v->number}}" value="" class="hui-button hui-button-large hui-date-picker" style="width:50%;height:30px;" />
                </div>
                <div class="hui-form-items">
                    <div class="hui-form-items-title" style="width:30%;">检测时长</div>
                    <input id="long_{{$v->number}}" class="hui-input hui-pwd-eye"/>
                </div>
                <div class="hui-common-title-line" style="margin:auto;width:100%;height:5px;"></div>
            @endforeach
        </div>
        <span type="button" onclick="scanQRCode()" class="hui-button hui-primary hui-wrap" value="" id="submitBtn" style="margin:auto;margin-top:10%;width:80%;">继续租赁</span>
    </div>
    <div id="hui-footer">
        <div style="width:55%;font-size:10px;">
            押金1000元/台，100元/台/天<br/>
            归还结算租金并退还押金
        </div>
        <div style="width:55%;margin:auto;">
            <input type="hidden" id="numbers" value="{{$numbers}}">
            <div style="float:left;">2000元</div>
            <a onclick="submit()" style="float:left;margin-left:5px;"><div class="s1" >支付押金</div></a>
        </div>
    </div>
@endsection

@section('script')
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php use Illuminate\Support\Facades\Config;
            use EasyWeChat\Factory; $config = Config::get("wechat.official_account.default");
            $app = Factory::officialAccount($config); echo $app->jssdk->buildConfig(array('scanQRCode'), false) ?>);
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
        function submit(){
            var str = '';
            var paying = new Array();
            var aa = $('#numbers').val();
            var a = JSON.parse(aa);
            $.each(a, function(key,val){
                var number = $('#number_'+val).val();
                paying[number] = $('#number_'+val).val() + ','+ $('#date_'+val).val()+','+$('#time_'+val).val()+','+$('#long_'+val).val();
                // paying[key]['date'] = $('#date_'+val).val();
                // paying[key]['time'] = $('#time_'+val).val();
                // paying[key]['long'] = $('#long_'+val).val();
                if(str.length == 0){
                    str = $('#number_'+val).val() + ','+ $('#date_'+val).val()+','+$('#time_'+val).val()+','+$('#long_'+val).val();
                }else{
                    str = str+','+$('#number_'+val).val() + ','+ $('#date_'+val).val()+','+$('#time_'+val).val()+','+$('#long_'+val).val();
                }
            });
            console.log(str);
            $.ajax({
                type: 'POST',
                url: "{{URL::asset('api/QRcode/pay_PPhone')}}",
                dataType: 'json',
                data:{
                    'order':str,
                    '_token':'{{csrf_token()}}',
                },
                success: function (data) {
                    console.log(data);
                    if (data.code == 200) {
                        hui.iconToast('发送中...', 'warn');
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
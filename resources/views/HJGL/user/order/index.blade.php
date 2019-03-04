@extends('HJGL.user.layouts.app')
<style type="text/css">
    .div1{
        margin-top:10px;
    }
    span{
        color: #9d9d9d;
    }
</style>
@section('content')
    <div class="hui-header">
        <h1>订单</h1>
    </div>
    <div class="hui-wrap">
        <div style="padding:5px;">
            <div class="hui-segment" id="cate">
                <a href="javascript:showDoing(1);" class="hui-segment-active">进行中</a>
                <a href="javascript:showFinish(2);">已完成</a>
            </div>
            <div id="Doing" style="padding:10px; text-align:center;">
                @foreach($order_doing as $v)
                    <div style="margin:auto;margin:5px;">
                        <div>
                            <div class="div1" style="float:left;">{{$v->created_at}}</div>
                            <div class="div1" style="float:right;">未归还({{$v->tool_finish}}/{{$v->tool_total}})</div>
                        </div>
                        <div>
                            <span class="div1" style="float:left;">使用地点</span>
                            <span class="div1" style="float:right;">{{$v->shop_name}}</span>
                        </div>
                        <div>
                            <span class="div1" style="float:left;">收费标准</span>
                            <span class="div1" style="float:right;">2222222</span>
                        </div>
                        <div>
                            <span class="div1" style="float:left;">订单号</span>
                            <span class="div1" style="float:right;">{{$v->order_number}}</span>
                        </div>
                        <div>
                            <span class="div1" style="float:left;">检测器</span>
                            <span class="div1" style="float:right;">
                                @foreach($order_doing_tool[$v->order_number] as $vv)
                                    <span>{{$vv}}</span><br/>
                                @endforeach
                            </span>
                        </div>
                        <div>
                            <a href="{{URL::asset('api/order/loan')}}?order_number={{$v->order_number}}" class="div1" style="float:right;color:#007bb6;">查看订单详情</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="Finish" style="padding:10px; text-align:center;display: none">
                @foreach($order_finish as $v)
                    <div style="margin:auto;margin:5px;">
                        <div>
                            <div class="div1" style="float:left;">{{$v->created_at}}</div>
                            <div class="div1" style="float:right;">未归还({{$v->tool_finish}}/{{$v->tool_total}})</div>
                        </div>
                        <div>
                            <span class="div1" style="float:left;">使用地点</span>
                            <span class="div1" style="float:right;">{{$v->shop_name}}</span>
                        </div>
                        <div>
                            <span class="div1" style="float:left;">收费标准</span>
                            <span class="div1" style="float:right;">2222222</span>
                        </div>
                        <div>
                            <span class="div1" style="float:left;">订单号</span>
                            <span class="div1" style="float:right;">{{$v->order_number}}</span>
                        </div>
                        <div>
                            <span class="div1" style="float:left;">检测器</span>
                            <span class="div1" style="float:right;">
                                @foreach($order_finish_tool[$v->order_number] as $vv)
                                    <span>{{$vv}}</span><br/>
                                @endforeach
                            </span>
                        </div>
                        <div>
                            <a href="{{URL::asset('api/order/loan')}}" class="div1" style="float:right;color:#007bb6;">查看订单详情</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
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
        function showDoing(index){
            index--;
            $("#Finish").hide();
            $("#Doing").show();
            hui('#cate a').eq(index).addClass('hui-segment-active').siblings().removeClass('hui-segment-active');
        }
        function showFinish(index){
            index--;
            $("#Doing").hide();
            $("#Finish").show();
            hui('#cate a').eq(index).addClass('hui-segment-active').siblings().removeClass('hui-segment-active');
        }
    </script>
@endsection
@extends('HJGL.user.layouts.app')
<style type="text/css">
    .div1{
        margin-top:10px;
    }
    span{
        color: #9d9d9d;
    }
    .center {
        display: -webkit-box;
        -webkit-box-orient: horizontal;
        -webkit-box-pack: center;
        -webkit-box-align: center;
        margin-top:10px;
    }
</style>
@section('content')
    <div class="hui-header">
        <div id="hui-back"></div>
        <h1>订单详情</h1>
    </div>
    <div class="hui-wrap">
        <div style="padding:5px 0px;">
            <div style="margin:auto;margin:15px;margin-bottom:80px;">
                <div>
                    <div class="div1" style="float:left;">{{$order->created_at}}</div>
                    <div class="div1" style="float:right;">未归还({{$order->tool_finish}}/{{$order->tool_total}})</div>
                </div>
                <div>
                    <span class="div1" style="float:left;">使用地点</span>
                    <span class="div1" style="float:right;">{{$order->shop_name}}</span>
                </div>
                <div>
                    <span class="div1" style="float:left;">收费标准</span>
                    <span class="div1" style="float:right;">2222222</span>
                </div>
                <div>
                    <span class="div1" style="float:left;">订单号</span>
                    <span class="div1" style="float:right;">{{$order->order_number}}</span>
                </div>
                @foreach($user_loan as $v)
                    <div style="margin-top:20px;background: #fcfcfc">
                        <div>
                            <div style="float:left;height:50px;margin:15px;">
                                <img style="width:50px;height:50px;" />
                            </div>
                            <div style="float:left;margin:10px;">
                                <span>{{$v->tool_number}}&nbsp;&nbsp;&nbsp;&nbsp;{{$v->loan_status == 1?'未归还':'已归还'}}</span><br/>
                                <span>检测开启时间: {{$v->work_start}}</span><br/>
                                <span>检测时长:{{$v->detection_duration}} &nbsp;&nbsp;&nbsp;&nbsp;租借时长{{$v->out_time}}</span><br/>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <div style="margin-bottom:10px;width:50%;float:left;">
                                <div class="center">租金</div>
                                <div class="center">{{$v->rent_total}}元</div>
                            </div>
                            <div style="margin-bottom:10px;width:50%;float:left;">
                                <div class="center">押金</div>
                                <div class="center">{{$v->deposit}}元</div>
                            </div>
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


    </script>
@endsection
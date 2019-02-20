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
        <div style="padding:28px 0px;">
            <div class="hui-tab">
                <div class="hui-tab-title">
                    <div>进行中</div>
                    <div>已完成</div>
                </div>
                <div class="hui-tab-body">
                    <div class="hui-tab-body-items">
                        <div class="hui-tab-item hui-list">
                            <div style="margin:auto;margin:15px;">
                                <div>
                                    <div class="div1" style="float:left;">2019-01-01 00:00:00</div>
                                    <div class="div1" style="float:right;">未归还(2/4)</div>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">使用地点</span>
                                    <span class="div1" style="float:right;">1111111</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">收费标准</span>
                                    <span class="div1" style="float:right;">2222222</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">订单号</span>
                                    <span class="div1" style="float:right;">2222222</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">检测器</span>
                                    <span class="div1" style="float:right;">
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                    </span>
                                </div>
                                <div>
                                    <a href="{{URL::asset('api/order/loan')}}" class="div1" style="float:right;color:#007bb6;">查看订单详情</a>
                                </div>
                            </div>
                            <div style="margin:auto;margin:15px;">
                                <div>
                                    <div class="div1" style="float:left;">2019-01-01 00:00:00</div>
                                    <div class="div1" style="float:right;">未归还(2/4)</div>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">使用地点</span>
                                    <span class="div1" style="float:right;">1111111</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">收费标准</span>
                                    <span class="div1" style="float:right;">2222222</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">订单号</span>
                                    <span class="div1" style="float:right;">2222222</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">检测器</span>
                                    <span class="div1" style="float:right;">
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                    </span>
                                </div>
                                <div>
                                    <a href="" class="div1" style="float:right;color:#007bb6;">查看订单详情</a>
                                </div>
                            </div>
                        </div>
                        <div class="hui-tab-item hui-list">
                            <div style="margin:auto;margin:15px;">
                                <div>
                                    <div class="div1" style="float:left;">2019-01-01 00:00:00</div>
                                    <div class="div1" style="float:right;">未归还(2/4)</div>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">使用地点</span>
                                    <span class="div1" style="float:right;">1111111</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">收费标准</span>
                                    <span class="div1" style="float:right;">2222222</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">订单号</span>
                                    <span class="div1" style="float:right;">2222222</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">检测器</span>
                                    <span class="div1" style="float:right;">
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                    </span>
                                </div>
                                <div>
                                    <a href="" class="div1" style="float:right;color:#007bb6;">查看订单详情</a>
                                </div>
                            </div>
                            <div style="margin:auto;margin:15px;">
                                <div>
                                    <div class="div1" style="float:left;">2019-01-01 00:00:00</div>
                                    <div class="div1" style="float:right;">未归还(2/4)</div>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">使用地点</span>
                                    <span class="div1" style="float:right;">1111111</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">收费标准</span>
                                    <span class="div1" style="float:right;">2222222</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">订单号</span>
                                    <span class="div1" style="float:right;">2222222</span>
                                </div>
                                <div>
                                    <span class="div1" style="float:left;">检测器</span>
                                    <span class="div1" style="float:right;">
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                        <span>123132</span><br/>
                                    </span>
                                </div>
                                <div>
                                    <a href="" class="div1" style="float:right;color:#007bb6;">查看订单详情</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        hui.tab('.hui-tab');

    </script>
@endsection
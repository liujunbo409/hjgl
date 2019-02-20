@extends('HJGL.user.layouts.app')

@section('content')
    <div class="hui-header">
        <h1>我的</h1>
    </div>
    <div class="hui-wrap">
        <div class="hui-list" style="background:#FFFFFF; margin-top:28px;">
            {{--<a style="height:auto; height:80px; padding-bottom:8px;">--}}
                <div class="hui-list-icons" style="width:110px; height:80px;float:left;">
                    123123<img src="" style="width:66px; margin:0px; border-radius:50%;" />
                </div>
                <div style="height:79px; line-height:79px;float:left;">
                    <div class="hui-list-text-content">
                        Hcoder.net
                    </div>
                </div>
            {{--</a>--}}
        </div>
        <div class="hui-list" style="background:#FFFFFF; margin-top:28px;">
            <ul>
                <li>
                    <a href="javascript:hui.open('{{URL::asset('api/my/info')}}');">
                        <div class="hui-list-text">
                            个人信息
                            <div class="hui-list-info">
                                <span class="hui-icons hui-icons-right"></span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="javascript:hui.open('{{URL::asset('api/my/phone')}}');">
                        <div class="hui-list-text">
                            更换手机号
                            <div class="hui-list-info">
                                <span class="hui-icons hui-icons-right"></span>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
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
        /*开启子页面*/
        function sopen(title, url) {
            consoledebug.log("show_optRecord url:" + url);
            var index = layer.open({
                type: 2,
                area: ['100%', '100%'],
                fixed: false,
                // maxmin: true,
                content: url
            });
        }

    </script>
@endsection
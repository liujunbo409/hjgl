@extends('HJGL.user.layouts.app')

@section('content')
    <div class="hui-header">
        <h1>环境检测</h1>
    </div>
    <div class="hui-wrap">
        <div style="float:left">
            订单号: {{$infos['ordernumber']}}
        </div>
        <div style="float:right">
            {{$infos['time1']}}
        </div>
        <br/><br/>
        @foreach($infos['tool_ss'] as $v)
            <div>
                <div>
                    检测器编号: {{$v['toolid']}}
                </div>
                <div>
                    <div style="float:left">
                        检测时间: {{$v['time2']}}
                    </div>
                    <div style="float:right">
                        检测时长: {{$v['time_long']}}小时
                    </div>
                </div>
                <div>
                    地点备注: {{$v['about']}}--<span>备注</span>
                </div>
                <div style="width:98%;height:220px;">
                    <span onclick='set("container_{{$v['toolid']}}",{{$v['CH2O']}})'>显示显示显示</span>
                    <span>甲醛</span>----<span>甲苯</span>----<span>二甲苯</span>----<span>voc</span><br/>
                    <div id="container_{{$v['toolid']}}" style="height:200px"></div>

                </div>
            </div>
        @endforeach
    </div>
    <div id="hui-footer">
        <a href="" id="nav-tab" style="width:33%;">
            <div class="hui-footer-icons hui-icons-tab"></div>
            <div class="hui-footer-text">环境监测</div>
        </a>
        <a href="" id="nav-news" style="width:34%;">
            <div class="hui-footer-icons hui-icons-news"></div>
            <div class="hui-footer-text">订单</div>
        </a>
        <a href="" id="nav-my" style="width:33%;">
            <div class="hui-footer-icons hui-icons-my"></div>
            <div class="hui-footer-text">我的</div>
        </a>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        hui('#nav-tab').pointMsg('hot', null, null, null, '16%');
        hui('#nav-news').pointMsg(8, null, null, null, '25%');
        hui('#nav-my').pointMsg(null, null, null, null, '25%');

        function set(id,datas){
            var dom = document.getElementById(id);
            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            option = {
                xAxis: {
                    type: 'category',
                    // data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                    data: datas ,
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                        data: datas,
                        type: 'line',
                    },
                    {
                        data: [100, 200, 300, 700, 600, 700, 600],
                        type: 'line',
                    }

                ]
            };
            if (option && typeof option === "object") {
                myChart.setOption(option, true);
            }
        }

    </script>
@endsection
@extends('HJGL.user.layouts.app')
<style>
    .a1{

    }
</style>
@section('content')
    <span onclick='set("container",{{$datas}})'>1111111</span>
    <div id="container" style="height:200px">22222222</div>
@endsection

@section('script')
    <script type="text/javascript">
        console.log("{{$datas}}");
        var a = JSON.parse("{{$datas}}");
        console.log(a);
        var i =0;
        for(x in a){
            i++;
            console.log(i);
        }
        function set(id){
            var dom = document.getElementById(id);
            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('api/hjjc/getCH2O')}}",
                dataType: 'json',
                data: {
                    'tool_id' : '123',
                },
                success: function (data, sta) {
                    console.log(data);
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
@endsection

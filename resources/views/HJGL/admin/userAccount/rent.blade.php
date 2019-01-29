@extends('HJGL.admin.layouts.app')

@section('content')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 账目管理 <span
                class="c-gray en">&gt;</span> 租金管理<a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace(location.href);" title="刷新"
                                                       onclick="location.replace('{{URL::asset('admin/userAccount/rent')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div style="width:100%;height:160px;">
        <div style="width:20%;margin-left:1%;float: left">
            总租金:<span id="rent_total"></span><br/>
            总未支付租金:<span id="rent_unpaid"></span><br/>
        </div>
        <div style="width:20%;margin-left:1%;float: left">
            所选月:<span id="rent_choose"></span><br/>
            月租金:<span id="rent_month"></span><br/>
            月同比:<span id="rent_month_compare"></span><br/>
            <span style="color:blue;" onclick="rent_month('front')">左</span>&nbsp;
            <span style="color:blue;" onclick="rent_month('back')">右</span>
        </div>
        <div style="width:20%;margin-left:1%;float: left">
            所选月:<span id="rent_day_choose">2</span><br/>
            日租金:<span id="rent_day"></span><br/>
            日同比:<span id="rent_day_compare"></span><br/>
            <span style="color:blue;" onclick="rent_day('front')">左</span>&nbsp;
            <span style="color:blue;" onclick="rent_day('back')">右</span>
        </div>
        <div style="width:20%;margin-left:1%;float: left">
            开始日期<input id="start" type="date" value=""><br/>
            结束日期<input id="end" type="date" value=""><br/>
            <span onclick="show()" style="color:blue;">查询</span>
            <span id="rent_range_total"></span><br/>
            <span id="rent_range_unpaid"></span>
        </div>
    </div><br/>
    <div class="page-container">
        <div class="text-c">
            <form action="{{URL::asset('admin/userAccount/rent')}}" method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    订单编号/商家: <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                    <span class="select-box" style="width:150px">
                        <select name="order_status" id="is_base" class="select" size="1">
                            @foreach(\App\Components\Utils::order_status as $key=>$value)
                                <option value="{{$key}}" {{$con_arr['order_status'] == strval($key)?'selected':''}}>{{$value}}</option>
                            @endforeach
                                <option value="0,1,2">全部</option>
                        </select>
                    </span>
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                </div>
            </form>
        </div>
        <example></example>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="r">共有数据：<strong>{{$datas->total()}}</strong> 条</span>
        </div>
        <table class="table table-border table-bordered table-bg table-sort mt-10">
            <thead>
            <tr>
                <th scope="col" colspan="8">订单列表</th>
            </tr>
            <tr class="text-c">
                <th>订单号</th>
                <th>商家</th>
                <th>总应支付租金</th>
                <th>未支付租金</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->order_number}}</td>
                    <td>{{$data->shop_name}}</td>
                    <td>{{$data->rent_total}}</td>
                    <td>{{$data->rent_unpaid}}</td>
                    <td>{{$data->order_status}}</td>
                    <td class="td-manage">
                        <a href="{{URL::asset('admin/userOrder/info?order_number='.$data->order_number)}}">详情</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-20">
            {{ $datas->appends($con_arr)->links() }}
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        var month_i = 2;
        var day_i = 2;
        window.onload = rent_total();
        window.onload = rent_month('back');
        window.onload = rent_day('back');
        function show(){
            var start_time = $("#start").val();
            var end_time = $("#end").val();
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('admin/userAccount/rent_range')}}",
                dataType: 'json',
                data: {
                    'start_time' : start_time,
                    'end_time' : end_time
                },
                success: function (data, sta) {
                    console.log(data);
                    if (data.code == 200) {
                        document.getElementById("rent_range_total").innerHTML=data.ret.rent_range_total;
                        document.getElementById("rent_range_unpaid").innerHTML=data.ret.rent_range_unpaid;
                    } else {
                        layer.msg(data.message, {icon: 2, time: 1000});
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })

        }
        function rent_total(){
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('admin/userAccount/rent_total')}}",
                dataType: 'json',
                data: {
                },
                success: function (data, sta) {
                    if (data.code == 200) {
                        document.getElementById("rent_total").innerHTML = data.ret.rent_total;
                        document.getElementById("rent_unpaid").innerHTML = data.ret.rent_unpaid;
                    } else {
                        document.getElementById("rent_total").innerHTML = data.message;
                        document.getElementById("rent_unpaid").innerHTML = data.message;
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function rent_month(i){
            if(i == 'back'){
                month_i--;
            }else if(i == 'front'){
                month_i++;
            }
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('admin/userAccount/rent_month')}}",
                dataType: 'json',
                data: {
                    'month_i': month_i,
                },
                success: function (data,) {
                    if (data.code == 200) {
                        document.getElementById("rent_month").innerHTML = data.ret.rent_total;
                        document.getElementById("rent_month_compare").innerHTML = data.ret.rent_compare;
                        document.getElementById("rent_choose").innerHTML = data.ret.search_time;
                    } else {
                        document.getElementById("rent_month").innerHTML = data.message;
                        document.getElementById("rent_month_compare").innerHTML = data.message;
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
        function rent_day(i){
            if(i == 'back'){
                day_i--;
            }else if(i == 'front'){
                day_i++;
            }
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('admin/userAccount/rent_day')}}",
                dataType: 'json',
                data: {
                    'day_i': day_i,
                },
                success: function (data,) {
                    if (data.code == 200) {
                        document.getElementById("rent_day").innerHTML = data.ret.rent_total;
                        document.getElementById("rent_day_compare").innerHTML = data.ret.rent_compare;
                        document.getElementById("rent_day_choose").innerHTML = data.ret.search_time;
                    } else {
                        document.getElementById("rent_day").innerHTML = data.message;
                        document.getElementById("rent_day_compare").innerHTML = data.message;
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
    </script>
@endsection
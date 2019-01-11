@extends('HJGL.admin.layouts.app')

@section('content')
    <div class="page-container">
        <div>
            订单号:{{$user_order->order_number}}<br/>
            商家名称: {{$user_order->shop_name}} &nbsp;&nbsp;&nbsp;&nbsp;订单状态: {{$user_order->order_status}}<br/>
            设备数量: {{$datas->total()}}
        </div>
        <div>
            订单详情<br/>
            <div>
                开启时间: {{$user_order->create_time}}&nbsp;&nbsp;&nbsp;&nbsp;结束时间:{{empty($user_order->end_time)?'未结束':$user_order->end_time}}<br/>
                订单时长:{{$user_order->long_time}} &nbsp;&nbsp;&nbsp;&nbsp;  用户手机:<br/>
                租金(待支付):{{$user_order->rent_sum}} &nbsp;&nbsp;&nbsp;&nbsp;  押金(待支付):{{$user_order->deposit_sum}}
            </div>
            <div>
                <table class="table table-border table-bordered table-bg table-sort mt-10">
                <thead>
                <tr>
                <th scope="col" colspan="8">设备列表</th>
                </tr>
                <tr class="text-c">
                <th width="50">设备编号</th>
                <th width="100">检测地址</th>
                <th width="120">检测时长</th>
                <th width="50">租金</th>
                <th width="50">押金</th>
                <th width="60">状态</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                <tr class="text-c">
                <td>{{$data->tool_number}}</td>
                <td>{{$data->address}}</td>
                <td>{{$data->detection_duration}}</td>
                <td>{{$data->rent}}</td>
                <td>{{$data->deposit}}</td>
                <td>{{$data->loan_status}}</td>
                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection
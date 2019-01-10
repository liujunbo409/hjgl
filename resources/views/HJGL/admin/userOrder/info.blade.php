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
                租金: &nbsp;&nbsp;&nbsp;&nbsp;  押金:
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
        /*设备-编辑*/
        function edit(title, url, id) {
            consoledebug.log("show_optRecord url:" + url);
            var index = layer.open({
                type: 2,
                area: ['850px', '550px'],
                fixed: false,
                maxmin: true,
                title: title,
                content: url
            });
        }

        /*设备-停用*/
        function stop(obj, id) {
            consoledebug.log("stop id:" + id);
            layer.confirm('确认要停用吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 0,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置设备状态
                setToolStatus('{{URL::asset('')}}', param, function (ret) {
                    console.log("ret:" + JSON.stringify(ret));
                    if (ret.status == true) {

                    }
                })
                $(obj).parents("tr").find(".td-manage").prepend('<a onClick="start(this,' + id + ')" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
                $(obj).remove();
                layer.msg('已停用', {icon: 5, time: 1000});
            });
        }

        /*管理员-启用*/
        function start(obj, id) {
            layer.confirm('确认要启用吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置管理员状态
                setToolStatus('{{URL::asset('')}}', param, function (ret) {
                    if (ret.status == true) {

                    }
                })
                $(obj).parents("tr").find(".td-manage").prepend('<a onClick="stop(this,' + id + ')" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
                $(obj).remove();
                layer.msg('已启用', {icon: 6, time: 1000});
            });
        }

    </script>
@endsection
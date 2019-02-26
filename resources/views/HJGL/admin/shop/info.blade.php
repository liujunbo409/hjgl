@extends('HJGL.admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 商家管理 <span
                class="c-gray en">&gt;</span> 商家列表 <span
                class="c-gray en">&gt;</span> 商家详情<a class="btn btn-success radius r btn-refresh"
                                                     style="line-height:1.6em;margin-top:3px"
                                                     href="javascript:location.replace(location.href);" title="刷新"
                                                     onclick="location.replace('{{URL::asset('admin/shop/info')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div style="width:100%;height:120px;">
            <div style="float:left;margin-left:10px;width:600px;">
                <span class="l"><strong>{{$shop->shop_name}}</strong></span><br/><br/>
                <div class="l" style="width:300px;">
                    <span class="l">管理员姓名: {{$shop->name}}</span><br/>
                    <span class="l">地址: {{$shop->address}}</span>
                </div>
                <div class="r"  style="width:300px;">
                    <span class="l">联系电话: {{$shop->phone}}</span><br/>
                    <span class="l">加入时间: {{$shop->created_at}}</span>
                </div>
                <br/>
            </div>
            <div style="float:right;margin-right:10px;width:300px;">
                <span class="r"><a>历史</a>&nbsp;<a>编辑</a></span><br/>
                <span class="r">月租借率</span><br/>
                <div class="r" style="width:100px;height:60px;background-color: #75aa94">

                </div>
            </div>
        </div>
        <div class="cl pd-5 bg-1 bk-gray mt-20" style="width:100%;">
            <span class="l">
                 <a href="javascript:;" onclick="edit('添加设备','{{URL::asset('admin/shop/chooseTool')}}?id={{$shop->id}}',{{$shop->id}})"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加检测器
                 </a>

                <a title="选择设备" href="javascript:;"
                   onclick="edit('管理员编辑','{{URL::asset('admin/shop/chooseTool')}}?id={{$shop->id}}',{{$shop->id}})"
                   class="ml-5" style="text-decoration:none">
                            <i class="Hui-iconfont">选择设备</i>
                        </a>
            </span>
            <span class="r">共有数据：<strong>{{$datas->total()}}</strong> 条</span>
        </div>
        <table class="table table-border table-bordered table-bg table-sort mt-10">
            <thead>
            <tr>
                <th scope="col" colspan="8">设备列表</th>
            </tr>
            <tr class="text-c">
                <th width="50">设备编号</th>
                <th width="100">检测时长</th>
                <th width="50">状态</th>
                <th width="60">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->number}}</td>
                    <td><span style="color:yellow;">待商议</span></td>
                    <td class="td-status">
                        @if($data->status=="2")
                            <span class="label label-success radius">已启用</span>
                        @else
                            <span class="label label-default radius">已禁用</span>
                        @endif
                    </td>
                    <td class="td-manage">
                        <a title="详情" href="javascript:;"
                           onclick="edit('设备详情','{{URL::asset('admin/tool/info')}}?id={{$data->id}}',{{$data->id}})"
                           class="ml-5" style="text-decoration:none">
                            <i class="Hui-iconfont">详情</i>
                        </a>
                        <span style="color:blue;cursor: pointer;" onclick="remove('{{$data->id}}','{{$shop->id}}')">移除</span>
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

        /*设备-编辑*/
        function edit(title, url, id) {
            // consoledebug.log("show_optRecord url:" + url);
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
            // consoledebug.log("stop id:" + id);
            layer.confirm('确认要停用吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置设备状态
                setToolStatus('{{URL::asset('')}}', param, function (ret) {
                    // console.log("ret:" + JSON.stringify(ret));
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
                    status: 2,
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

        /*
         * 设备移除
         *
         * By Yuyang
         *
         * 2019-01-14
         */
        function remove(tool_id, shop_id) {
            var param = {
                shop_id: shop_id,
                tool_id: tool_id,
            }
            removeTool('{{URL::asset('')}}', param, function (ret) {
                // consoledebug.log(ret);
                if (ret.result == true) {
                    layer.msg('选择成功', {icon: 1, time: 1000});
                    window.location.reload();
                }
                else{
                    layer.msg(ret.message, {icon: 2, time: 1000});
                }
            });
        }

    </script>
@endsection
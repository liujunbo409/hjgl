@extends('HJGL.admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 设备管理 <span
                class="c-gray en">&gt;</span> 设备列表<a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace(location.href);" title="刷新"
                                                       onclick="location.replace('{{URL::asset('admin/tool/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form action="{{URL::asset('admin/tool/index')}}" method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                           placeholder="设备编号" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                </div>
            </form>
        </div>
        <example></example>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;" onclick="edit('添加设备','{{URL::asset('admin/tool/edit')}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加设备
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
                <th>设备编号</th>
                <th>归属商家</th>
                <th>加入时间</th>
                <th>设备现状</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->number}}</td>
                    <td>{{$data->shop_name}}</td>
                    <td>{{$data->create_time}}</td>
                    <td>
                        @if($data->loan_status=="2")
                            <span class="label label-success radius">已借出</span>
                        @elseif($data->loan_status == 1)
                            <span class="label label-default radius">未借出</span>
                        @else
                            <span class="label label-default radius">待校准</span>
                        @endif
                    </td>
                    <td class="td-status">
                        @if($data->status=="2")
                            <span class="label label-success radius">已启用</span>
                        @else
                            <span class="label label-default radius">已禁用</span>
                        @endif
                    </td>
                    <td class="td-manage">
                        @if($data->status=="2")
                            <a style="text-decoration:none" onClick="stop(this,'{{$data->id}}')"
                               href="javascript:;"
                               title="停用">
                                <i class="Hui-iconfont">&#xe631;</i>
                            </a>
                        @else
                            <a style="text-decoration:none" onClick="start(this,'{{$data->id}}')"
                               href="javascript:;"
                               title="启用">
                                <i class="Hui-iconfont">&#xe615;</i>
                            </a>
                        @endif
                        <a title="编辑" href="javascript:;"
                           onclick="edit('管理员编辑','{{URL::asset('admin/tool/edit')}}?id={{$data->id}}',{{$data->id}})"
                           class="ml-5" style="text-decoration:none">
                            <i class="Hui-iconfont" style="color:blue;">编辑</i>
                        </a>
                            @if(!empty($data->shop_id))
                                已有选择
                            @else
                                <a title="选择商家" href="javascript:;"
                                   onclick="edit('管理员编辑','{{URL::asset('admin/tool/chooseShop')}}?id={{$data->id}}',{{$data->id}})"
                                   class="ml-5" style="text-decoration:none">
                                    <i class="Hui-iconfont" style="color:blue;">选择商家</i>
                                </a>
                            @endif

                            <a href="{{URL::asset('admin/tool/info?id='.$data->id)}}" style="color:blue;">详情</a>
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
                    status: 1,
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

    </script>
@endsection
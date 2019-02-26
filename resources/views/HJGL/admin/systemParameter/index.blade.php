@extends('HJGL.admin.layouts.app')

@section('content')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 系统参数管理 <span
                class="c-gray en">&gt;</span> 系统参数列表 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace(location.href);" title="刷新"
                                                      onclick="location.replace('{{URL::asset('admin/systemParameter/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form action="{{URL::asset('admin/article/index')}}" method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                           placeholder="系统参数名称" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i>搜索
                    </button>
                </div>
            </form>
        </div>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;" onclick="edit('添加系统参数','{{URL::asset('admin/systemParameter/edit')}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加系统参数
                 </a>
            </span>
            <span class="r"></span>
        </div>
        <table class="table table-border table-bordered table-bg mt-20">
            <thead>
            <tr class="text-c">
                <th width="80">ID</th>
                <th width="160">系统参数名</th>
                <th width="160">系统参数标识</th>
                <th width="80">系统参数值</th>
                <th width="80">状态 </th>
                <th width="80">创建时间 </th>
                <th width="80">创建人 </th>
                <th width="80">修改时间 </th>
                <th width="80">修改人 </th>
                <th width="120">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->id}}</td>
                    <td>{{$data->parameter_name}}</td>
                    <td>{{$data->parameter}}</td>
                    <td>{{$data->parameter_val}}</td>
                    <td class="td-status">
                        @if($data->status=="2")
                            <span class="label label-success radius">已启用</span>
                        @else
                            <span class="label label-default radius">已禁用</span>
                        @endif
                    </td>
                    <td>{{$data->created_at}}</td>
                    <td>{{$data->create_person}}</td>
                    <td>{{$data->updated_at}}</td>
                    <td>{{$data->update_person}}</td>
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
                           onclick="edit('系统参数编辑','{{URL::asset('admin/systemParameter/edit')}}?id={{$data->id}})',{{$data->id}})"
                           class="ml-5" style="text-decoration:none">
                            编辑
                        </a>
                        {{--<span style="color:#ff0000;cursor: pointer;" onclick="deleteCon('{{$data->id}}','{{$data->name}}')">删除</span>--}}
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
        /*系统参数-编辑*/
        function edit(title, url, id) {
            // consoledebug.log("edit url:" + url);
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }
        /*
         * 删除系统参数确认框
         *
         * By Yuyang
         *
         * 2019-01-07
         */
        function deleteCon(id,name) {
            var text = '删除系统参数';
            text = '确定要删除'+name+'吗？';
            layer.confirm(text, function () {
                //此处请求后台程序，下方是成功后的前台处理
                deleteArticle(id);
            });
        }
        /*
         * 删除系统参数
         *
         * By Yuyang
         *
         * 2019-01-07
         */
        function deleteArticle(id) {
            var param = {
                id: id,
            }
            parameter_delete('{{URL::asset('')}}', param, function (ret) {
                if (ret.result == true) {
                    layer.msg('删除成功', {icon: 1, time: 1000});
                    window.location.reload();
                }
                else{
                    layer.msg(ret.message, {icon: 2, time: 1000});
                }
            });
        }

        /*系统参数-停用*/
        function stop(obj, id) {
            layer.confirm('确认要停用吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置设备状态
                setParameterStatus('{{URL::asset('')}}', param, function (ret) {
                    if (ret.status == true) {

                    }
                })
                $(obj).parents("tr").find(".td-manage").prepend('<a onClick="start(this,' + id + ')" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
                $(obj).remove();
                layer.msg('已停用', {icon: 5, time: 1000});
            });
        }

        /*系统参数-启用*/
        function start(obj, id) {
            layer.confirm('确认要启用吗？', function (index) {
                //此处请求后台程序，下方是成功后的前台处理
                var param = {
                    id: id,
                    status: 2,
                    _token: "{{ csrf_token() }}"
                }
                //从后台设置管理员状态
                setParameterStatus('{{URL::asset('')}}', param, function (ret) {
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
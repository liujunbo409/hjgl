@extends('HJGL.admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 设备管理 <span
                class="c-gray en">&gt;</span> 设备选择<a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace(location.href);" title="刷新"
                                                       onclick="location.replace('{{URL::asset('admin/tool/chooseShop')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form action="{{URL::asset('admin/tool/chooseShop')}}" method="get" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input name="id" value="{{$tool->id}}" hidden>
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                           placeholder="商家名称" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
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
                <th scope="col" colspan="8">商家列表</th>
            </tr>
            <tr class="text-c">
                <th width="40">商家</th>
                <th width="50">管理员姓名</th>
                <th width="100">手机</th>
                <th width="120">设备数量</th>
                <th width="50">加入时间</th>
                <th width="60">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->shop_name}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->phone}}</td>
                    <td>xxxxx</td>
                    <td>{{$data->created_at}}</td>
                    <td class="td-manage">
                        @if(!empty($tool->shop_id))
                            已选择商家
                        @else
                            <span style="color:blue;cursor: pointer;" onclick="chooseSure('{{$data->shop_name}}','{{$data->id}}','{{$tool->id}}')">选择</span>
                        @endif
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

        function chooseSure(name,shop_id,tool_id) {
            var text = '选择商家';
            text = '是否确定分配到'+name+'商家下?';
            layer.confirm(text, function () {
                //此处请求后台程序，下方是成功后的前台处理
                chooseCon(shop_id,tool_id);
            });
        }
        /*
         * 选择商家
         *
         * By Yuyang
         *
         * 2019-01-02
         */
        function chooseCon(shop_id,tool_id) {
            var param = {
                shop_id: shop_id,
                tool_id: tool_id,
            }
            chooseShop('{{URL::asset('')}}', param, function (ret) {
                consoledebug.log(ret);
                if (ret.result == true) {
                    layer.msg('选择成功', {icon: 1, time: 1000});
//                    window.location.reload();
                    setTimeout(function () {
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.$('.btn-refresh').click();
                        parent.layer.close(index);
                    }, 500)
                }
                else{
                    layer.msg(ret.message, {icon: 2, time: 1000});
                }
            });
        }

    </script>
@endsection
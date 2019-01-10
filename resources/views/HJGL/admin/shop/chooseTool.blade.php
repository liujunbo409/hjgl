@extends('HJGL.admin.layouts.app')

@section('content')

    <span style="margin-left:30px;">{{$shop->shop_name}} -- 添加新设备</span>
    <div class="page-container">
        <div class="text-c">
            <form action="{{URL::asset('admin/shop/chooseTool')}}" method="get" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input name="id" value="{{$shop->id}}" hidden>
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                           placeholder="商家编号" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
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
                <th width="40">设备ID</th>
                <th width="50">设备编号</th>
                <th width="50">加入时间</th>
                <th width="60">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr class="text-c">
                <td>已选择</td>
            </tr>
            @foreach($my_tools as $my_tool)
                <tr class="text-c">
                    <td>{{$my_tool->id}}</td>
                    <td>{{$my_tool->number}}</td>
                    <td>{{$my_tool->create_time}}</td>
                    <td class="td-manage">
                        @if($my_tool->shop_id == $shop->id)
                            <span>已被选择</span>
                        @else
                            <span style="color:blue;cursor: pointer;" onclick="chooseCon('{{$my_tool->id}}','{{$shop->id}}')">选择</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr class="text-c">
            <td>未选择</td>
            </tr>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->id}}</td>
                    <td>{{$data->number}}</td>
                    <td>{{$data->create_time}}</td>
                    <td class="td-manage">
                        @if($data->shop_id == $shop->id)
                            <span>已被选择</span>
                            @else
                            <span style="color:blue;cursor: pointer;" onclick="chooseCon('{{$data->id}}','{{$shop->id}}')">选择</span>
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
        /*
         * 选择设备
         *
         * By Yuyang
         *
         * 2019-01-02
         */
        function chooseCon(tool_id, shop_id) {
            var param = {
                shop_id: shop_id,
                tool_id: tool_id,
            }
            chooseTool('{{URL::asset('')}}', param, function (ret) {
                consoledebug.log(ret);
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
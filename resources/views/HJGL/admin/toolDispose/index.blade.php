@extends('HJGL.admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 设备管理 <span
                class="c-gray en">&gt;</span> 设备列表<a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace(location.href);" title="刷新"
                                                       onclick="location.replace('{{URL::asset('admin/toolDispose/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form action="{{URL::asset('admin/toolDispose/index')}}" method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    设备编号/分配商家: <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                           placeholder="设备编号/分配商家" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                    <span class="select-box" style="width:150px">
                        <select name="process" id="is_base" class="select" size="1">
                            <option value="" {{$con_arr['process']==null?'selected':''}}>未选择</option>
                            @foreach(\App\Components\Utils::tool_dispose_process as $key=>$value)
                                <option value="{{$key}}" {{$con_arr['process']['0'] == strval($key)?'selected':''}}>{{$value}}</option>
                            @endforeach
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
            <span class="l">
                 <a href="{{URL::asset('admin/toolDispose/index')}}"
                    class="btn btn-primary radius">设备校准
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
                <th>分配商家</th>
                <th>检测时长</th>
                <th>问题</th>
                <th>处理过程</th>
                <th>停用时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->tool_num}}</td>
                    <td>{{$data->shop_name}}</td>
                    <td>{{$data->monitoring_duration}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{\App\Components\Utils::tool_dispose_process[$data->process]}}</td>
                    <td>{{$data->stop_time}}</td>
                    <td class="td-manage">
                        <span style="color:blue;cursor: pointer;" onclick="operate('{{$data->id}}','{{$data->process}}')">操作</span>
                        <a style="color:blue;" href="{{URL::asset('admin/toolDispose/info?id='.$data->id)}}">详情</a>
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
         * 设备处理--操作确认框
         *
         * By Yuyang
         *
         * 2018-12-07
         */
        function operate(id,process) {
            var text = '设备处理操作状态';
            if(process == 1){
                text = '设备是否已取回';
                layer.confirm(text, function () {
                    //此处请求后台程序，下方是成功后的前台处理
                    operate_do(id,process);
                });
            }else if(process == 2){
                text = '设备是否已处理';
                layer.confirm(text, function () {
                    //此处请求后台程序，下方是成功后的前台处理
                    operate_do(id,process);
                });
            }else if(process == 3){
                text = '设备是否已送回';
                layer.confirm(text, function () {
                    //此处请求后台程序，下方是成功后的前台处理
                    operate_do(id,process);
                });
            }else{
                alert('已是最终状态，无法在进行操作');
            }
        }
        /*
         * 设备处理--操作
         *
         * By Yuyang
         *
         * 2018-12-07
         */
        function operate_do(id,process) {
            var param = {
                id: id,
                process: process,
            }
            setToolDisposeStatus('{{URL::asset('')}}', param, function (ret) {
                if (ret.result == true) {
                    layer.msg('设置成功', {icon: 1, time: 1000});
                    window.location.reload();
                }
                else{
                    layer.msg(ret.message, {icon: 2, time: 1000});
                }
            });
        }
    </script>
@endsection
@extends('HJGL.admin.layouts.app')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('f-tree/css/css.css') }}"/>
<script type="text/javascript" src="{{ URL::asset('f-tree/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('f-tree/js/config.js') }}"></script>
{{--<script type="text/javascript" src="{{ URL::asset('f-tree/js/data.js') }}"></script>--}}
<style>
.editrow{
    text-indent:5%;
    display: flex;
    line-height: 30px;
    margin-top:15px;
    margin-right:15px;
}
.editlabel{
    width:20%;
    text-align:right;
    float: left;
}
.editinput{
    width: 45%;
    text-indent:5px;
    float: right;
    margin-left:15px;
}
.actionButton{
    width: 100px;
    height: 30px;
    line-height: 30px;
    background-color: #5a98de;
    text-align: center;
    border-radius: 5px;
    margin-left: 40px;
    color: #ffffff;
    cursor: pointer;
    margin: 10px auto;
}
</style>
@section('content')
    <div class="text-c">
    </div>
<table class="table table-border table-bordered table-bg table-sort mt-10">
    <thead>
    <tr>
        <div class="row cl">
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{URL::asset('admin/article/articleList')}}?id={{$data['id']}}" class="btn btn-default radius" type="button">返回</a>
        </div>
        <span class="r">共有数据：<strong>{{$ascriptions->total()}}</span>
    </tr>
    <tr class="text-c">
        <th>ID</th>
        <th>文章标题</th>
        <th>录入人</th>
        <th>录入时间</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ascriptions as $ascription)
        <tr class="text-c">
            <td>{{$ascription->article_id}}</td>
            <td>{{$ascription->article_title}}</td>
            <td>{{$ascription->article_oper_name}}</td>
            <td>{{$ascription->article_created_at}}</td>
            <td class="td-status">
                @if($ascription->article_status=="2")
                    <span class="label label-success radius">已启用</span>
                @else
                    <span class="label label-default radius">已禁用</span>
                @endif
            </td>
            <td class="td-manage">
                @if($ascription->article_status=="2")
                    <a style="text-decoration:none" onClick="stop(this,'{{$ascription->article_id}}')"
                       href="javascript:;"
                       title="停用">
                        <i class="Hui-iconfont">&#xe631;</i>
                    </a>
                @else
                    <a style="text-decoration:none" onClick="start(this,'{{$ascription->article_id}}')"
                       href="javascript:;"
                       title="启用">
                        <i class="Hui-iconfont">&#xe615;</i>
                    </a>
                @endif
                <input id="seq_{{$ascription->article_id}}" name="seq" value="1" />
                <span style="color:#ff0000;cursor: pointer;" onclick="up('{{$ascription->article_id}}','{{$data['id']}}')">上</span>
                <span style="color:#ff0000;cursor: pointer;" onclick="down('{{$ascription->article_id}}','{{$data['id']}}')">下</span>
                <span style="color:#ff0000;cursor: pointer;" onclick="moveList('选择目录','{{URL::asset('admin/article/moveArticleList')}}?article_id={{$ascription->article_id}}&old_type_id={{$ascription->type_id}}')">移动文章</span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="mt-20">
    {{ $ascriptions->appends(['id'=>$data['id']])->links() }}
</div>
@endsection

@section('script')
    <script type="text/javascript">
        /*增加基础文章*/
        function moveList(title, url, id) {
            consoledebug.log("show_optRecord url:" + url);
            var index = layer.open({
                type: 2,
                area: ['100%', '100%'],
                fixed: false,
                maxmin: true,
                title: title,
                content: url
            });

        }

        function click(id,type) {
            window.location.href = "{{ URL::asset('admin/article/sort?id=')}}" + id ;
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
                setArticleStatus('{{URL::asset('')}}', param, function (ret) {
//                    console.log("ret:" + JSON.stringify(ret));
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
                setArticleStatus('{{URL::asset('')}}', param, function (ret) {
                    if (ret.status == true) {

                    }
                })
                $(obj).parents("tr").find(".td-manage").prepend('<a onClick="stop(this,' + id + ')" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
                $(obj).remove();
                layer.msg('已启用', {icon: 6, time: 1000});
            });
        }

        function up(article_id,type_id) {
            var seq = document.getElementById("seq_" + article_id).value;
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('admin/article/upArticle')}}",
                dataType: 'json',
                data: {
                    article_id: article_id,
                    type_id: type_id,
                    seq: seq,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data, sta) {
                    if (data.code == 200) {
                        window.location.href = '{{URL::asset('admin/article/sort')}}?id={{$data['id']}}';
                    } else {
                        layer.alert( data.message , function () {
                        });
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }

        function down(article_id,type_id) {
            var seq = document.getElementById("seq_" + article_id).value;
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('admin/article/downArticle')}}",
                dataType: 'json',
                data: {
                    article_id: article_id,
                    type_id: type_id,
                    seq: seq,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data, sta) {
                    if (data.code == 200) {
                        window.location.href = '{{URL::asset('admin/article/sort')}}?id={{$data['id']}}';
                    } else {
                        layer.alert(data.message, function () {
                        });
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
    </script>

@endsection



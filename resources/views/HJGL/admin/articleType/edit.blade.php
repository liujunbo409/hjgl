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
    <a class="btn btn-success radius r btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" onclick="location.replace('{{URL::asset('admin/articleType/edit')}}?id={{$data['id']}}');">
        <i class="Hui-iconfont">&#xe68f;</i></a>
    <br/><br/>
    <div class="text-c">
        <form action="{{URL::asset('admin/articleType/edit')}}" method="get" class="form-horizontal">
            {{csrf_field()}}
            <div class="Huiform text-r">
                <input name="id" value="{{$data['id']}}" hidden>
                <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                       placeholder="文章名称" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                <span class="select-box" style="width:150px">
                    <select class="select" name="status" id="status" size="1">
                        <option value="" {{$con_arr['status']==null?'selected':''}}>未选择</option>
                        @foreach(\App\Components\Utils::articler_status as $key=>$value)
                            <option value="{{$key}}" {{$con_arr['status'] == strval($key)?'selected':''}}>{{$value}}</option>
                        @endforeach
                    </select>
                </span>
                <button type="submit" class="btn btn-success" id="" name="">
                    <i class="Hui-iconfont">&#xe665;</i>搜索
                </button>
            </div>
        </form>

    </div>
<table class="table table-border table-bordered table-bg table-sort mt-10">
    <thead>
    <tr>
        <a href="javascript:;" style="margin-top: 5px" onclick="edit('添加文章','{{URL::asset('admin/articleType/addArticle')}}?id={{$data['id']}}')"
           class="btn btn-primary radius">
            <i class="Hui-iconfont">&#xe600;</i> 添加新文章
        </a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="{{URL::asset('admin/articleType/sort')}}?id={{$data['id']}}" style="margin-top: 5px" class="btn btn-primary radius">
            <i class="Hui-iconfont">&#xe600;</i> 排序
        </a>
        <span class="r" style="margin-top: 20px">共有数据：<strong>{{$articles->total()}}</span>
        {{--<a href="javascript:;" onclick="edit('添加文章','{{URL::asset('admin/articleType/chooseArticle')}}?id={{$data['id']}}')"--}}
           {{--class="btn btn-primary radius">--}}
            {{--<i class="Hui-iconfont">&#xe600;</i> 选择新文章--}}
        {{--</a>--}}
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
    @foreach($articles as $article)
        <tr class="text-c">
            <td>{{$article->id}}</td>
            <td>{{$article->title}}</td>
            <td>{{$article->oper_name}}</td>
            <td>{{$article->create_time}}</td>
            <td class="td-status">
                @if($article->status=="2")
                    <span class="label label-success radius">已启用</span>
                @else
                    <span class="label label-default radius">已禁用</span>
                @endif
            </td>
            <td class="td-manage">
                @if($article->status=="2")
                    <a style="text-decoration:none" onClick="stop(this,'{{$article->id}}')"
                       href="javascript:;"
                       title="停用">
                        <i class="Hui-iconfont">&#xe631;</i>
                    </a>
                @else
                    <a style="text-decoration:none" onClick="start(this,'{{$article->id}}')"
                       href="javascript:;"
                       title="启用">
                        <i class="Hui-iconfont">&#xe615;</i>
                    </a>
                @endif
                <a title="详情" href="javascript:;"
                   onclick="info('文章详情','{{URL::asset('admin/article/info')}}?id={{$article->id}})',{{$article->id}})"
                   class="ml-5" style="text-decoration:none">
                    详情
                </a>
                <a title="编辑" href="javascript:;"
                   onclick="edit_article('文章编辑','{{URL::asset('admin/article/edit')}}?id={{$article->id}})',{{$article->id}})"
                   class="ml-5" style="text-decoration:none">
                    编辑
                </a>
                <span style="color:#ff0000;cursor: pointer;" onclick="deleteCon('{{$article->id}}',{{$ascription_sign[$article->id]}},'{{$article->title}}')">删除</span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="mt-20">
    {{ $articles->appends(['id'=>$data['id']])->links() }}
</div>
@endsection

@section('script')
    <script type="text/javascript">
        /*增加基础文章*/
        function edit(title, url, id) {
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

        /*
         * 删除文章确认框
         *
         * By Yuyang
         *
         * 2018-12-07
         */
        function deleteCon(article_id,type_id,name) {
            var text = '删除文章';
            text = '确定要删除[ '+name+' ]吗？';
            layer.confirm(text, function () {
                //此处请求后台程序，下方是成功后的前台处理
                deletearticle(article_id,type_id);
            });

        }

        /*
         * 删除文章
         *
         * By Yuyang
         *
         * 2018-12-07
         */
        function deletearticle(article_id,type_id) {
            var param = {
                article_id: article_id,
                type_id: type_id,
            }
            articleascription_delete('{{URL::asset('')}}', param, function (ret) {
                if (ret.result == true) {
                    layer.msg('删除成功', {icon: 1, time: 1000});
                    window.location.reload();
                }
                else{
                    layer.msg(ret.message, {icon: 2, time: 1000});
                }
            });
        }

        /*文章-编辑*/
        function edit_article(title, url, id) {
            consoledebug.log("edit url:" + url);
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }
        /*文章-详情*/
        function info(title, url, id) {
            consoledebug.log("edit url:" + url);
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
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
    </script>

@endsection



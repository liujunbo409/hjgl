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
    <nav class="breadcrumb"><a class="btn btn-success radius r btn-refresh"
                               style="line-height:1.6em;margin-top:3px"
                               href="javascript:location.replace(location.href);" title="刷新"
                               onclick="location.replace('{{URL::asset('admin/articleType/edit')}}?id={{$data->id}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="text-c">
        <form action="{{URL::asset('admin/articleType/edit')}}" method="post" class="form-horizontal">
            {{csrf_field()}}
            <div class="Huiform text-r">
                <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                       placeholder="文章名称" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                <button type="submit" class="btn btn-success" id="" name="">
                    <i class="Hui-iconfont">&#xe665;</i>搜索
                </button>
            </div>
        </form>
    </div>
<table class="table table-border table-bordered table-bg table-sort mt-10">
    <thead>
    <tr>
        <a href="javascript:;" onclick="edit('添加文章','{{URL::asset('admin/articleType/addArticle')}}?id={{$data->id}}')"
           class="btn btn-primary radius">
            <i class="Hui-iconfont">&#xe600;</i> 添加新文章
        </a>
        <a href="javascript:;" onclick="edit('添加文章','{{URL::asset('admin/articleType/chooseArticle')}}?id={{$data->id}}')"
           class="btn btn-primary radius">
            <i class="Hui-iconfont">&#xe600;</i> 选择新文章
        </a>
    </tr>
    <tr class="text-c">
        <th width="80">ID</th>
        <th width="160">题目</th>
        <th width="160">作者</th>
        <th width="80">内容</th>
        <th width="120">操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($articles as $article)
        <tr class="text-c">
            <td>{{$article->id}}</td>
            <td>{{$article->title}}</td>
            <td>{{$article->author}}</td>
            <td>{{$article->html}}</td>
            <td>
                <span style="color:#ff0000;cursor: pointer;" onclick="deleteCon('{{$article->id}}',{{$ascriptions[$article->id]}},'{{$article->title}}')">删除</span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="mt-20">
    {{--{{ $mediences->appends($con_arr)->links() }}--}}
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
    </script>

@endsection



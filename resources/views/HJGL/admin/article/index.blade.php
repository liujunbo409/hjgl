@extends('HJGL.admin.layouts.app')

@section('content')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 文章管理 <span
                class="c-gray en">&gt;</span> 文章列表 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace(location.href);" title="刷新"
                                                      onclick="location.replace('{{URL::asset('admin/article/index')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form action="{{URL::asset('admin/article/index')}}" method="post" class="form-horizontal">
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
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                 <a href="javascript:;" onclick="edit('添加文章','{{URL::asset('admin/article/edit')}}')"
                    class="btn btn-primary radius">
                     <i class="Hui-iconfont">&#xe600;</i> 添加文章
                 </a>
            </span>
            <span class="r"></span>
        </div>
        <table class="table table-border table-bordered table-bg mt-20">
            <thead>
            <tr class="text-c">
                <th width="80">ID</th>
                <th width="160">题目</th>
                <th width="160">作者</th>
                <th width="80">内容</th>
                <th width="120">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->id}}</td>
                    <td>{{$data->title}}</td>
                    <td>{{$data->author}}</td>
                    <td>{{$data->html}}</td>
                    <td class="td-manage">
                        <a title="编辑" href="javascript:;"
                           onclick="edit('文章编辑','{{URL::asset('admin/article/edit')}}?id={{$data->id}})',{{$data->id}})"
                           class="ml-5" style="text-decoration:none">
                            编辑
                        </a>
                        <span style="color:#ff0000;cursor: pointer;" onclick="deleteCon('{{$data->id}}','{{$data->name}}')">删除</span>
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
        /*文章-编辑*/
        function edit(title, url, id) {
            consoledebug.log("edit url:" + url);
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }
        /*
         * 删除文章确认框
         *
         * By Yuyang
         *
         * 2018-12-07
         */
        function deleteCon(id,name) {
            var text = '删除文章';
            text = '确定要删除'+name+'吗？';
            layer.confirm(text, function () {
                //此处请求后台程序，下方是成功后的前台处理
                deleteArticle(id);
            });
        }
        /*
         * 删除文章
         *
         * By Yuyang
         *
         * 2018-12-07
         */
        function deleteArticle(id) {
            var param = {
                id: id,
            }
            article_delete('{{URL::asset('')}}', param, function (ret) {
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
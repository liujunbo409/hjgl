@extends('HJGL.admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 文章管理 <span
                class="c-gray en">&gt;</span> 文章列表 <a class="btn btn-success radius r btn-refresh"
                                                      style="line-height:1.6em;margin-top:3px"
                                                      href="javascript:location.replace(location.href);" title="刷新"
                                                      onclick="location.replace('{{URL::asset('admin/articleType/chooseArticle')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form action="{{URL::asset('admin/articleType/chooseArticle')}}" method="get" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px"
                           placeholder="文章名称" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                    <input name = 'id' value='{{$type_id}}' hidden>
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i>搜索
                    </button>
                </div>
            </form>
        </div>
        <table class="table table-border table-bordered table-bg mt-20">
            <thead>
            <tr class="text-c">
                <th width="80">ID</th>
                <th width="160">文章名</th>
                <th width="160">作者</th>
                <th width="120">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->id}}</td>
                    <td>{{$data->title}}</td>
                    <td>{{$data->author}}</td>
                    <td class="td-manage">
                        @if(in_array($data->id,$ids))
                        <span>已选择</span>
                        @else
                        <span style="color:blue;cursor: pointer;" onclick="chooseCon('{{$data->id}}','{{$type_id}}')">选择文章</span>
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
         * 选择文章
         *
         * By Yuyang
         *
         * 2018-12-21
         */
        function chooseCon(id,type_id) {
            var param = {
                medicine_id: id,
                type_id: type_id,
            }
            medicineascription_choosesave('{{URL::asset('')}}', param, function (ret) {
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
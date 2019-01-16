@extends('HJGL.admin.layouts.app')

@section('content')

    <div class="page-container">
            {{csrf_field()}}
                文章标题：{{$data->title}}<br/><br/><br/>
                录入人：{{$data->oper_name}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                录入时间：{{$data->create_time}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                文章分类：{{$data->oper_name}}<br/><br/><br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$data->html}}<br/><br/><br/>
                <a title="编辑"  href="javascript:;"
                   onclick="edit('文章编辑','{{URL::asset('admin/article/edit')}}?id={{$data->id}}&is_type=1',{{$data->id}})"
                   class="btn btn-primary radius" style="text-decoration:none">
                    编辑
                </a>
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

        function click(id,type) {
            window.location.href = "{{ URL::asset('admin/article/info?id=')}}" + id ;
        }

        $(function () {
            //获取七牛token
            $("#form-edit").validate({
                rules: {
                    chemical_name: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function (form) {
                    var index = layer.load(2, {time: 10 * 1000}); //加载
                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: "{{ URL::asset('admin/article/edit')}}",
                        success: function (ret) {
                            consoledebug.log(JSON.stringify(ret));
                            if (ret.result) {
                                layer.msg('保存成功', {icon: 1, time: 1000});
                                setTimeout(function () {
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.$('.btn-refresh').click();
                                    parent.layer.close(index);
                                }, 500)
                            } else {
                                layer.msg(ret.message, {icon: 2, time: 1000});
                            }
                            layer.close(index);
                        },
                        error: function (XmlHttpRequest, textStatus, errorThrown) {
                            $('#password').val('');
                            layer.msg('保存失败', {icon: 2, time: 1000});
                            consoledebug.log("XmlHttpRequest:" + JSON.stringify(XmlHttpRequest));
                            consoledebug.log("textStatus:" + textStatus);
                            consoledebug.log("errorThrown:" + errorThrown);
                        }
                    });
                }
            });
        });

    </script>
@endsection
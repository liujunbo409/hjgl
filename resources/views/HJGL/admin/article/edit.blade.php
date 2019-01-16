@extends('HJGL.admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            <div class="row cl hidden">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="id" name="id" type="text" class="input-text"
                           value="{{ isset($article->id) ? $article->id : '' }}" placeholder="文章id">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>文章标题：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="title" name="title" type="text" class="input-text"
                           value="{{ isset($article->title) ? $article->title : '' }}" placeholder="请输入">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>作者：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="author" name="author" type="text" class="input-text"
                           value="{{ isset($article->author) ? $article->author : '' }}" placeholder="请输入">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>内容：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea id="html" name="html" rows="15" cols="65%" class="text">{{ isset($article->html) ? $article->html : '' }}</textarea>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="保存文章">
                    <button onClick="layer_close();" class="btn btn-default radius" type="button">取消</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
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
                            if (ret.result) {
                                layer.msg('保存成功', {icon: 1, time: 1000});
                                if("{{isset($data['is_type'])}}" && "{{!empty($data['is_type'])}}"){
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.click('{{$data['id']}}');
                                    parent.layer.close(index);
                                }else{
                                    setTimeout(function () {
                                        var index = parent.layer.getFrameIndex(window.name);
                                        parent.$('.btn-refresh').click();
                                        parent.layer.close(index);
                                    }, 500)
                                }
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
@extends('HJGL.admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            <div class="row cl hidden">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="id" name="id" type="text" class="input-text"
                           value="{{ isset($data->id) ? $data->id : '' }}" placeholder="设备id">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>设备编号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="number" name="number" type="text" class="input-text" style="width:350px" value="{{ isset($data->number) ? $data->number : '' }}" placeholder="请输入设备编号">
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="保存设备信息" >
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(function () {
            $("#form-edit").validate({
                rules: {
                    number: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function (form){
                    var index = layer.load(2, {time: 1 * 1000}); //加载
                    var id = $("#id").val();
                    //新建设备时校验
                    if(judgeIsAnyNullStr(id)){
                        //姓名
                        var number = $("#number").val();
                        if(judgeIsAnyNullStr(number)){
                            layer.msg('请输入设备编号！', {icon: 2, time: 1000});
                            return false;
                        }
                    }
                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: "{{ URL::asset('admin/tool/edit')}}",
                        data: {
                            '_token': '{{csrf_token()}}'
                        },
                        success: function (ret) {
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
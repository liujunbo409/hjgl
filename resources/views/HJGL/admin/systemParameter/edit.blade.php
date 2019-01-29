@extends('HJGL.admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            <div class="row cl hidden">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="id" name="id" type="text" class="input-text"
                           value="{{ isset($data->id) ? $data->id : '' }}" placeholder="系统参数id">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>系统参数名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    @if(isset($data->parameter_name))
                        <input id="parameter_name" name="parameter_name" type="text" class="input-text"
                               value="{{$data->parameter_name}}" readonly>
                    @else
                        <input id="parameter_name" name="parameter_name" type="text" class="input-text"
                               value="" placeholder="请输入">
                    @endif
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>系统参数标识：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    @if(isset($data->parameter))
                        <input id="parameter" name="parameter" type="text" class="input-text"
                               value="{{ $data->parameter }}" readonly>
                    @else
                        <input id="parameter" name="parameter" type="text" class="input-text"
                               value="" placeholder="请输入">
                    @endif
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>系统参数值：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="parameter_val" name="parameter_val" type="text" class="input-text"
                           value="{{ isset($data->parameter_val) ? $data->parameter_val : '' }}" placeholder="请输入">
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="保存系统参数">
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
                        url: "{{ URL::asset('admin/systemParameter/edit')}}",
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
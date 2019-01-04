@extends('HJGL.admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            <div class="row cl hidden">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="id" name="id" type="text" class="input-text"
                           value="{{ isset($data->id) ? $data->id : '' }}" placeholder="商家id">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>商家名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="shop_name" name="shop_name" type="text" class="input-text" style="width:350px" value="{{ isset($data->shop_name) ? $data->shop_name : '' }}" placeholder="请输入商家名称">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>商家地址：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="address" name="address" type="text" class="input-text" style="width:350px" value="{{ isset($data->address) ? $data->address : '' }}" placeholder="请输入商家地址">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>管理员姓名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="name" name="name" type="text" class="input-text" style="width:350px" {{$data->id?'disabled':''}} value="{{ isset($data->name) ? $data->name : '' }}" placeholder="请输入管理员姓名">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>手机号(账号)：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="phone" name="phone" type="text" class="input-text" style="width:350px" {{$data->id?'disabled':''}} value="{{ isset($data->phone) ? $data->phone : '' }}" placeholder="请输入设备编号">
                </div>
            </div>
            @if($data->id=="")
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>登录密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="password" name="password" type="text" class="input-text" style="width:350px" value="" placeholder="请输入设备编号">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>确认密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="confirm_password" name="confirm_password" type="text" class="input-text" style="width:350px" value="" placeholder="请输入设备编号">
                </div>
            </div>
            @endif
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
                    shop_name: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    name: {
                        required: true,
                    },
                    phone: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                    confirm_password: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function (form){
                    var index = layer.load(2, {time: 1 * 1000}); //加载
                    var id = $("#id").val();
                    //修改商家时校验
                    if(judgeIsAnyNullStr(id)){
                        //管理员姓名
                        var name = $("#name").val();
                        if(judgeIsAnyNullStr(name)){
                            layer.msg('请输入管理员姓名！', {icon: 2, time: 1000});
                            return false;
                        }
                        //手机号(账号)
                        var phone = $("#phone").val();
                        if(judgeIsAnyNullStr(phone)){
                            layer.msg('请输入手机号！', {icon: 2, time: 1000});
                            return false;
                        }
                        //登录密码
                        var password = $("#password").val();
                        if(judgeIsAnyNullStr(password)){
                            layer.msg('请输入登录密码！', {icon: 2, time: 1000});
                            return false;
                        }
                        //确认密码
                        var confirm_password = $("#confirm_password").val();
                        if(judgeIsAnyNullStr(confirm_password)){
                            layer.msg('请输入确认密码！', {icon: 2, time: 1000});
                            return false;
                        }
                        if(password != confirm_password){
                            layer.msg('两次输入的密码不一致！', {icon: 2, time: 1000});
                            return false;
                        }
                        //加密密码
                        if (password) {
                            $('#password').val(hex_md5(password));
                        }
                        if (confirm_password) {
                            $('#confirm_password').val(hex_md5(confirm_password));
                        }
                    }
                    //商家名称
                    var shop_name = $("#shop_name").val();
                    if(judgeIsAnyNullStr(shop_name)){
                        layer.msg('请输入商家名称！', {icon: 2, time: 1000});
                        return false;
                    }
                    //商家地址
                    var address = $("#address").val();
                    if(judgeIsAnyNullStr(address)){
                        layer.msg('请输入商家地址！', {icon: 2, time: 1000});
                        return false;
                    }

                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: "{{ URL::asset('admin/shop/edit')}}",
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
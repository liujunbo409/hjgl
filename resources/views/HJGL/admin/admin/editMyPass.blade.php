@extends('HJGL.admin.layouts.app')

@section('content')
    <style>
        .tabbar {
            border-bottom: 2px solid #222;
            height: 30px;
        }
        .tab{
            background-color: #e8e8e8;
            cursor: pointer;
            display: inline-block;
            float: left;
            font-weight: bold;
            height: 30px;
            line-height: 30px;
            padding: 0 15px;
        }
        .tabactive{
            background-color: #222;color: #fff;
        }
    </style>
    <div class="page-container">
        <form class="form form-horizontal" id="form-admin-edit">
            {{csrf_field()}}
            <div id="tab-system" class="HuiTab">
                <div class="tabbar">
                    <a href="{{URL::asset('admin/admin/editMySelf') }}" ><span class="tab">基本信息</span></a>
                    <span class="tab tabactive">修改密码</span>
                    <a href="{{URL::asset('admin/admin/editMyTel') }}" ><span class="tab">修改手机号</span></a>
                </div>
                <div class="row cl hidden">
                    <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>id：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input id="id" name="id" type="text" class="input-text" readonly
                               value="{{ isset($admin['id']) ? $admin['id'] : '' }}" placeholder="管理员id">
                    </div>
                </div>
                <div class="tabCon">
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>原密码：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <input id="password" name="password" type="password" class="input-text" style="width: 400px;"
                                   placeholder="请输入原密码">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>新密码：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <input id="new_password" name="new_password" type="password" class="input-text" style="width: 400px;"
                                   placeholder="请输入新密码">
                            <span id="notice" style="color:#ff0000;">密码至少长6位并包含大、小写字母和数字</span>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>确认密码：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <input id="confirm_password" name="confirm_password" type="password" class="input-text" style="width: 400px;"
                                   placeholder="请输入确认密码">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit">
                    <button onClick="layer_close();" class="btn btn-default radius" type="button">取消</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var countdown = 60;
        $(function () {
            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });
            $("#tab-system").Huitab({
                index: 0
            });
            var md5_status = true; //防止二次加密
            $("#form-admin-edit").validate({
                rules: {
                    id: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                    new_password: {
                        required: true,
                    },
                    confirm_password: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: false,
                success: "valid",
                submitHandler: function (form) {
                    if ($('#password').val() != '') {
                        var password = $('#password').val();
                        var new_password = $('#new_password').val();
                        var confirm_password = $('#confirm_password').val();

                        var index = layer.load(2, {time: 10 * 1000}); //加载
                        if (new_password != confirm_password) {
                            layer.msg('密码修改失败，确认密码与新密码不相符', {icon: 2, time: 2000});
                        }
                        else {
                            var re_password=/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,16}/;
                            if(!new_password.match(re_password)){
                                layer.msg('密码至少长6位并包含大、小写字母和数字', {icon: 2, time: 1000});
                                return false;
                            }
                            if (md5_status) {
                                $('#password').val(hex_md5(password));
                                $('#new_password').val(hex_md5(new_password));
                                $('#confirm_password').val(hex_md5(confirm_password));
                                md5_status = false
                            }
                            $('#error').hide();
                            $('.btn-primary').html('<i class="Hui-iconfont">&#xe634;</i> 保存中...')

                            var index = layer.load(2, {time: 10 * 1000}); //加载
                            $(form).ajaxSubmit({
                                type: 'POST',
                                url: "{{ URL::asset('admin/admin/editMyPass')}}",
                                success: function (ret) {
                                    // consoledebug.log(JSON.stringify(ret));
                                    if (ret.result) {
                                        layer.msg(ret.message, {icon: 1, time: 2000});
                                        setTimeout(function () {
                                            window.parent.location = "{{ URL::asset('/admin/loginout')}}"
                                        }, 1000)
                                    } else {
                                        layer.msg(ret.message, {icon: 2, time: 2000});
                                    }
                                    layer.close(index);
                                    $('#password').val('');
                                    $('#new_password').val('');
                                    $('#confirm_password').val('');
                                    re_captcha();
                                    md5_status = true
                                    $('.btn-primary').html('<i class="Hui-iconfont">&#xe632;</i> 保存')
                                },
                                error: function (XmlHttpRequest, textStatus, errorThrown) {
                                    $('#password').val('');
                                    $('#new_password').val('');
                                    $('#confirm_password').val('');
                                    re_captcha();
                                    md5_status = true
                                    layer.msg('保存失败', {icon: 2, time: 2000});
                                    $('.btn-primary').html('<i class="Hui-iconfont">&#xe632;</i> 保存')
                                }
                            });
                        }
                    } else {
                        $('#error').hide();
                        $('.btn-primary').html('<i class="Hui-iconfont">&#xe634;</i> 保存中...')
                        $(form).ajaxSubmit({
                            type: 'POST',
                            url: "{{ URL::asset('admin/admin/editMyPass')}}",
                            success: function (ret) {
                                if (ret.result) {
                                    layer.msg(ret.message, {icon: 1, time: 2000});
                                    setTimeout(function () {
                                        var index = parent.layer.getFrameIndex(window.name);
                                        parent.$('.btn-refresh').click();
                                        parent.location.reload(); // 父页面刷新
                                        parent.layer.close(index);
                                    }, 1000)
                                } else {
                                    layer.msg(ret.message, {icon: 2, time: 2000});
                                }
                                $('.btn-primary').html('<i class="Hui-iconfont">&#xe632;</i> 保存')
                            },
                            error: function (XmlHttpRequest, textStatus, errorThrown) {
                                layer.msg('保存失败', {icon: 2, time: 2000});
                                $('.btn-primary').html('<i class="Hui-iconfont">&#xe632;</i> 保存')
                            }
                        });
                    }

                    layer.close(index);
                }
            });
        });

    </script>
@endsection
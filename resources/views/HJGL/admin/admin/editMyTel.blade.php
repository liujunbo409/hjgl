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
                    <a href="{{URL::asset('admin/admin/editMyPass') }}" ><span class="tab">修改密码</span></a>
                    <span class="tab tabactive">修改手机号</span>
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
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>新手机号：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <input id="phone" name="phone" type="text" class="input-text" style="width: 400px;"
                                   placeholder="请输入新密码">
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>短信验证码：</label>
                        <div class="formControls col-xs-8 col-sm-9" style="display: flex;width:430px;">
                            <input type="text" placeholder="输入短信验证码" name="sm_validate" id="sm_validate" class="input-text" style="width: 400px;"/>
                            <div class="aui-list-item-right aui-btn aui-btn-info"
                                 style="float:right;line-height: 30px;width: 250px;background: #5a98de;color:#fff;text-align: center;cursor: pointer;" id="getVcode"
                                 onclick="sendMsg()">获取短信验证码
                            </div>
                            <div class="aui-list-item-right aui-btn aui-btn-info"
                                 style="float:right;width:250px;display: none;line-height: 30px;text-align: center;background: #9a9898 !important" id="cannotgetVcode">获取短信验证码
                            </div>
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
                    phone: {
                        required: true,
                        number: true,
                        maxlength: 11,
                        minlength: 11
                    },
                    sm_validate: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: false,
                success: "valid",
                submitHandler: function (form) {
                    if ($('#sm_validate').val() != '') {
                        var phone = $('#phone').val();
                        var sm_validate = $('#sm_validate').val();
                        var index = layer.load(2, {time: 10 * 1000}); //加载
                        if (judgeIsAnyNullStr(phone)) {
                            layer.msg('请输入手机号', {icon: 2, time: 2000});
                            return false;
                        } else if (judgeIsAnyNullStr(sm_validate)) {
                            layer.msg('请输入短信验证码', {icon: 2, time: 2000});
                            return false;
                        } else {
                            $('#error').hide();
                            $('.btn-primary').html('<i class="Hui-iconfont">&#xe634;</i> 保存中...')
                            var index = layer.load(2, {time: 10 * 1000}); //加载
                            $(form).ajaxSubmit({
                                type: 'POST',
                                url: "{{ URL::asset('admin/admin/editMyTel')}}",
                                success: function (ret) {
                                    consoledebug.log(JSON.stringify(ret));
                                    if (ret.result) {
                                        layer.msg(ret.message, {icon: 1, time: 2000});
                                        setTimeout(function () {
                                            window.parent.location = "{{ URL::asset('/admin/loginout')}}"
                                        }, 1000)
                                    } else {
                                        layer.msg(ret.message, {icon: 2, time: 2000});
                                    }
                                    layer.close(index);
                                    $('#sm_validate').val('');
                                    re_captcha();
                                    md5_status = true
                                    $('.btn-primary').html('<i class="Hui-iconfont">&#xe632;</i> 保存')
                                },
                                error: function (XmlHttpRequest, textStatus, errorThrown) {
                                    $('#sm_validate').val('');
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
                            url: "{{ URL::asset('admin/admin/editMyTel')}}",
                            success: function (ret) {
                                consoledebug.log(JSON.stringify(ret));
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
                                consoledebug.log("XmlHttpRequest:" + JSON.stringify(XmlHttpRequest));
                                consoledebug.log("textStatus:" + textStatus);
                                consoledebug.log("errorThrown:" + errorThrown);
                                $('.btn-primary').html('<i class="Hui-iconfont">&#xe632;</i> 保存')
                            }
                        });
                    }

                    layer.close(index);
                }
            });
        });

        /*
        * 倒计时
        * */
        function settime() {
            if (countdown == 0) {
                $("#getVcode").css('display','block');
                $("#cannotgetVcode").css('display','none');
                countdown = 60;
            } else {
                $("#getVcode").css('display','none');
                $("#cannotgetVcode").css('display','block');
                $("#cannotgetVcode").html("重新发送(" + countdown + ")");
                countdown--;
                setTimeout(function() {
                    settime()
                },1000)
            }
        }
        //进行表单校验
        function sendMsg() {
            consoledebug.log("sendMsg");
            var id = $("#id").val();
            //手机号是否为空
            if (judgeIsAnyNullStr(id)) {
                layer.msg('账号不能为空！', {icon: 2, time: 1000});
                return false;
            }
            var  phone= $("#phone").val();
            if (phone == null || phone.length == 0 || judgeIsNullStr(phone)) {
                layer.msg('新手机号不能为空！', {icon: 2, time: 1000});
                return false;
            }
            sendMassage('{{ URL::asset('admin/admin/validateNewPhone')}}', {phone: phone},
                function (res) {
                    consoledebug.log("发送验证码接口的返回为", res);
                    toast_hide();
                    if (res.result) {
                        layer.msg('发送中...', {icon: 1, time: 1000});
                        settime();
                    }
                    else{
                        layer.msg(res.message, {icon: 2, time: 1000});
                        return false;
                    }
                })
        }
    </script>
@endsection
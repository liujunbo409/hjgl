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
                    <span class="tab tabactive">基本信息</span>
                    <a href="{{URL::asset('admin/admin/editMyPass') }}" ><span class="tab">修改密码</span></a>
                    <a href="{{URL::asset('admin/admin/editMyTel') }}" ><span class="tab">修改手机号</span></a>
                </div>
                <div class="row cl hidden">
                    <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>id：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input id="id" name="id" type="text" class="input-text" readonly style="width: 400px;"
                               value="{{ isset($admin['id']) ? $admin['id'] : '' }}" placeholder="管理员id">
                    </div>
                </div>
                <div class="tabCon">
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>管理员账号：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <input  type="text" class="input-text" style="width: 400px;background: #f3f3f3"
                                   value="{{ isset($admin['phone']) ? $admin['phone'] : '' }}" placeholder="请输入管理员账号" readonly>
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>管理员姓名：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <input id="name" name="name"  type="text" class="input-text" style="width: 400px;background: #f3f3f3"
                                   value="{{ isset($admin['name']) ? $admin['name'] : '' }}" placeholder="请输入管理员姓名" >
                        </div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>管理员角色：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box" style="width:400px;background: #f3f3f3">
                    <select  class="select" disabled style="background: #f3f3f3">
                        @foreach(\App\Components\Utils::admin_role as $key=>$value)
                            <option value="{{$key}}" {{$admin['role'] == $key? "selected":""}}>{{$value}}</option>
                        @endforeach
                    </select>
                    </span>
                        </div>
                    </div>
                    {{--<div class="row cl">--}}
                        {{--<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>管理员昵称：</label>--}}
                        {{--<div class="formControls col-xs-8 col-sm-9">--}}
                            {{--<input id="nick_name" name="nick_name" type="text" class="input-text" style="width: 400px;"--}}
                                   {{--value="{{ isset($admin['nickname']) ? $admin['nickname'] : '' }}" placeholder="请输入管理员昵称">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--2018.2.22 谢晋 编辑--}}
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2">头像：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <input id="avatar" name="avatar" type="text" class="input-text" style="width: 400px;" readonly
                                   value="{{ isset($admin->avatar) ? $admin->avatar : '' }}" placeholder="请输入头像网络连接地址">
                            <div id="container" class="margin-top-10">
                                <img id="pickfiles"
                                     src="{{ isset($admin->avatar) ? $admin->avatar : URL::asset('/img/default_headicon.png') }}"
                                     style="width: 120px;height: 120px;border-radius: 50%;">
                            </div>
                            <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传200*200尺寸图片</div>
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
            //获取七牛token
            initQNUploader();

            $("#form-admin-edit").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    avatar: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: false,
                success: "valid",
                submitHandler: function (form) {
                    if ($('#nick_name').val() != '') {
                        var index = layer.load(2, {time: 10 * 1000}); //加载
                        $('#error').hide();
                        $('.btn-primary').html('<i class="Hui-iconfont">&#xe634;</i> 保存中...')

                            var index = layer.load(2, {time: 10 * 1000}); //加载

                            $(form).ajaxSubmit({
                                type: 'POST',
                                url: "{{ URL::asset('admin/admin/editMySelf')}}",
                                success: function (ret) {
                                    consoledebug.log(JSON.stringify(ret));
                                    if (ret.result) {
                                        layer.msg(ret.message, {icon: 1, time: 2000});
                                        setTimeout(function () {
                                            // var index = parent.layer.getFrameIndex(window.name);
                                            // parent.$('.btn-refresh').click();
                                            // parent.layer.close(index);
                                            {{--window.parent.location = "{{ URL::asset('/admin/loginout')}}"--}}
                                        }, 1000)
                                    } else {
                                        layer.msg(ret.message, {icon: 2, time: 2000});
                                    }
                                    layer.close(index);
                                    md5_status = true
                                    $('.btn-primary').html('<i class="Hui-iconfont">&#xe632;</i> 保存')
                                },
                                error: function (XmlHttpRequest, textStatus, errorThrown) {
                                    md5_status = true
                                    layer.msg('保存失败', {icon: 2, time: 2000});
                                    consoledebug.log("XmlHttpRequest:" + JSON.stringify(XmlHttpRequest));
                                    consoledebug.log("textStatus:" + textStatus);
                                    consoledebug.log("errorThrown:" + errorThrown);
                                    $('.btn-primary').html('<i class="Hui-iconfont">&#xe632;</i> 保存')
                                }
                            });
                    }
                    else {
                        layer.msg('请输入昵称', {icon: 2, time: 2000});
                    }
                    // layer.close(index);
                }
            });
        });

        //初始化七牛上传模块
        function initQNUploader() {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: 'pickfiles',         // 上传选择的点选按钮，必需
                container: 'container',//上传按钮的上级元素ID
                // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
                // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
                // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
                uptoken: "{{$upload_token}}", // uptoken是上传凭证，由其他程序生成
                // uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
                // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
                //    // do something
                //    return uptoken;
                // },
                get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                // downtoken_url: '/downtoken',
                // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
                unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
                // save_key: true,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
                domain: 'http://twst.isart.me/',     // bucket域名，下载资源时用到，必需
                max_file_size: '100mb',             // 最大文件体积限制
                flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                dragdrop: true,                     // 开启可拖曳上传
                drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                //x_vars : {
                //    查看自定义变量
                //    'time' : function(up,file) {
                //        var time = (new Date()).getTime();
                // do something with 'time'
                //        return time;
                //    },
                //    'size' : function(up,file) {
                //        var size = file.size;
                // do something with 'size'
                //        return size;
                //    }
                //},
                init: {
                    'FilesAdded': function (up, files) {
                        plupload.each(files, function (file) {
                            // 文件添加进队列后，处理相关的事情
//                                            alert(alert(JSON.stringify(file)));
                        });
                    },
                    'BeforeUpload': function (up, file) {
                        // 每个文件上传前，处理相关的事情
//                        consoledebug.log("BeforeUpload up:" + up + " file:" + JSON.stringify(file));
                    },
                    'UploadProgress': function (up, file) {
                        // 每个文件上传时，处理相关的事情
//                        consoledebug.log("UploadProgress up:" + up + " file:" + JSON.stringify(file));
                    },
                    'FileUploaded': function (up, file, info) {
                        // 每个文件上传成功后，处理相关的事情
                        // 其中info是文件上传成功后，服务端返回的json，形式如：
                        // {
                        //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                        //    "key": "gogopher.jpg"
                        //  }
//                        consoledebug.log(JSON.stringify(info));
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        //获取上传成功后的文件的Url
                        var sourceLink = domain + res.key;
                        $("#avatar").val(sourceLink);
                        $("#pickfiles").attr('src', qiniuUrlTool(sourceLink, "head_icon"));
//                        consoledebug.log($("#pickfiles").attr('src'));
                    },
                    'Error': function (up, err, errTip) {
                        //上传出错时，处理相关的事情
                        consoledebug.log(err + errTip);
                    },
                    'UploadComplete': function () {
                        //队列文件处理完毕后，处理相关的事情
                    },
                    'Key': function (up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在unique_names: false，save_key: false时才生效

                        var key = "";
                        // do something with key here
                        return key
                    }
                }
            });
        }
    </script>
@endsection
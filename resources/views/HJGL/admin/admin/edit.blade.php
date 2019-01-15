@extends('HJGL.admin.layouts.app')

@section('content')

    <div class="page-container">
        <form class="form form-horizontal" id="form-edit">
            {{csrf_field()}}
            <div class="row cl hidden">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="id" name="id" type="text" class="input-text"
                           value="{{ isset($data->id) ? $data->id : '' }}" placeholder="管理员id">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>角色：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box" style="width:350px">
                    <select id="role" name="role" class="select">
                        @foreach(\App\Components\Utils::admin_role as $key=>$value)
                            @if(isset($data->role))
                                @if($key != 0)
                                    <option value="{{$key}}" {{$data->role == $key? "selected":""}}>{{$value}}</option>
                                @endif
                            @else
                                @if($key != 0)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>管理员姓名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="name" name="name" type="text" class="input-text" style="width:350px" {{isset($data->id)?'disabled':''}}
                           value="{{ isset($data->name) ? $data->name : '' }}" placeholder="请输入管理员姓名">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>联系电话：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="phone" name="phone" type="text" class="input-text" style="width:350px;"
                           value="{{ isset($data->phone) ? $data->phone : '' }}" {{isset($data->id)?'disabled':''}} placeholder="请输入联系电话">
                </div>
            </div>
            @if(!isset($data->id) || $data->id=="")
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>初始密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="password" name="password" type="password" class="input-text" style="width: 400px;" value="{{isset($data->id)?'disabled':''}}"
                           placeholder="请输入初始密码">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>确认密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="confirm_password" name="confirm_password" type="password" class="input-text" style="width: 400px;"
                           placeholder="请输入确认密码">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">头像：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input id="avatar" name="avatar" type="text" class="input-text" style="width:350px"  readonly
                           value="{{ isset($data->avatar) ? $data->avatar : URL::asset('/img/default_headicon.png')}}"
                           placeholder="请输入头像网络连接地址">
                    <div id="container" class="margin-top-10">
                        <img id="pickfiles"
                             src="{{ isset($data->avatar) ? $data->avatar : URL::asset('/img/default_headicon.png') }}"
                             style="width: 120px;height: 120px;border-radius: 50%;">
                    </div>
                    <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传200*200尺寸图片</div>
                </div>
            </div>
            @endif
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="保存管理员" >
                   <!-- <button onClick="layer_close();" class="btn btn-default radius" type="button">取消</button>-->
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(function () {
            //获取七牛token
            initQNUploader();
            $("#form-edit").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    phone: {
                        required: true,
                        isPhone: true,
                    },
                    avatar: {
                        required: true,
                    }
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function (form){
                    var index = layer.load(2, {time: 1 * 1000}); //加载
                    //角色
                    var role = $("#role").val();
                    if(judgeIsAnyNullStr(role)){
                        layer.msg('请选择管理员角色！', {icon: 2, time: 1000});
                        return false;
                    }
                    var id = $("#id").val();
                    //新建管理员时校验密码相关
                    if(judgeIsAnyNullStr(id)){
                        //姓名
                        var name = $("#name").val();
                        if(judgeIsAnyNullStr(name)){
                            layer.msg('请输入管理员姓名！', {icon: 2, time: 1000});
                            return false;
                        }
                        //电话
                        var phone = $("#phone").val();
                        if(judgeIsAnyNullStr(phone)){
                            layer.msg('请输入联系方式！', {icon: 2, time: 1000});
                            return false;
                        }
                        //密码
                        var password = $("#password").val();
                        if(judgeIsAnyNullStr(password)){
                            layer.msg('请输入密码！', {icon: 2, time: 1000});
                            return false;
                        }
                        //确认密码
                        var confirm_password = $("#confirm_password").val();
                        if(judgeIsAnyNullStr(confirm_password)){
                            layer.msg('请输入确认密码！', {icon: 2, time: 1000});
                            return false;
                        }
                        var re_password=/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,16}/;
                        if(!password.match(re_password)){
                            layer.msg('密码至少长6位并包含大、小写字母和数字', {icon: 2, time: 1000});
                            return false;
                        }
                        if (password != confirm_password) {
                            layer.msg('确认密码与初始密码不相符', {icon: 2, time: 2000});
                            return false;
                        }
//                    加密密码
                        var password = $('#password').val();
                        if (password) {
                            $('#password').val(hex_md5(password));
                        }
                    }
                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: "{{ URL::asset('admin/admin/edit')}}",
                        data: {
                            '_token': '{{csrf_token()}}'
                        },
                        success: function (ret) {
                            consoledebug.log(JSON.stringify(ret));
                            if (ret.result) {
                                layer.msg('保存成功', {icon: 1, time: 1000});
                                setTimeout(function () {
                                    $('#password').val('');
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.$('.btn-refresh').click();
                                    parent.layer.close(index);
                                }, 500)
                            } else {
                                layer.msg(ret.message, {icon: 2, time: 1000});
                                $('#password').val('');
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
                        // 若想在前端对每个   文件的key进行个性化处理，可以配置该函数
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
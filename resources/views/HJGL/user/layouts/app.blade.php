<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {{--首页结束--}}
    <link rel="Bookmark" href="{{ URL::asset('img/favor.ico') }}">
    <link rel="Shortcut Icon" href="{{ URL::asset('img/favor.ico') }}"/>

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/dist/lib/HUI-master/css/hui.css')}}"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('/css/iconfont/iconfont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/common.css') }}"/>
</head>
<body>

@yield('content')

</body>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery/1.9.1/jquery.min.js') }}"></script>
<!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery.contextmenu/jquery.contextmenu.r2.js') }}"></script>
<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="{{ URL::asset('dist/lib/My97DatePicker/4.8/WdatePicker.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dist/lib/datatables/1.10.0/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dist/lib/laypage/1.2/laypage.js') }}"></script>
{{--doT、md5、七牛等相关--}}
<script type="text/javascript" src="{{ URL::asset('/js/doT.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/md5.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/qiniu.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/plupload/plupload.full.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/plupload/moxie.js') }}"></script>
<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery.validation/1.14.0/jquery.validate.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery.validation/1.14.0/validate-methods.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dist/lib/jquery.validation/1.14.0/messages_zh.js') }}"></script>

{{--首页信息--}}
<script type="text/javascript" src="{{ URL::asset('dist/lib/hcharts/Highcharts/5.0.6/js/highcharts.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('dist/lib/hcharts/Highcharts/5.0.6/js/modules/exporting.js') }}"></script>

{{--common.js--}}
<script type="text/javascript" src="{{ URL::asset('/js/aui/aui-dialog.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/aui/aui-toast.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/aui/aui-popup.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/common.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/hjgl/apiTool.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/dist/lib/HUI-master/js/hui.js') }}" ></script>
<script type="text/javascript" src="{{ URL::asset('/js/hjgl/echarts.common.min.js') }}"></script>
{{--layer--}}
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/dist/lib/layer/2.4/skin/layer.css')}}"/>
<script type="text/javascript" src="{{ URL::asset('/dist/lib/layer/2.4/layer.js') }}"></script>
</html>

@yield('script')
@extends('HJGL.admin.layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ URL::asset('z-tree/css/demo.css')}}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('z-tree/css/zTreeStyle/zTreeStyle.css')}}" type="text/css">
<style type="text/css">
    .addRoot{
        background-color: #5a98de;
        width: 100px;
        margin-top: 380px;
        margin-left: 40px;
        height: 30px;
        text-align: center;
        border-radius: 5px;
        color: #FFFFFF;
        float: initial;
        line-height: 30px;
    }
    .ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}.ztree li span.demoIcon{padding:0 2px 0 10px;}
    .ztree li span.button.iconup{margin:0; background: url({{ URL::asset('z-tree/css/zTreeStyle/img/diy/up.png')}}) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.icondown{margin:0; background: url({{ URL::asset('z-tree/css/zTreeStyle/img/diy/down.png')}}) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.iconmove{margin:0; background: url({{ URL::asset('z-tree/css/zTreeStyle/img/diy/move.png')}}) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}

</style>
@section('content')
    <div  style="width:20%;float:left">
        <ul id="treeDemo" class="ztree" style="width:100%;float:left"></ul>
        <a id="addParent" href="#" title="添加根分类" onclick="return false;"> <div class="addRoot">添加根分类</div></a>
    </div>
    <input id="refurbish" value="" type="hidden" onclick="click(open_id)">
    <iframe ID="testIframe" Name="testIframe" FRAMEBORDER=0 SCROLLING=YES width=75% height=AUTO
            SRC="{{ URL::asset('/admin/article/articleList')}}" style="float:right;min-height: 500px;"></iframe>
@endsection

@section('script')
    <script type="text/javascript" src="{{ URL::asset('z-tree/js/jquery.ztree.core.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('z-tree/js/jquery.ztree.excheck.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('z-tree/js/jquery.ztree.exedit.js')}}"></script>
    <SCRIPT type="text/javascript">
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        demoIframe = $("#testIframe");//嵌套页面iframe
        var setting = {
            view: {
                selectedMulti: false
            },
            // edit: {
            //     enable: true,
            //     editNameSelectAll: true,
            // },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                beforeDrag: beforeDrag,
            }
        };

        var zNodes ={!! $datas !!};
        function beforeDrag(treeId, treeNodes) {
            return false;
        }
        $(document).ready(function () {
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        });

        function click(open_id,type) {
            window.location.href = "{{ URL::asset('admin/article/index?open_id=')}}" + open_id +"";
        }
        function openMulu(id, pId) {
            demoIframe.attr('src', "{{ URL::asset('/admin/article/articleList')}}?id=" + id + "&parent_id=" + pId + "");
        }
    </SCRIPT>

@endsection



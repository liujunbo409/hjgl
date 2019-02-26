@extends('HJGL.admin.layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ URL::asset('z-tree/css/demo.css')}}" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('z-tree/css/zTreeStyle/zTreeStyle.css')}}" type="text/css">
<style type="text/css">


    .addRoot{
        background-color: #5a98de;
        width: 100px;
        margin-left: 20px;
        height: 30px;
        text-align: center;
        border-radius: 5px;
        color: #FFFFFF;
        float: initial;
        line-height: 30px;
        margin-top:10px;
    }

    .ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}.ztree li span.demoIcon{padding:0 2px 0 10px;}
    .ztree li span.button.iconup{margin:0; background: url({{ URL::asset('z-tree/css/zTreeStyle/img/diy/up.png')}}) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.icondown{margin:0; background: url({{ URL::asset('z-tree/css/zTreeStyle/img/diy/down.png')}}) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}
    .ztree li span.button.iconmove{margin:0; background: url({{ URL::asset('z-tree/css/zTreeStyle/img/diy/move.png')}}) no-repeat scroll 0 0 transparent; vertical-align:top; *vertical-align:middle}

</style>
@section('content')
    <div class="zTreeDemoBackground left">
        <ul id="treeDemo" class="ztree"></ul>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ URL::asset('z-tree/js/jquery.ztree.core.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('z-tree/js/jquery.ztree.excheck.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('z-tree/js/jquery.ztree.exedit.js')}}"></script>
    <SCRIPT type="text/javascript">
        var setting = {
            data: {
                simpleData: {
                    enable: true
                }
            }
        };
        var zNodes ={!! $datas !!};
        $(document).ready(function(){
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        });
        function moveMulu(article_id, move_id,old_type_id) {
            $.ajax({
                type: 'POST',
                url: "{{URL::asset('admin/article/moveArticleSave')}}",
                dataType: 'json',
                data: {
                    article_id: article_id,
                    move_id: move_id,
                    old_type_id: old_type_id,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data, sta) {
                    if (data.code == 200) {
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.click(old_type_id);
                        parent.layer.close(index);
                    } else {
                        layer.alert(data.message, function () {
                        });
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }

    </SCRIPT>

@endsection



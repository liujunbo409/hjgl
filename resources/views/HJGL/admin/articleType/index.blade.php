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
    <div  style="width:30%;float:left">
        <ul id="treeDemo" class="ztree" style="width:100%;float:left"></ul>
        <a id="addParent" href="#" title="添加根分类" onclick="return false;"> <div class="addRoot">添加根分类</div></a>
    </div>
    <input id="refurbish" value="" type="hidden" onclick="click(open_id)">
    <iframe ID="testIframe" Name="testIframe" FRAMEBORDER=0 SCROLLING=NO width=65% height=AUTO
            SRC="{{ URL::asset('/admin/articleType/edit')}}" style="float:right;min-height: 500px;"></iframe>
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
                addHoverDom: addHoverDom,
                removeHoverDom: removeHoverDom,
                selectedMulti: false
            },
            edit: {
                enable: true,
                editNameSelectAll: true,
                showRemoveBtn: showRemoveBtn,
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                beforeDrag: beforeDrag,
                beforeEditName: beforeEditName,
                beforeRemove: beforeRemove,
                beforeRename: beforeRename,
                onRemove: onRemove,
                onRename: onRename
            }
        };

        var zNodes ={!! $datas !!};
        var log, className = "dark";
        function beforeDrag(treeId, treeNodes) {
            return false;
        }
        function beforeEditName(treeId, treeNode) {
            className = (className === "dark" ? "":"dark");
            showLog("[ "+getTime()+" beforeEditName ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
            var zTree = $.fn.zTree.getZTreeObj("treeDemo");

            zTree.selectNode(treeNode);
            setTimeout(function() {
                if (confirm("进入节点 -- " + treeNode.name + " 的编辑状态吗？")) {
                    setTimeout(function() {
                        zTree.editName(treeNode);
                    }, 0);
                }
            }, 0);
            return false;
        }
        function beforeRemove(treeId, treeNode) {
            className = (className === "dark" ? "":"dark");
            showLog("[ "+getTime()+" beforeRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
            var zTree = $.fn.zTree.getZTreeObj("treeDemo");
            zTree.selectNode(treeNode);
            return confirm("确认删除 节点 -- " + treeNode.name + " 吗？");
        }
        function onRemove(e, treeId, treeNode) {
            var param = {
                id:treeNode.id,
                _token: "{{ csrf_token() }}"
            };
            articleType_delete('{{URL::asset('')}}', param, function (ret) {});
            showLog("[ "+getTime()+" onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
        }
        function beforeRename(treeId, treeNode, newName, isCancel) {
            className = (className === "dark" ? "":"dark");
            showLog((isCancel ? "<span style='color:red'>":"") + "[ "+getTime()+" beforeRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>":""));
            if (newName.length == 0) {
                setTimeout(function() {
                    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                    zTree.cancelEditName();
                    alert("节点名称不能为空.");
                }, 0);
                return false;
            }
            return true;
        }
        function onRename(e, treeId, treeNode, isCancel) {
            $.ajax({
                type: 'POST',
                url: "{{URL::asset('admin/articleType/edit')}}",
                dataType: 'json',
                data: {
                    id: treeNode.id,
                    name:treeNode.name,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data, sta) {
                    if (data.code == 200) {
                    } else {
                        layer.alert('失败', function () {
                        });
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
            showLog((isCancel ? "<span style='color:red'>":"") + "[ "+getTime()+" onRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>":""));
        }
        function showRemoveBtn(treeId, treeNode) {
            return !treeNode.isParent;
        }
        function showLog(str) {
            if (!log) log = $("#log");
            log.append("<li class='"+className+"'>"+str+"</li>");
            if(log.children("li").length > 8) {
                log.get(0).removeChild(log.children("li")[0]);
            }
        }
        function getTime() {
            var now= new Date(),
                h=now.getHours(),
                m=now.getMinutes(),
                s=now.getSeconds(),
                ms=now.getMilliseconds();
            return (h+":"+m+":"+s+ " " +ms);
        }
        var newCount = 1;
        function addHoverDom(treeId, treeNode) {
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
            //上移步数输入框
            var selStr = "<input type='text' id='diyText_" +treeNode.id+ "' style='width: 25px;text-align: right' maxlength='2' onclick='stoponclick()'></input>";
            sObj.after(selStr);
            var sel = $("#diyText_"+treeNode.id);
            //添加按钮
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                + "' title='add node' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            //添加上移箭头
            var upStr = "<span class='button iconup' id='diyBtnUp_" +treeNode.id+ "' title='"+treeNode.name+"' onfocus='this.blur();' ></span>";
            sObj.after(upStr);
            var downStr = "<span class='button icondown' id='diyBtnDown_" +treeNode.id+ "' title='"+treeNode.name+"' onfocus='this.blur();' ></span>";
            sObj.after(downStr);
            var moveStr = "<span class='button iconmove' id='diyBtnMove_" +treeNode.id+ "' title='"+treeNode.name+"' onfocus='this.blur();' ></span>";
            sObj.after(moveStr);
            var move = $("#diyBtnMove_"+treeNode.id);
            if (move) move.bind("click", function(){
                moveList("选择基础文章分类","{{URL::asset('admin/articleType/moveTypeList')}}?id="+treeNode.id+"&parent_id="+treeNode.pId);
            });
            var moveup = $("#diyBtnUp_"+treeNode.id);
            if (moveup) moveup.bind("click", function(){
                var upsteps = sel.val();
                up(treeNode.id, upsteps,treeNode.pId);
            });
            var movedown = $("#diyBtnDown_"+treeNode.id);
            if (movedown) movedown.bind("click", function(){
                var downsteps = sel.val();
                down(treeNode.id, downsteps,treeNode.pId);
            });
            var btn = $("#addBtn_"+treeNode.tId);
            if (btn) btn.bind("click", function(){
                var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount)});
                $.ajax({
                    type: 'POST',
                    url: "{{URL::asset('admin/articleType/edit')}}",
                    dataType: 'json',
                    data: {
                        id: '',
                        parent_id: treeNode.id,
                        name: "new node" + (newCount++),
                        '_token': '{{csrf_token()}}'
                    },
                    success: function (data, sta) {
                        if (data.code == 200) {
                            window.location.href = "{{ URL::asset('admin/articleType/index')}}?open_id="+treeNode.id;
                        } else {
                            layer.alert('失败', function () {
                            });
                        }
                    },
                    error: function (data) {
                        console.log(data)
                    }
                })
                return false;
            });
        };
        function removeHoverDom(treeId, treeNode) {
            $("#addBtn_"+treeNode.tId).unbind().remove();
            $("#diyBtnUp_"+treeNode.id).unbind().remove();
            $("#diyBtnDown_"+treeNode.id).unbind().remove();
            $("#diyText_"+treeNode.id).unbind().remove();
            $("#diyBtnMove_"+treeNode.id).unbind().remove();
        };
        function selectAll() {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo");
            zTree.setting.edit.editNameSelectAll =  $("#selectAll").attr("checked");
        }
        var newCount = 1;
        function add(e) {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
                isParent = e.data.isParent,
                nodes = zTree.getSelectedNodes(),
                treeNode = nodes[0];
            treeNode = zTree.addNodes(null, {
                id: (100 + newCount),
                pId: 0,
                isParent: isParent,
                name: "new node" + (newCount)
            });
            $.ajax({
                type: 'POST',
                url: "{{URL::asset('admin/articleType/addTypeFather')}}",
                dataType: 'json',
                data: {
                    name: "new node" + (newCount++),
                    '_token': '{{csrf_token()}}'
                },
                success: function (data, sta) {
                    layer.alert('失败', function () {
                    });
                    if (data.code == 200) {
                        window.location.href = "{{ URL::asset('admin/articleType/index')}}";
                    } else {
                        layer.alert('失败', function () {
                        });
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        };
        $(document).ready(function () {
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            $("#addParent").bind("click", {isParent: true }, add);
        });
        function stoponclick() {
            var ev = window.event || arguments.callee.caller.arguments[0];

            if (window.event) ev.cancelBubble = true;
            else {
                ev.stopPropagation();
            }
        };
        function openMulu(id, pId) {
            demoIframe.attr('src', "{{ URL::asset('/admin/articleType/edit')}}?id=" + id + "&parent_id=" + pId + "");
        }
        function up(id, seq,open_id) {
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('admin/articleType/upType')}}",
                dataType: 'json',
                data: {
                    id: id,
                    seq: seq,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data, sta) {
                    if (data.code == 200) {
                        window.location.href = "{{ URL::asset('admin/articleType/index?open_id=')}}"+open_id+"";
                    } else {
                        layer.alert('失败', function () {
                        });
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }

        function down(id, seq,open_id) {
            $.ajax({
                type: 'GET',
                url: "{{URL::asset('admin/articleType/downType')}}",
                dataType: 'json',
                data: {
                    id: id,
                    seq: seq,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data, sta) {
                    if (data.code == 200) {
                        window.location.href = "{{ URL::asset('admin/articleType/index?open_id=')}}"+open_id+"";
                    } else {
                        layer.alert('失败', function () {
                        });
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }

        function click(open_id,type) {
            window.location.href = "{{ URL::asset('admin/articleType/index?open_id=')}}" + open_id +"";
        }

        /*移动*/
        function moveList(title, url, id) {

            var index = layer.open({
                type: 2,
                area: ['700px', '500px'],
                fixed: false,
                maxmin: true,
                title: title,
                content: url
            });
        }
    </SCRIPT>

@endsection



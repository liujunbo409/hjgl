@extends('HJGL.admin.layouts.app')

@section('content')

    <header class="navbar-wrapper">
        <div class="navbar navbar-fixed-top">
            <div class="container-fluid cl"><a class="logo navbar-logo f-l mr-10 hidden-xs"
                                               href="/aboutHui.shtml">环境监测管理系统</a>
                <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="#"></a>
                <span class="logo navbar-slogan f-l mr-10 hidden-xs">v1.0.0</span>
                <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
                <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                    <ul class="cl">
                        @foreach(\App\Components\Utils::admin_role as $key=>$index)
                            @if($admin['role']==$key)
                                <li>{{$index}}</li>
                            @endif
                        @endforeach
                        {{--<li>超级管理员</li>--}}
                        <li class="dropDown dropDown_hover">
                            <a href="#" class="dropDown_A">{{$admin['name']}}<i class="Hui-iconfont">&#xe6d5;</i></a>
                            <ul class="dropDown-menu menu radius box-shadow">
                                <li><a href="javascript:;" onClick="mysqlf_edit('修改个人信息','{{ route('editMySelf') }}')">个人信息</a>
                                </li>
                                {{--<li><a href="#">切换账户</a></li>--}}
                                <li><a href="{{ URL::asset('/admin/loginout') }}">退出</a></li>
                            </ul>
                        </li>
                        {{--<li id="Hui-msg">--}}
                        {{--<a href="#" title="消息">--}}
                        {{--<span class="badge badge-danger">1</span>--}}
                        {{--<i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        <li id="Hui-skin" class="dropDown right dropDown_hover">
                            <a href="javascript:;" class="dropDown_A" title="换肤">
                                <i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i>
                            </a>
                            <ul class="dropDown-menu menu radius box-shadow">
                                <li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
                                <li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
                                <li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
                                <li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
                                <li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
                                <li><a href="javascript:;" data-val="orange" title="橙色">橙色</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <aside class="Hui-aside">
        <div class="menu_dropdown bk_2">
            {{--超级管理员有管理员管理权限--}}
            @if($admin['role']==0)
                <dl id="menu-article">
                    <dt><i class="icon iconfont icon-erji-guanliyuanshouye"></i>管理员管理<i
                                class="Hui-iconfont menu_dropdown-arrow">
                            &#xe6d5;</i>
                    </dt>
                    <dd>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/admin/index') }}" data-title="管理员管理"
                                   href="javascript:void(0)" target="'_blank">管理员列表</a></li>
                        </ul>
                    </dd>
                </dl>
                <dl id="menu-article">
                    <dt><i class="icon iconfont icon-wenzhangfenxiang"></i>设备管理<i
                                class="Hui-iconfont menu_dropdown-arrow">
                            &#xe6d5;</i>
                    </dt>
                    <dd>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/tool/index') }}" data-title="设备列表"
                                   href="javascript:void(0)" target="'_blank">设备列表</a></li>
                        </ul>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/toolDispose/index') }}" data-title="设备处理列表"
                                   href="javascript:void(0)" target="'_blank">设备处理列表</a></li>
                        </ul>
                    </dd>
                </dl>
                <dl id="menu-article">
                    <dt><i class="icon iconfont icon-yonghu1"></i>商家管理<i
                                class="Hui-iconfont menu_dropdown-arrow">
                            &#xe6d5;</i>
                    </dt>
                    <dd>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/shop/index') }}" data-title="商家管理"
                                   href="javascript:void(0)" target="'_blank">商家列表</a></li>
                        </ul>
                    </dd>
                </dl>
                <dl id="menu-article">
                    <dt><i class="icon iconfont icon-yonghu1"></i>文章管理<i
                                class="Hui-iconfont menu_dropdown-arrow">
                            &#xe6d5;</i>
                    </dt>
                    <dd>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/article/index') }}" data-title="文章管理"
                                   href="javascript:void(0)" target="'_blank">文章管理</a></li>
                        </ul>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/articleType/index') }}" data-title="文章目录"
                                   href="javascript:void(0)" target="'_blank">文章目录</a></li>
                        </ul>
                    </dd>
                </dl>
                <dl id="menu-article">
                    <dt><i class="icon iconfont icon-yonghu1"></i>系统管理<i
                                class="Hui-iconfont menu_dropdown-arrow">
                            &#xe6d5;</i>
                    </dt>
                    <dd>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/systemParameter/index') }}" data-title="系统管理"
                                   href="javascript:void(0)" target="'_blank">系统参数列表</a></li>
                        </ul>
                    </dd>
                </dl>
                <dl id="menu-article">
                    <dt><i class="icon iconfont icon-yonghu1"></i>订单管理<i
                                class="Hui-iconfont menu_dropdown-arrow">
                            &#xe6d5;</i>
                    </dt>
                    <dd>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/userOrder/index') }}" data-title="订单管理"
                                   href="javascript:void(0)" target="'_blank">订单管理</a></li>
                        </ul>
                    </dd>
                </dl>
                <dl id="menu-article">
                    <dt><i class="icon iconfont icon-yonghu1"></i>账目管理<i
                                class="Hui-iconfont menu_dropdown-arrow">
                            &#xe6d5;</i>
                    </dt>
                    <dd>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/userAccount/rent') }}" data-title="租金管理"
                                   href="javascript:void(0)" target="'_blank">租金管理</a></li>
                        </ul>
                        <ul>
                            <li><a data-href="{{ URL::asset('admin/userAccount/deposit') }}" data-title="押金管理"
                                   href="javascript:void(0)" target="'_blank">押金管理</a></li>
                        </ul>
                    </dd>
                </dl>
            @endif
        </div>
    </aside>
    <div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a>
    </div>
    <section class="Hui-article-box">
        <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
            <div class="Hui-tabNav-wp">
                <ul id="min_title_list" class="acrossTab cl">
                    <li class="active">
                        <span title="业务概览" data-href="{{ URL::asset('admin/index/index') }}">业务概览</span>
                        <em></em></li>
                </ul>
            </div>
            <div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S"
                                                      href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a
                        id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i
                            class="Hui-iconfont">
                        &#xe6d7;</i></a></div>
        </div>
        <div id="iframe_box" class="Hui-article">
            <div class="show_iframe">
                <div style="display:none" class="loading"></div>
                <iframe scrolling="yes" frameborder="0" src="{{ URL::asset('admin/index/index') }}"></iframe>
            </div>
        </div>
    </section>

    <div class="contextMenu" id="Huiadminmenu">
        <ul>
            <li id="closethis">关闭当前</li>
            <li id="closeall">关闭全部</li>
        </ul>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(function () {

        });

        /*个人信息-修改*/
        function mysqlf_edit(title, url) {
            var index = layer.open({
                type: 2,
                title: title,
                content: url
            });
            layer.full(index);
        }
    </script>
@endsection



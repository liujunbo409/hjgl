@extends('HJGL.admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 设备管理 <span
                class="c-gray en">&gt;</span> 设备列表 <span
                class="c-gray en">&gt;</span> 设备详情<a class="btn btn-success radius r btn-refresh"
                                                     style="line-height:1.6em;margin-top:3px"
                                                     href="javascript:location.replace(location.href);" title="刷新"
                                                     onclick="location.replace('{{URL::asset('admin/tool/info')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div style="width:100%;height:120px;">
            <div style="float:left;margin-left:10px;width:600px;">
                <span class="l"><strong>{{$tool->number}}</strong></span><br/>
                <div class="l" style="width:300px;">
                    <br/>
                    <span class="l">商家名称: {{$tool->shop_id}}</span><br/><br/>
                    <span class="l">加入时间: {{$tool->create_time}}</span>
                </div>
                <div class="r"  style="width:300px;">
                    <br/>
                    <span class="l">设备状态: {{$tool->status}}</span>
                </div>
                <br/>
            </div>
            <div style="float:right;margin-right:10px;width:300px;">
                <span class="r"><a>检测结果</a></span><br/>
                <div class="r" style="width:150px;height:100px;background-color: #75aa94">
                    <br/>
                    <span class="r">检测时长</span><br/>
                    <br/>
                    <span class="r">xx小时</span><br/>
                </div>
            </div>
        </div><br/>
        <div>
            <div class="l" style="width:400px;height:280px;background-color: #a49ea3">
                检测二维码
            </div>
            <div class="l" style="margin-left:10px;width:400px;height:280px;background-color: #a49ea3">
                租借信息
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection
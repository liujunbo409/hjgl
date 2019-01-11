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
                    <span class="l">商家名称: {{isset($tool->shop_name)? $tool->shop_name : ''}}</span><br/><br/>
                    <span class="l">加入时间: {{isset($tool->create_time)? $tool->create_time : ''}}</span>
                </div>
                <div class="r"  style="width:300px;">
                    <br/>
                    <span class="l">设备借出状态: {{\App\Components\Utils::tool_loan_status[$tool->loan_status]}}</span>
                </div>
                <br/>
            </div>
            <div style="float:right;margin-right:10px;width:300px;">
                <span class="r"><a>检测结果</a></span><br/>
                <div class="r" style="width:150px;height:100px;border:1px solid">
                    <br/>
                    <span class="r">检测时长</span><br/>
                    <br/>
                    <span class="r">xx小时</span><br/>
                </div>
            </div>
        </div><br/>
        <div>

            <div class="l" style="width:400px;height:280px;border:1px solid">
                设备二维码<br/>
                <input id="code" name="code" value="{{$tool->code}}" hidden>
                @if(isset($tool->code) && !empty($tool->code))
                    <div  id="qrcodeCanvas"></div><br/>
                    <span>重新生成二维码</span>
                @else
                    <div style="width:200px;height:200px;"></div><br/>
                    <span>生成二维码</span>
                @endif

            </div>
            <div class="l" style="margin-left:10px;width:400px;height:280px;border:1px solid">
                租借信息
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        var code = $("#code").val();
            jQuery('#qrcodeCanvas').qrcode({
                text	: code,
                width:200,
                height:200
            });
    </script>
@endsection
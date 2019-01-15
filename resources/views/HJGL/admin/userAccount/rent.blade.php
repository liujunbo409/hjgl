@extends('HJGL.admin.layouts.app')

@section('content')

    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 账目管理 <span
                class="c-gray en">&gt;</span> 租金管理<a class="btn btn-success radius r btn-refresh"
                                                       style="line-height:1.6em;margin-top:3px"
                                                       href="javascript:location.replace(location.href);" title="刷新"
                                                       onclick="location.replace('{{URL::asset('admin/userLoan/rent')}}');"><i
                    class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form action="{{URL::asset('admin/userLoan/rent')}}" method="post" class="form-horizontal">
                {{csrf_field()}}
                <div class="Huiform text-r">
                    订单编号/商家: <input id="search_word" name="search_word" type="text" class="input-text" style="width:350px" value="{{$con_arr['search_word']?$con_arr['search_word']:''}}">
                    <span class="select-box" style="width:150px">
                        <select name="order_status" id="is_base" class="select" size="1">
                            <option value="" {{$con_arr['order_status']==null?'selected':''}}>未选择</option>
                            @foreach(\App\Components\Utils::order_status as $key=>$value)
                                <option value="{{$key}}" {{$con_arr['order_status'] == strval($key)?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </span>
                    <button type="submit" class="btn btn-success" id="" name="">
                        <i class="Hui-iconfont">&#xe665;</i> 搜索
                    </button>
                </div>
            </form>
        </div>
        <example></example>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="r">共有数据：<strong>{{$datas->total()}}</strong> 条</span>
        </div>
        <table class="table table-border table-bordered table-bg table-sort mt-10">
            <thead>
            <tr>
                <th scope="col" colspan="8">订单列表</th>
            </tr>
            <tr class="text-c">
                <th>订单号</th>
                <th>商家</th>
                <th>总应支付租金</th>
                <th>未支付租金</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr class="text-c">
                    <td>{{$data->order_number}}</td>
                    <td>{{$data->shop_name}}</td>
                    <td>{{$data->rent_total}}</td>
                    <td>{{$data->rent_unpaid}}</td>
                    <td>{{$data->order_status}}</td>
                    <td class="td-manage">
                        <a href="{{URL::asset('admin/userOrder/info?order_number='.$data->order_number)}}">详情</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-20">
            {{ $datas->appends($con_arr)->links() }}
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection
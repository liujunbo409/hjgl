@extends('HJGL.user.layouts.app')
<style type="text/css">
</style>
@section('content')
    <div class="hui-header">
        <h1>个人信息</h1>
    </div>
    <div class="hui-wrap" style="width:100%;">
        <form style="padding:28px 10px;" class="hui-form" id="form1">
            <div class="hui-list" style="background:#FFFFFF; margin-top:28px;">
                <ul>
                    <li>
                        <div class="hui-list-text">
                            姓氏<input id="hj_name" value="" class="hui-input hui-input-clear" style="margin-right:2%;width:100%;height:49px;direction:rtl;" placeholder="请输入姓氏">
                        </div>
                    </li>
                    <li>
                        <div class="hui-list-text">
                            性别
                            <input id="btn1" value="" class="hui-input hui-input-clear" style="margin-right:2%;width:100%;height:49px;direction:rtl;" placeholder="请选择性别" readonly>
                            <input type="hidden" id="hj_sex" value="">
                        </div>
                    </li>
                    <li>
                        <div class="hui-list-text">
                            所在地区
                            <input id="btn3" class="hui-input hui-input-clear" style="margin-right:2%;width:100%;height:49px;direction:rtl;" placeholder="请选择所在区域" readonly>
                            <input type="hidden" id="hj_province" value="">
                            <input type="hidden" id="hj_city" value="">
                            <input type="hidden" id="hj_area" value="">
                        </div>
                    </li>
                    <li>
                        <div class="hui-list-text">
                            详细地址<input id="hj_address" class="hui-input hui-input-clear" style="margin-right:2%;width:100%;height:49px;direction:rtl;" placeholder="请输入详细地址">
                        </div>
                    </li>
                </ul>
            </div>
            <div style="padding:15px 8px;">
                <span type="button" onclick="submit()" class="hui-button hui-primary hui-wrap" value="" id="submitBtn" style="margin-top:10%;">确定</span>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        /* 示例 1 : 普通选择器 非关联型绑定 */
        var picker1 = new huiPicker('#btn1', function(){
            var val = picker1.getVal(0);
            var txt = picker1.getText(0);
            // hui('#btn1').val(txt + '[' + val + ']');
            $("#hj_sex").val(val);
            hui('#btn1').val(txt);
        });
        // 同级 picker 数量设置 默认 1
        picker1.level    = 1;
        //1. piker 数据
        var pickerData   = [{value:1, text:'男'},{value:2, text:'女'}];
        //2. 查询默认值 例如  {value:2, text:'女'} 的默认值为 2
        //不设置默认值此步骤省略即可
        var defaultValue  = 2;
        var defaultIndex  = pickerData.pickerIndexOf(defaultValue);
        //3 绑定数据时设置默认值 //不设置默认值 忽略第三个参数即可
        picker1.bindData(0, pickerData, defaultIndex);
        /* 地区选择， 关联型数据 */
        var picker3 = new huiPicker('#btn3', function(){
            var sheng   = picker3.getText(0);
            var shi     = picker3.getText(1);
            var qu      = picker3.getText(2);
            var shengVal= picker3.getVal(0);
            var shiVal  = picker3.getVal(1);
            var quVal   = picker3.getVal(2);
            console.log(sheng, shi, qu);
            console.log(shengVal, shiVal, quVal);
            $("#hj_province").val(sheng);
            $("#hj_city").val(shi);
            $("#hj_area").val(qu);
            hui('#btn3').val(sheng + shi + qu);
        });
        picker3.level = 3;
        //cities 数据来源于 cities.js
        // 默认值设置方式 [330000 330400 330424] 浙江省 嘉兴市 海盐区
        var defaultVal = [330000, 330400, 330424];
        // 不设置默认值忽略第三个参数即可
        picker3.bindRelevanceData(cities, defaultVal);

        function submit(){
            //手机号是否为空
            var  hj_name= $("#hj_name").val();
            if (hj_name == null || hj_name.length == 0 || judgeIsNullStr(hj_name)) {
                hui.iconToast('姓氏不能为空', 'warn');
                return false;
            }
            var  hj_sex= $("#hj_sex").val();
            if (hj_sex == null || hj_sex.length == 0 || judgeIsNullStr(hj_sex)) {
                hui.iconToast('性别不能为空', 'warn');
                return false;
            }
            var  hj_province= $("#hj_province").val();
            if (hj_province == null || hj_province.length == 0 || judgeIsNullStr(hj_province)) {
                hui.iconToast('省不能为空', 'warn');
                return false;
            }
            var  hj_city= $("#hj_city").val();
            if (hj_city == null || hj_city.length == 0 || judgeIsNullStr(hj_city)) {
                hui.iconToast('市不能为空', 'warn');
                return false;
            }
            var  hj_area= $("#hj_area").val();
            if (hj_area == null || hj_area.length == 0 || judgeIsNullStr(hj_area)) {
                hui.iconToast('区不能为空', 'warn');
                return false;
            }
            var  hj_address= $("#hj_address").val();
            if (hj_address == null || hj_address.length == 0 || judgeIsNullStr(hj_address)) {
                hui.iconToast('详细地址不能为空', 'warn');
                return false;
            }
            $.ajax({
                type: 'post',
                url: "{{URL::asset('api/perfect_info_save')}}",
                dataType: 'json',
                data: {
                    'hj_name' : hj_name,
                    'hj_sex' : hj_sex,
                    'hj_province' : hj_province,
                    'hj_city' : hj_city,
                    'hj_area' : hj_area,
                    'hj_address' : hj_address,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    console.log(data);
                    if (data.code == 200) {
                        window.location="{{URL::asset('api/hjjc/index')}}";
                    } else {
                        hui.iconToast(data.message, 'warn');
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }
    </script>
@endsection
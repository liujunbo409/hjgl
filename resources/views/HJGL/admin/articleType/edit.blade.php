@extends('HJGL.admin.layouts.app')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('f-tree/css/css.css') }}"/>
<script type="text/javascript" src="{{ URL::asset('f-tree/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('f-tree/js/config.js') }}"></script>
{{--<script type="text/javascript" src="{{ URL::asset('f-tree/js/data.js') }}"></script>--}}
<style>
    .s1{
        width:10%;
        height:5%;
    }
    .a1{
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
</style>
@section('content')
    <div style="width:100%;">
        <input type="hidden" id="id" name="id" value="{{$articleType->id}}">
        <div style="width:70%;text-align:center;margin-top:10%;">
            目录名称:&nbsp;
            <input style="width:40%;height:6%;" class="input-text a1" id="name"  name="name" value="{{isset($articleType->name)?$articleType->name:'' }}">
        </div>
        <div style="width:70%;text-align:center;margin-top:3%;">
            是否显示:&nbsp;
            <select style="width:40%;height:6%" class="select a1" name="show" id="show" size="1">
                <option value="0" {{$articleType['show']==null?'selected':''}}>未选择</option>
                @foreach(\App\Components\Utils::articler_type_show as $key=>$value)
                    <option value="{{$key}}" {{$articleType['show'] == strval($key)?'selected':''}}>{{$value}}</option>
                @endforeach
            </select>
        </div>
        <div style="width:70%;text-align:center;margin-top:3%;">
            <button type="submit" onclick="edit()" class="s1 a1">保存</button><button style="margin-left:7%;" type="submit" class="s1 a1">删除</button>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function edit(){
            var id=$("#id").val();
            var name=$("#name").val();
            var show=$("#show").val();
            $.ajax({
                type: 'POST',
                url: "{{URL::asset('admin/articleType/edit')}}",
                dataType: 'json',
                data: {
                    id: id,
                    name: name,
                    show: show,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data, sta) {
                    if (data.code == 200) {
                        parent.click(id);
                    } else {
                        layer.alert(data.message, function () {
                        });
                    }
                },
                error: function (data) {
                    console.log(data)
                }
            });
        }

        function del(){

        }
    </script>

@endsection



@extends('HJGL.admin.layouts.app')
@section('content')
    <span style="color:#ff0000;cursor: pointer;" onclick="chooseCon()">删除</span>
@endsection

@section('script')
    <script type="text/javascript">
        function chooseCon() {
            $.ajax({
                type: 'POST',
                url: "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=TOKEN",
                dataType: 'json',
                data: {"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "test"}}},
                success: function (data, sta) {
                    console.log(data)
                },
                error: function (data) {
                    console.log(data)
                }
            })
        }
    </script>
@endsection

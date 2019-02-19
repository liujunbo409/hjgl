@extends('HJGL.user.layouts.app')

@section('content')

    <div class="hui-wrap">
        <div class="line"></div>
        <div class="hui-title">
            通用标题
        </div>
        <div class="line"></div>
        <div class="hui-title hui-primary-txt">
            通用标题 + More
            <div class="hui-more"><a href="javascript:hui.toast('more...')">More...</a></div>
        </div>
        <div class="line"></div>
        <div class="hui-center-title">
            <h1>居中的标题</h1>
        </div>
        <div class="line"></div>
        <div class="hui-common-title" style="margin-top:15px;">
            <div class="hui-common-title-line"></div>
            <div class="hui-common-title-txt">带有修饰的标题</div>
            <div class="hui-common-title-line"></div>
        </div>
        <div class="hui-content" style="padding:10px;">
            <h2>文本段落演示</h2>
            是啊，春风十里不及你。后来，你们单位的事太多，我陪着你回到了你的宿舍。
            <p>使用P标记会缩进！你的宿舍简单到只有一张床一套沙发和一个衣柜。我说，你这是一箪食一瓢饮的颜回，你点头，我就喜欢这样安静的生活，人不堪其忧，我也不改其乐。</p>
            <br/>手机绑定<br/>
            请输入您的手机号码,<br/>
            绑定您的环境检测帐号<br/>
        </div>




@endsection

@section('script')



@endsection
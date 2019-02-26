<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/QRcode/index/{tool_id}', 'HJGL\API\QRcodeController@index');
Route::get('/user', function () {
    $user = session('wechat.oauth_user'); // 拿到授权用户资料
    dd($user);
    return redirect()->to('/home#/index'); //這時候已經拿到用戶資料了，跳轉到想要的路由
});

Route::group(['middleware'=>['web']],function(){
    //前方配置
    Route::any('/sendAlertMsg', 'HJGL\API\WeChatController@sendAlertMsg');
    Route::get('/getAccessToken', 'HJGL\API\WeChatController@getAccessToken');//获取access_token
    Route::get('/getMenu', 'HJGL\API\WeChatController@getMenu');//获取菜单
    Route::get('/createMenu', 'HJGL\API\WeChatController@createMenu');//创建菜单
    Route::get('/delMenu', 'HJGL\API\WeChatController@delMenu');//删除菜单

    Route::any('/wechat', 'HJGL\API\WeChatController@serve');
    Route::get('/webScope', 'HJGL\API\WeChatController@webScope');//网页授权
    Route::get('/getInfo', 'HJGL\API\WeChatController@getInfo');//获取信息
});

Route::group(['middleware'=>['web','hjgl.userLogin']],function(){

    //完善相关
    Route::get('/perfect_phone', 'HJGL\API\PerfectController@perfect_phone');//是否绑定手机号码
    Route::any('/perfect_phone_save', 'HJGL\API\PerfectController@perfect_phone_save');//完善手机号码
    Route::get('/perfect_info', 'HJGL\API\PerfectController@perfect_info');//是否完善个人信息
    Route::any('/perfect_info_save', 'HJGL\API\PerfectController@perfect_info_save');//完善个人信息
    Route::any('/validateNewPhone', 'HJGL\API\PerfectController@validateNewPhone');//发送验证码

    //环境检测
    Route::get('/hjjc/index', 'HJGL\API\HjjcController@index');
    Route::any('/hjjc/getCH2O', 'HJGL\API\HjjcController@getCH2O');
    Route::any('/hjjc/getC6H6', 'HJGL\API\HjjcController@getC6H6');
    Route::any('/hjjc/getC8H10', 'HJGL\API\HjjcController@getC8H10');
    Route::any('/hjjc/getVOC', 'HJGL\API\HjjcController@getVOC');

    //订单
    Route::get('/order/index', 'HJGL\API\OrderController@index');
    Route::get('/order/loan', 'HJGL\API\OrderController@loan');

    //我
    Route::get('/my/index', 'HJGL\API\MyController@index');
    Route::get('/my/info', 'HJGL\API\MyController@info');
    Route::get('/my/phone', 'HJGL\API\MyController@phone');

    //二维码相关
//    Route::get('/QRcode/index/{tool_id}', 'HJGL\API\QRcodeController@index');


});







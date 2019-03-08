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

Route::group(['prefix' => 'app','middleware'=>['cors']],function(){
    Route::any('/login', 'HJGL\App\LoginController@login');
    Route::any('/send_code', 'HJGL\App\LoginController@send_code');
    Route::any('/forget_login', 'HJGL\App\LoginController@forget_login');
    Route::any('/tool', 'HJGL\App\ShopToolController@index');
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

    //完善相关
    Route::get('/perfect_phone', 'HJGL\API\PerfectController@perfect_phone');//是否绑定手机号码
    Route::any('/perfect_phone_save', 'HJGL\API\PerfectController@perfect_phone_save');//完善手机号码
    Route::get('/perfect_info', 'HJGL\API\PerfectController@perfect_info');//是否完善个人信息
    Route::any('/perfect_info_save', 'HJGL\API\PerfectController@perfect_info_save');//完善个人信息
    Route::any('/validateNewPhone', 'HJGL\API\PerfectController@validateNewPhone');//发送验证码
    Route::any('/lose', 'HJGL\API\PerfectController@lose');//信息丢失
});

Route::group(['middleware'=>['web','hjgl.userLogin']],function(){

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
    Route::post('/my/info_save', 'HJGL\API\MyController@info_save');
    Route::get('/my/phone', 'HJGL\API\MyController@phone');
    Route::post('/my/phone_save', 'HJGL\API\MyController@phone_save');

    //二维码相关
//    Route::any('/QRcode/index/{tool_num}', 'HJGL\API\QRcodeController@index');
    Route::any('/QRcode/index', 'HJGL\API\QRcodeController@index');
    Route::any('/QRcode/test', 'HJGL\API\QRcodeController@test');
    Route::any('/QRcode/order_list', 'HJGL\API\QRcodeController@order_list');
    Route::any('/QRcode/orderPhone', 'HJGL\API\QRcodeController@orderPhone');
    Route::any('/QRcode/orderPhoneSave', 'HJGL\API\QRcodeController@orderPhoneSave');
    Route::any('/QRcode/paying', 'HJGL\API\QRcodeController@paying');

});







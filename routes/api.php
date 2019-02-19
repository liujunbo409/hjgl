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

Route::get('/user', function () {
    $user = session('wechat.oauth_user'); // 拿到授权用户资料
    dd($user);
    return redirect()->to('/home#/index'); //這時候已經拿到用戶資料了，跳轉到想要的路由
});
Route::any('/test', 'HJGL\API\WeChatController@test');
Route::any('/getAccessToken', 'HJGL\API\WeChatController@getAccessToken');

Route::group(['middleware'=>['web']],function(){
    Route::any('/sendAlertMsg', 'HJGL\API\WeChatController@sendAlertMsg');
    Route::any('/wechat', 'HJGL\API\WeChatController@serve');
    Route::get('/getAccessToken', 'HJGL\API\WeChatController@getAccessToken');//获取access_token
    Route::get('/getMenu', 'HJGL\API\WeChatController@getMenu');//获取菜单
    Route::get('/createMenu', 'HJGL\API\WeChatController@createMenu');//创建菜单
    Route::get('/delMenu', 'HJGL\API\WeChatController@delMenu');//删除菜单
    Route::get('/webScope', 'HJGL\API\WeChatController@webScope');//网页授权
    Route::get('/getInfo', 'HJGL\API\WeChatController@getInfo');//获取信息


    Route::any('/perfect_phone', 'HJGL\API\PerfectController@perfect_phone');//获取信息
    Route::any('/perfect_info', 'HJGL\API\PerfectController@perfect_info');//获取信息


    Route::get('/hjjc', 'HJGL\API\WeChatController@hjjc');//环境检测
});







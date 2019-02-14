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
    return redirect()->to('/home#/index'); //這時候已經拿到用戶資料了，跳轉到想要的路由
});
Route::any('/test', 'HJGL\API\WeChatController@test');
Route::any('/getAccessToken', 'HJGL\API\WeChatController@getAccessToken');


Route::group(['middleware'=>['web','wechat.oauth']],function(){

});
Route::any('/menu_add', 'HJGL\API\WeChatController@menu_add');
Route::any('/wechat', 'HJGL\API\WeChatController@serve');





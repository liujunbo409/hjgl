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
Route::any('/wechat', 'HJGL\API\WeChatController@serve');
Route::any('/test',function(){
    return redirect()->to('admin/login');
});
Route::get('/user', function () {
    $user = session('wechat.oauth_user'); // 拿到授权用户资料
    return redirect()->to('/home#/index'); //這時候已經拿到用戶資料了，跳轉到想要的路由
});

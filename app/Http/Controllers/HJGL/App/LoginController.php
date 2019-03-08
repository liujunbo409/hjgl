<?php

namespace App\Http\Controllers\HJGL\App;

use App\Components\HJGL\ShopManager;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use App\Http\Controllers\ApiResponse;
use App\Components\HJGL\VertifyManager;
use App\Components\Utils;

class LoginController
{
    //POST-实现登录逻辑
    public function login(Request $request)
    {
        $data = $request->all();

        //参数校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, '请输入账号或密码', ApiResponse::MISSING_PARAM);
        }
        $con_arr = array(
            'phone' => $data['phone'],
            'password' => $data['password'],
        );
        $shop = ShopManager::getListByCon($con_arr, false)->first();
        if (!$shop) {
            return ApiResponse::makeResponse(false, '账户名或密码错误', ApiResponse::PARAM_ERROR);
        }
        if($shop->status != 2 && $shop->id != 1){
            return ApiResponse::makeResponse(false, '该账号已被禁用', ApiResponse::POWER_ERROR);
        }
        $re_shop = array(
            'id'=>$shop->id,
        );
        return ApiResponse::makeResponse(true, $re_shop, ApiResponse::SUCCESS_CODE);
    }

    //注销登录
    public function forget(Request $request)
    {

    }

    /*
     * 向用户旧手机号发送短信
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function send_code(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('phone', $data) || $data['phone'] == '') {
            return ApiResponse::makeResponse(false, '请输入手机号', ApiResponse::MISSING_PARAM);
        }
        $con_arr=array(
            'phone'=>$data['phone']
        );
        $shop=ShopManager::getListByCon($con_arr,false)->first();
        if(!$shop){
            return ApiResponse::makeResponse(false, '手机号不存在', ApiResponse::NO_USER);
        }
        $phonenum=$shop->phonenum;
        $result = VertifyManager::sendVertify($phonenum);
        if($result){
            return ApiResponse::makeResponse(true,'短信验证码已发送', ApiResponse::SUCCESS_CODE);
        }
        return ApiResponse::makeResponse(false, '短信验证码发送失败', ApiResponse::SM_VERTIFY_SEND_ERROR);
    }

}
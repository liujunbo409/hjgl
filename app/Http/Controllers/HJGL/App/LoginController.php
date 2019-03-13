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
            'name'=>$shop->name,
            'phone'=>$shop->phone,
        );
        return ApiResponse::makeResponse(true, $re_shop, ApiResponse::SUCCESS_CODE);
    }

    //忘记密码_登录
    public function forget_login(Request $request)
    {
        $data = $request->all();
        if(!array_key_exists('phone',$data) || Utils::isObjNull($data['phone'])){
            return ApiResponse::makeResponse(false, '请输入手机号', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('sms_code',$data) || Utils::isObjNull($data['sms_code'])){
            return ApiResponse::makeResponse(false, '请输入验证码', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('password',$data) || Utils::isObjNull($data['password'])){
            return ApiResponse::makeResponse(false, '请输入密码', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('confirm_password',$data) || Utils::isObjNull($data['confirm_password'])){
            return ApiResponse::makeResponse(false, '请输入确认密码', ApiResponse::MISSING_PARAM);
        }
        if($data['password'] != $data['confirm_password']){
            return ApiResponse::makeResponse(false, '两次输入密码不一致', ApiResponse::INNER_ERROR);
        }

        $con_arr=array(
            'phone'=>$data['phone']
        );
        $shop=ShopManager::getListByCon($con_arr,false)->first();
        if(!$shop){
            return ApiResponse::makeResponse(false, '手机号不存在', ApiResponse::NO_USER);
        }
        $ys_sm = VertifyManager::judgeVertifyCode($data['phone'], $data['sms_code']);
        if (!$ys_sm) {
            return ApiResponse::makeResponse(false, '短信验证码验证失败', ApiResponse::SM_VERTIFY_ERROR);
        }

        $shop->password = $data['password'];
        $re_shop = array(
            'id'=>$shop->id
        );
        $shop->save();
        return ApiResponse::makeResponse(true, $re_shop, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 向用户原有手机号发送短信
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
        $phone=$shop->phone;
        $result = VertifyManager::sendVertify($phone);
        if($result){
            return ApiResponse::makeResponse(true,'短信验证码已发送', ApiResponse::SUCCESS_CODE);
        }
        return ApiResponse::makeResponse(false, '短信验证码发送失败', ApiResponse::SM_VERTIFY_SEND_ERROR);
    }

    /*
     * 向用户新手机号发送短信
     */
    public function send_code_new(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('phone', $data) || $data['phone'] == '') {
            return ApiResponse::makeResponse(false, '请输入手机号', ApiResponse::MISSING_PARAM);
        }
        $con_arr=array(
            'phone'=>$data['phone']
        );
        $shop=ShopManager::getListByCon($con_arr,false)->first();
        if($shop){
            return ApiResponse::makeResponse(false, '手机号已存在', ApiResponse::NO_USER);
        }
        $phone=$data['phone'];
        $result = VertifyManager::sendVertify($phone);
        if($result){
            return ApiResponse::makeResponse(true,'短信验证码已发送', ApiResponse::SUCCESS_CODE);
        }
        return ApiResponse::makeResponse(false, '短信验证码发送失败', ApiResponse::SM_VERTIFY_SEND_ERROR);
    }

}
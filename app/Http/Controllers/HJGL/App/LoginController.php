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
//        return ApiResponse::makeResponse(true, '登陆成功', ApiResponse::SUCCESS_CODE);
        return ApiResponse::makeResponse(false, '请输入账号或密码', ApiResponse::MISSING_PARAM);

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
        $admin = ShopManager::getListByCon($con_arr, false)->first();
        if (!$admin) {
            return ApiResponse::makeResponse(false, '账户名或密码错误', ApiResponse::PARAM_ERROR);
        }
        if($admin->status != 2 && $admin->id != 1){
            return ApiResponse::makeResponse(false, '该账号已被禁用', ApiResponse::POWER_ERROR);
        }
        return ApiResponse::makeResponse(true, '登陆成功', ApiResponse::SUCCESS_CODE);
    }

    //注销登录
    public function loginout(Request $request)
    {
        //清空session
        $request->session()->remove('admin');
        return redirect('/admin/login');
    }

    /*
     * 向用户旧手机号发送短信
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function validateOldPhonenum(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('login_name', $data) || $data['login_name'] == '') {
            return ApiResponse::makeResponse(false, '请输入登录名', ApiResponse::MISSING_PARAM);
        }
        $con_arr=array(
            'login_name'=>$data['login_name']
        );
        $admin=ShopManager::getListByCon($con_arr,false)->first();
        if(!$admin){
            return ApiResponse::makeResponse(false, '账号不存在', ApiResponse::NO_USER);
        }
        $phonenum=$admin->phonenum;
        $result = VertifyManager::sendVertify($phonenum);
        if($result){
            return ApiResponse::makeResponse(true,'短信验证码已发送', ApiResponse::SUCCESS_CODE);
        }
        return ApiResponse::makeResponse(false, '短信验证码发送失败', ApiResponse::SM_VERTIFY_SEND_ERROR);
    }

}
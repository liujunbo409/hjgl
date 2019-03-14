<?php

namespace App\Http\Controllers\HJGL\App;

use App\Components\HJGL\ShopManager;
use App\Components\HJGL\ToolManager;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use App\Components\HJGL\VertifyManager;
use Illuminate\Support\Facades\Log;

class ShopController
{

    public function index(Request $request)
    {
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '信息缺失', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        $re_shop = array(
            'shop_name'=>$shop->shop_name,
            'shop_img'=>$shop->shop_img,
            'name'=>$shop->name,
            'address'=>$shop->address,
            'phone'=>$shop->phone,
            'open_time'=>substr(date($shop->open_time),0,5),
            'close_time'=>substr(date($shop->close_time),0,5),
        );
        return ApiResponse::makeResponse(true, $re_shop, ApiResponse::SUCCESS_CODE);
    }

    public function update_time(Request $request){
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '信息缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('open_time',$data) || Utils::isObjNull($data['open_time'])){
            return ApiResponse::makeResponse(false, '营业时间缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('close_time',$data) || Utils::isObjNull($data['close_time'])){
            return ApiResponse::makeResponse(false, '歇业时间缺失', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        $shop->open_time = $data['open_time'];
        $shop->close_time = $data['close_time'];
        $shop->save();
        return ApiResponse::makeResponse(true, '修改营业时间成功', ApiResponse::SUCCESS_CODE);
    }

    public function update_phone(Request $request){
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '信息缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('phone',$data) || Utils::isObjNull($data['phone'])){
            return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        $shop->phone = $data['phone'];
        $shop->save();
        return ApiResponse::makeResponse(true, '修改成功手机号', ApiResponse::SUCCESS_CODE);
    }

    public function name_phone(Request $request){
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '信息缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('phone',$data) || Utils::isObjNull($data['phone'])){
            return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('name',$data) || Utils::isObjNull($data['name'])){
            return ApiResponse::makeResponse(false, '商家姓名缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('sms_code',$data) || Utils::isObjNull($data['sms_code'])){
            return ApiResponse::makeResponse(false, '短信验证码缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('remarks',$data) || Utils::isObjNull($data['remarks'])){
            return ApiResponse::makeResponse(false, '备注缺失', ApiResponse::MISSING_PARAM);
        }
        $ys_sm = VertifyManager::judgeVertifyCode($data['phone'], $data['sms_code']);
        if (!$ys_sm) {
            return ApiResponse::makeResponse(false, '短信验证码验证失败', ApiResponse::SM_VERTIFY_ERROR);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        $shop->phone = $data['phone'];
        $shop->name = $data['name'];
        $shop->save();
        return ApiResponse::makeResponse(true, '提交审核成功', ApiResponse::SUCCESS_CODE);
    }

    public function update_pwd(Request $request){
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '信息缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('pwd',$data) || Utils::isObjNull($data['pwd'])){
            return ApiResponse::makeResponse(false, '密码缺失', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        if($data['pwd'] != $shop->password){
            return ApiResponse::makeResponse(false, '密码错误', ApiResponse::PARAM_ERROR);
        }else{
            return ApiResponse::makeResponse(true, '', ApiResponse::SUCCESS_CODE);
        }
    }

    public function save_pwd(Request $request){
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '信息缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('new_pwd',$data) || Utils::isObjNull($data['new_pwd'])){
            return ApiResponse::makeResponse(false, '新密码缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('confirm_pwd',$data) || Utils::isObjNull($data['confirm_pwd'])){
            return ApiResponse::makeResponse(false, '确认密码缺失', ApiResponse::MISSING_PARAM);
        }
        if($data['new_pwd'] != $data['confirm_pwd']){
            return ApiResponse::makeResponse(false, '两次密码输入不一致', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        $shop->password = $data['new_pwd'];
        $shop->save();
        return ApiResponse::makeResponse(true, '新密码保存成功', ApiResponse::SUCCESS_CODE);
    }

    public function add_img(Request $request){
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '信息缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('shop_img',$data) || Utils::isObjNull($data['shop_img'])){
            return ApiResponse::makeResponse(false, '图片未获取到', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '商家信息获取失败', ApiResponse::MISSING_PARAM);
        }
        $shop->shop_img = $data['shop_img'];
        $shop->save();
        return ApiResponse::makeResponse(true, $data, ApiResponse::SUCCESS_CODE);
    }



}
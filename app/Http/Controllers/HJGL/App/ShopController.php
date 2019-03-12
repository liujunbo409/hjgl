<?php

namespace App\Http\Controllers\HJGL\App;

use App\Components\HJGL\ShopManager;
use App\Components\HJGL\ToolManager;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;

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
            'address'=>$shop->address,
            'phone'=>$shop->phone,
            'open_time'=>$shop->open_time,
            'close_time'=>$shop->close_time,
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
        if(!array_key_exists('shop_name',$data) || Utils::isObjNull($data['shop_name'])){
            return ApiResponse::makeResponse(false, '商家姓名缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('sms_code',$data) || Utils::isObjNull($data['sms_code'])){
            return ApiResponse::makeResponse(false, '短信验证码缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('remarks',$data) || Utils::isObjNull($data['remarks'])){
            return ApiResponse::makeResponse(false, '备注缺失', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        $shop->phone = $data['phone'];
        $shop->shop_name = $data['shop_name'];
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
        if(!array_key_exists('pwd',$data) || Utils::isObjNull($data['pwd'])){
            return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        $shop->password = $data['pwd'];
        $shop->save();
        return ApiResponse::makeResponse(true, '新密码保存成功', ApiResponse::SUCCESS_CODE);
    }



}
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
        );
        dd($re_shop);
        return ApiResponse::makeResponse(true, $re_shop, ApiResponse::SUCCESS_CODE);
    }


}
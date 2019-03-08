<?php

namespace App\Http\Controllers\HJGL\App;

use App\Components\HJGL\ShopManager;
use App\Components\HJGL\ToolManager;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;

class ShopToolController
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
        $con_arr = array(
            'shop_id'=>$data['id'],
        );
        $tools = ToolManager::getListByCon($con_arr,false);
        return ApiResponse::makeResponse(true, $tools, ApiResponse::SUCCESS_CODE);
    }


}
<?php

namespace App\Http\Controllers\HJGL\App;

use App\Components\HJGL\ShopManager;
use App\Components\HJGL\UserOrderManager;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;

class ShopOrderController
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
        $tools = UserOrderManager::getListByCon($con_arr,false);
        $re_tools = array();
        foreach($tools as $k=>$v){
            $re_tools[$k]['number'] = $v->number;
            $re_tools[$k]['loan_status'] = $v->loan_status;
            $re_tools[$k]['order_number'] = empty($v->order_number)?$v->order_number:'';
        }

        return ApiResponse::makeResponse(true, $re_tools, ApiResponse::SUCCESS_CODE);
    }


}
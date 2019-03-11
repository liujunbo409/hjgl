<?php

namespace App\Http\Controllers\HJGL\App;

use App\Components\HJGL\ShopManager;
use App\Components\HJGL\UserLoanManager;
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
        //进行中订单
        $con_doing = array(
            'shop_id'=>$data['id'],
            'order_status'=>array('1'),
        );
        $order_doing = UserOrderManager::getListByCon($con_doing,false);
        $re_doing = array();
        foreach($order_doing as $k=>$v){
            $re_doing[$k]['tool_numstr'] = explode(',',$v->tool_numstr);
            $re_doing[$k]['order_status'] = $v->order_status;
            $re_doing[$k]['order_number'] = empty($v->order_number)?'':$v->order_number;
            $re_doing[$k]['created_at'] = date($v->created_at);
            $re_doing[$k]['end_time'] = $v->end_time;
            $re_doing[$k]['user_name'] = $v->user_name;
            $re_doing[$k]['user_phone'] = $v->user_phone;
        }
        //已完成订单
        $con_finish = array(
            'shop_id'=>$data['id'],
            'order_status'=>array('2'),
        );
        $order_finish = UserOrderManager::getListByCon($con_finish,false);
        $re_finish = array();
        foreach($order_finish as $k=>$v){
            $re_finish[$k]['tool_numstr'] = explode(',',$v->tool_numstr);
            $re_finish[$k]['order_status'] = $v->order_status;
            $re_finish[$k]['order_number'] = empty($v->order_number)?'':$v->order_number;
            $re_finish[$k]['created_at'] = date($v->created_at);
            $re_finish[$k]['end_time'] = $v->end_time;
            $re_finish[$k]['user_name'] = $v->user_name;
            $re_finish[$k]['user_phone'] = $v->user_phone;
        }
        $order = array(
            'doing'=>$re_doing,
            'finish'=>$re_finish,
        );
        return ApiResponse::makeResponse(true, $order, ApiResponse::SUCCESS_CODE);
    }


    public function order_detail(Request $request){
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '信息缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('order_number',$data) || Utils::isObjNull($data['order_number'])){
            return ApiResponse::makeResponse(false, '订单号缺失', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        $order = UserOrderManager::getByOrderNumber($data['order_number']);
        if(empty($order)){
            return ApiResponse::makeResponse(false, '订单不存在', ApiResponse::MISSING_PARAM);
        }
        $re_order = array(
            'order_number'=>$order->order_number,
            'created_at'=>date($order->created_at),
            'end_time'=>$order->end_time,
            'user_name'=>$order->user_name,
            'user_phone'=>$order->user_phone,
        );

        $con_arr = array(
            'order_number'=>$order->order_number,
        );
        $tools = UserLoanManager::getListByCon($con_arr,false);
        $re_tools = array();
        foreach($tools as $k=>$v){
            $re_tools[$k]['tool_number'] = $v->tool_number;
            $re_tools[$k]['loan_status'] = $v->loan_status;
            $re_tools[$k]['lease_duration'] = ceil((time()-strtotime($v->created_at))/60/60);
            $re_tools[$k]['rent_status'] = $v->rent_status;
        }
        $re_info = array(
            'order'=>$re_order,
            'tools'=>$re_tools
        );
        return ApiResponse::makeResponse(true, $re_info, ApiResponse::SUCCESS_CODE);
    }

}
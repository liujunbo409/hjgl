<?php

namespace App\Components\HJGL;

use App\Models\HJGL\UserOrder;
use App\Components\Utils;

class UserOrderManager{
    /*
     * 根据id获取订单
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getById($id){
        $order = UserOrder::where('id','=',$id)->first();
        return $order;
    }

    /*
     * 根据order_number获取订单
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getByOrderNumber($order_number){
        $user_order = UserOrder::where('order_number','=',$order_number)->first();
        return $user_order;
    }

    /*
     * 根据条件获取列表
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $orders = new UserOrder();
        //相关条件
        if (array_key_exists('order_status', $con_arr) && !Utils::isObjNull($con_arr['order_status'])) {
            $orders = $orders->whereIn('order_status',$con_arr['order_status']);
        }
        if (array_key_exists('user_openid', $con_arr) && !Utils::isObjNull($con_arr['user_openid'])) {
            $orders = $orders->where('user_openid','=',$con_arr['user_openid']);
        }
        if (array_key_exists('shop_id', $con_arr) && !Utils::isObjNull($con_arr['shop_id'])) {
            $orders = $orders->where('shop_id','=',$con_arr['shop_id']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $orders = $orders->where(function ($query) use ($keyword) {
                $query->where('order_number', 'like', "%{$keyword}%")
                    ->orwhere('shop_name', 'like', "%{$keyword}%");
            });
        }

        $orders = $orders->orderBy('plan_minbacktime', 'asc');
        //配置规则
        if ($is_paginate) {
            $orders = $orders->paginate(Utils::PAGE_SIZE);
        } else {
            $orders = $orders->get();
        }

        return $orders;
    }

    /*
     * 设置订单信息，用于编辑
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function setUserOrder($user_order, $data)
    {
        if (array_key_exists('order_number', $data) && !Utils::isObjNull($data['order_number'])) {
            $user_order->order_number = array_get($data, 'order_number');
        }
        if (array_key_exists('shop_id', $data) && !Utils::isObjNull($data['shop_id'])) {
            $user_order->shop_id = array_get($data, 'shop_id');
        }
        if (array_key_exists('shop_name', $data) && !Utils::isObjNull($data['shop_name'])) {
            $user_order->shop_name = array_get($data, 'shop_name');
        }
        if (array_key_exists('user_id', $data) && !Utils::isObjNull($data['user_id'])) {
            $user_order->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('user_openid', $data) && !Utils::isObjNull($data['user_openid'])) {
            $user_order->user_openid = array_get($data, 'user_openid');
        }
        if (array_key_exists('user_phone', $data) && !Utils::isObjNull($data['user_phone'])) {
            $user_order->user_phone = array_get($data, 'user_phone');
        }
        if (array_key_exists('user_name', $data) && !Utils::isObjNull($data['user_name'])) {
            $user_order->user_name = array_get($data, 'user_name');
        }
        if (array_key_exists('tool_numstr', $data) && !Utils::isObjNull($data['tool_numstr'])) {
            $user_order->tool_numstr = array_get($data, 'tool_numstr');
        }
        if (array_key_exists('tool_total', $data) && !Utils::isObjNull($data['tool_total'])) {
            $user_order->tool_total = array_get($data, 'tool_total');
        }
        if (array_key_exists('tool_finish', $data) && !Utils::isObjNull($data['tool_finish'])) {
            $user_order->tool_finish = array_get($data, 'tool_finish');
        }
        if (array_key_exists('address', $data) && !Utils::isObjNull($data['address'])) {
            $user_order->address = array_get($data, 'address');
        }
        if (array_key_exists('order_duration', $data) && !Utils::isObjNull($data['order_duration'])) {
            $user_order->order_duration = array_get($data, 'order_duration');
        }
        if (array_key_exists('plan_minbacktime', $data) && !Utils::isObjNull($data['plan_minbacktime'])) {
            $user_order->plan_minbacktime = array_get($data, 'plan_minbacktime');
        }
        if (array_key_exists('order_status', $data) && !Utils::isObjNull($data['order_status'])) {
            $user_order->order_status = array_get($data, 'order_status');
        }
        return $user_order;
    }

    /*
     * 根据条搜索件获取设备借用总租金
     *
     * By Yuyang
     *
     * 2019-01-18
     */
    public static function getByTimeRange($start_time,$end_time,$order_status = 1){
        $orders = new UserOrder();
        $orders->where('order_status','=',$order_status);
        //相关条件
        $orders = $orders->whereBetween('created_at', [$start_time, $end_time]);
        $orders = $orders->get();
        return $orders;
    }

}
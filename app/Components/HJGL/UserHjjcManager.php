<?php

namespace App\Components\HJGL;

use App\Models\HJGL\UserHjjc;
use App\Components\Utils;

class UserHjjcManager{
    /*
     * 根据条件获取信息
     *
     * By Yuyang
     *
     * 2019-2-22
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $info = new UserHjjc();
        //相关条件
        if (array_key_exists('order_number', $con_arr) && !Utils::isObjNull($con_arr['order_number'])) {
            $info = $info->where('order_number', '=', $con_arr['order_number']);
        }
        if (array_key_exists('tool_id', $con_arr) && !Utils::isObjNull($con_arr['tool_id'])) {
            $info = $info->where('tool_id', '=', $con_arr['tool_id']);
        }
        if (array_key_exists('tool_number', $con_arr) && !Utils::isObjNull($con_arr['tool_number'])) {
            $info = $info->where('tool_number', '=', $con_arr['tool_number']);
        }
        $info = $info->orderBy('id', 'desc');
        if ($is_paginate) {
            $info = $info->paginate(Utils::PAGE_SIZE);
        } else {
            $info = $info->get();
        }
        return $info;
    }

    public static function serInfo($info,$data){
        if (array_key_exists('order_number', $data) && !Utils::isObjNull($data['order_number'])) {
            $info->order_number = array_get($data, 'order_number');
        }
        if (array_key_exists('tool_id', $data) && !Utils::isObjNull($data['tool_id'])) {
            $info->tool_id = array_get($data, 'tool_id');
        }
        if (array_key_exists('tool_number', $data) && !Utils::isObjNull($data['tool_number'])) {
            $info->tool_number = array_get($data, 'tool_number');
        }
        if (array_key_exists('ch2o_value', $data) && !Utils::isObjNull($data['ch2o_value'])) {
            $info->ch2o_value = array_get($data, 'ch2o_value');
        }
        if (array_key_exists('c6h6_value', $data) && !Utils::isObjNull($data['c6h6_value'])) {
            $info->c6h6_value = array_get($data, 'c6h6_value');
        }
        if (array_key_exists('c8h10_value', $data) && !Utils::isObjNull($data['c8h10_value'])) {
            $info->c8h10_value = array_get($data, 'c8h10_value');
        }
        if (array_key_exists('voc_value', $data) && !Utils::isObjNull($data['voc_value'])) {
            $info->voc_value = array_get($data, 'voc_value');
        }
    }

}
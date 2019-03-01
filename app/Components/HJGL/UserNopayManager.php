<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/18 0018
 * Time: 上午 9:56
 */
namespace App\Components\HJGL;

use App\Models\HJGL\UserNopay;
use App\Components\Utils;

class UserNopayManager{

    public static function getById($user_openid)
    {
        $info = UserNopay::where('user_openid', '=', $user_openid)->first();
        return $info;
    }

    public static function getByToolNum($tool_num)
    {
        $info = UserNopay::where('tool_num', '=', $tool_num)->first();
        return $info;
    }

    /*
     * 根据条件获取
     *
     * By Yuyang
     *
     * 2019-2-18
     */
    public static function getListByCon($con_arr,$is_paginate=false){
        $info = new UserNopay();
        //相关条件
        if (array_key_exists('user_openid', $con_arr) && !Utils::isObjNull($con_arr['user_openid'])) {
            $info = $info->where('user_openid', '=' ,$con_arr['user_openid']);
        }
        if (array_key_exists('start_time', $con_arr) && !Utils::isObjNull($con_arr['start_time']) && array_key_exists('end_time', $con_arr) && !Utils::isObjNull($con_arr['end_time'])) {
            $info = $info->whereBetween('updated_at', [$con_arr['start_time'] ,$con_arr['end_time']]);
        }
        $info = $info->orderBy('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $info = $info->paginate(Utils::PAGE_SIZE);
        } else {
            $info = $info->get();
        }
        return $info;
    }

    public static function setInfo($info,$data){
        if (array_key_exists('user_openid', $data) && !Utils::isObjNull($data['user_openid'])) {
            $info->user_openid = array_get($data, 'user_openid');
        }
        if (array_key_exists('tool_num', $data) && !Utils::isObjNull($data['tool_num'])) {
            $info->tool_num = array_get($data, 'tool_num');
        }
        return $info;
    }
}
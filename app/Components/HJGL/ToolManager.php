<?php

namespace App\Components\HJGL;

use App\Models\HJGL\Tool;
use App\Components\Utils;

class ToolManager{
    /*
     * 根据id获取设备
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function getById($id){
        $tool = Tool::where('id','=',$id)->first();
        return $tool;
    }

    /*
     * 根据number获取设备
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function getByNumber($number){
        $tool = Tool::where('number','=',$number)->first();
        return $tool;
    }

    /*
     * 根据shop_id获取设备
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function getByShopid($Shopid){
        $tools = Tool::where('shop_id','=',$Shopid)->get();
        return $tools;
    }

    /*
     * 根据条件获取列表
     *
     * By Yuyang
     *
     * 2018-12-28
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $tools = new Tool();
        //相关条件
        if (array_key_exists('number', $con_arr) && !Utils::isObjNull($con_arr['number'])) {
            $tools = $tools->where('number', '=', $con_arr['number']);
        }
        if (array_key_exists('shop_id', $con_arr) && !Utils::isObjNull($con_arr['shop_id'])) {
            $tools = $tools->where('shop_id', '=', $con_arr['shop_id']);
        }
        if (array_key_exists('code', $con_arr) && !Utils::isObjNull($con_arr['code'])) {
            $tools = $tools->where('code', '=', $con_arr['code']);
        }
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $tools = $tools->where('status', '=', $con_arr['status']);
        }
        if (array_key_exists('loan', $con_arr) && !Utils::isObjNull($con_arr['loan'])) {
            $tools = $tools->where('loan', '=', $con_arr['loan']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $tools = $tools->where(function ($query) use ($keyword) {
                $query->where('number', 'like', "%{$keyword}%")
                    ->orwhere('calibration_person', 'like', "%{$keyword}%");
            });
        }
        $tools = $tools->orderby('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $tools = $tools->paginate(Utils::PAGE_SIZE);
        } else {
            $tools = $tools->get();
        }

        return $tools;
    }

    /*
     * 设置设备信息，用于编辑
     *
     * By Yuyang
     *
     * 2018-12-28
     */
    public static function setTool($tool, $data)
    {
        if (array_key_exists('number', $data)) {
            $tool->number = array_get($data, 'number');
        }
        if (array_key_exists('shop_id', $data)) {
            $tool->shop_id = array_get($data, 'shop_id');
        }
        if (array_key_exists('code', $data)) {
            $tool->code = array_get($data, 'code');
        }
        if (array_key_exists('monitoring_duration', $data)) {
            $tool->monitoring_duration = array_get($data, 'monitoring_duration');
        }
        if (array_key_exists('calibration_time', $data)) {
            $tool->calibration_time = array_get($data, 'calibration_time');
        }
        if (array_key_exists('calibration_person', $data)) {
            $tool->calibration_person = array_get($data, 'calibration_person');
        }
        if (array_key_exists('status', $data)) {
            $tool->status = array_get($data, 'status');
        }
        if (array_key_exists('create_person', $data)) {
            $tool->create_person = array_get($data, 'create_person');
        }
        if (array_key_exists('update_person', $data)) {
            $tool->update_person = array_get($data, 'update_person');
        }
        if (array_key_exists('delete_person', $data)) {
            $tool->delete_person = array_get($data, 'delete_person');
        }
        return $tool;
    }

}
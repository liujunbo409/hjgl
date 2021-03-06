<?php

namespace App\Components\HJGL;

use App\Models\HJGL\Tool;
use App\Components\Utils;
use App\Models\HJGL\UserInfo;

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
        if (array_key_exists('numbers', $con_arr) && !Utils::isObjNull($con_arr['numbers'])) {
            $tools = $tools->whereIn('number', $con_arr['numbers']);
        }
        if (array_key_exists('shop_id', $con_arr) && !Utils::isObjNull($con_arr['shop_id'])) {
            $tools = $tools->where('shop_id', '=', $con_arr['shop_id']);
        }
        if (array_key_exists('shop_ids', $con_arr) && !Utils::isObjNull($con_arr['shop_ids'])) {
            $tools = $tools->whereIn('shop_id', $con_arr['shop_ids']);
        }
        if (array_key_exists('code', $con_arr) && !Utils::isObjNull($con_arr['code'])) {
            $tools = $tools->where('code', '=', $con_arr['code']);
        }
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $tools = $tools->where('status', '=', $con_arr['status']);
        }
        if (array_key_exists('loan_status', $con_arr) && !Utils::isObjNull($con_arr['loan_status'])) {
            $tools = $tools->where('loan_status', '=', $con_arr['loan_status']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $tools = $tools->where(function ($query) use ($keyword) {
                $query->where('number', 'like', "%{$keyword}%")
                ->orwhere('shop_name', 'like', "%{$keyword}%");
            });
        }
        $tools = $tools->orderBy('id', 'desc');
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
        if (array_key_exists('number', $data) && !Utils::isObjNull($data['number'])) {
            $tool->number = array_get($data, 'number');
        }
        if (array_key_exists('shop_id', $data) && !Utils::isObjNull($data['shop_id'])) {
            $tool->shop_id = array_get($data, 'shop_id');
        }
        if (array_key_exists('code', $data) && Utils::isObjNull($data['code'])) {
            $tool->code = array_get($data, 'code');
        }
        if (array_key_exists('detection_duration_total', $data) && !Utils::isObjNull($data['detection_duration_total'])) {
            $tool->detection_duration_total = array_get($data, 'detection_duration_total');
        }
        if (array_key_exists('calibration_time', $data) && !Utils::isObjNull($data['calibration_time'])) {
            $tool->calibration_time = array_get($data, 'calibration_time');
        }
        if (array_key_exists('calibration_person', $data) && !Utils::isObjNull($data['calibration_person'])) {
            $tool->calibration_person = array_get($data, 'calibration_person');
        }
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $tool->status = array_get($data, 'status');
        }
        return $tool;
    }

}
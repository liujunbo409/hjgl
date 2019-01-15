<?php

namespace App\Components\HJGL;

use App\Models\HJGL\ToolDispose;
use App\Components\Utils;

class ToolDisposeManager{
    /*
     * 根据id获取设备处理表
     *
     * By Yuyang
     *
     * 2019-01-07
     */
    public static function getById($id){
        $tool = ToolDispose::where('id','=',$id)->first();
        return $tool;
    }

    /*
     * 根据条件获取列表
     *
     * By Yuyang
     *
     * 2019-01-07
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $toolDisposes = new ToolDispose();
        //相关条件
        if (array_key_exists('tool_id', $con_arr) && !Utils::isObjNull($con_arr['tool_id'])) {
            $toolDisposes = $toolDisposes->where('tool_id', '=', $con_arr['tool_id']);
        }
        if (array_key_exists('shop_id', $con_arr) && !Utils::isObjNull($con_arr['shop_id'])) {
            $toolDisposes = $toolDisposes->where('shop_id', '=', $con_arr['shop_id']);
        }
        if (array_key_exists('detection_duration', $con_arr) && !Utils::isObjNull($con_arr['detection_duration'])) {
            $toolDisposes = $toolDisposes->where('detection_duration', '=', $con_arr['detection_duration']);
        }
        if (array_key_exists('calibration_time', $con_arr) && !Utils::isObjNull($con_arr['calibration_time'])) {
            $toolDisposes = $toolDisposes->where('calibration_time', '=', $con_arr['calibration_time']);
        }
        if (array_key_exists('calibration_person', $con_arr) && !Utils::isObjNull($con_arr['calibration_person'])) {
            $toolDisposes = $toolDisposes->where('calibration_person', '=', $con_arr['calibration_person']);
        }
        if (array_key_exists('type', $con_arr) && !Utils::isObjNull($con_arr['type'])) {
            $toolDisposes = $toolDisposes->where('type', '=', $con_arr['type']);
        }
        if (array_key_exists('process', $con_arr) && !Utils::isObjNull($con_arr['process'])) {
            $toolDisposes = $toolDisposes->wherein('process', $con_arr['process']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $toolDisposes = $toolDisposes->where(function ($query) use ($keyword) {
                $query->where('tool_num', 'like', "%{$keyword}%")
                    ->orwhere('shop_name', 'like', "%{$keyword}%");
            });
        }
        $toolDisposes = $toolDisposes->orderby('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $toolDisposes = $toolDisposes->paginate(Utils::PAGE_SIZE);
        } else {
            $toolDisposes = $toolDisposes->get();
        }

        return $toolDisposes;
    }

    /*
     * 设置设备信息，用于编辑
     *
     * By Yuyang
     *
     * 2018-12-28
     */
    public static function setToolDisposes($toolDispose, $data)
    {
        if (array_key_exists('tool_id', $data)) {
            $toolDispose->tool_id = array_get($data, 'tool_id');
        }
        if (array_key_exists('shop_id', $data)) {
            $toolDispose->shop_id = array_get($data, 'shop_id');
        }
        if (array_key_exists('detection_duration', $data)) {
            $tool->detection_duration = array_get($data, 'detection_duration');
        }
        if (array_key_exists('calibration_time', $data)) {
            $toolDispose->calibration_time = array_get($data, 'calibration_time');
        }
        if (array_key_exists('calibration_person', $data)) {
            $toolDispose->calibration_person = array_get($data, 'calibration_person');
        }
        if (array_key_exists('type', $data)) {
            $toolDispose->type = array_get($data, 'type');
        }
        if (array_key_exists('process', $data)) {
            $toolDispose->process = array_get($data, 'process');
        }
        if (array_key_exists('remarks', $data)) {
            $toolDispose->remarks = array_get($data, 'remarks');
        }
        if (array_key_exists('create_person', $data)) {
            $toolDispose->create_person = array_get($data, 'create_person');
        }
        if (array_key_exists('update_person', $data)) {
            $toolDispose->update_person = array_get($data, 'update_person');
        }
        return $toolDispose;
    }

}
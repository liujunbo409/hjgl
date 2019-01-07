<?php
namespace App\Components\HJGL;

use App\Models\HJGL\SystemParameter;
use App\Components\Utils;

class SystemParameterManager{
    /*
    * 根据id获取系统参数
    *
    * By Yuyang
    *
    * 2019-01-07
    */
    public static function getById($id){
        $parameter = SystemParameter::where('id','=',$id)->first();
        return $parameter;
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
        $infos = new SystemParameter();
        //相关条件
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('parameter_name', 'like', "%{$keyword}%")
                    ->orwhere('parameter', 'like', "%{$keyword}%")
                    ->orwhere('parameter_val', 'like', "%{$keyword}%");
            });
        }
        $infos = $infos->orderby('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $infos = $infos->paginate(Utils::PAGE_SIZE);
        } else {
            $infos = $infos->get();
        }
        return $infos;
    }

    /*
     * 设置系统参数信息，用于编辑
     *
     * By Yuyang
     *
     * 2019-01-17
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('parameter_name', $data)) {
            $info->parameter_name = array_get($data, 'parameter_name');
        }
        if (array_key_exists('parameter', $data)) {
            $info->parameter = array_get($data, 'parameter');
        }
        if (array_key_exists('parameter_val', $data)) {
            $info->parameter_val = array_get($data, 'parameter_val');
        }
        return $info;
    }

}
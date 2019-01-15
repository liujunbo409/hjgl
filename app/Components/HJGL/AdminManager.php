<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2018-3-19
 * Time: 10:30
 */

namespace App\Components\HJGL;

use App\Models\HJGL\Admin;
use App\Components\Utils;

class AdminManager
{

    /*
     * 根据id获取管理员
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function getById($id)
    {
        $admin = Admin::where('id', '=', $id)->first();
        return $admin;
    }

    /*
     * 根据条件获取列表
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $admins = new Admin();
        //相关条件
        if (array_key_exists('name', $con_arr) && !Utils::isObjNull($con_arr['name'])) {
            $admins = $admins->where('name', '=', $con_arr['name']);
        }
        if (array_key_exists('password', $con_arr) && !Utils::isObjNull($con_arr['password'])) {
            $admins = $admins->where('password', '=', $con_arr['password']);
        }
        if (array_key_exists('phone', $con_arr) && !Utils::isObjNull($con_arr['phone'])) {
            $admins = $admins->where('phone', '=', $con_arr['phone']);
        }

        if (array_key_exists('role', $con_arr) && !Utils::isObjNull($con_arr['role'])) {
            $admins = $admins->where('role', '=', $con_arr['role']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $admins = $admins->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
        }
        $admins = $admins->orderby('id', 'asc');
        //配置规则
        if ($is_paginate) {
            $admins = $admins->paginate(Utils::PAGE_SIZE);
        } else {
            $admins = $admins->get();
        }

        return $admins;
    }

    /*
     * 根据手机号获取用户信息
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function getByPhone($phone)
    {
        $admin = Admin::where('phone', '=', $phone)->first();
        return $admin;
    }


    /*
    * 搜索管理员信息
         *
         * By Yuyang
         *
         * 2018-12-27
         *
         */
    public static function searchByNameAndPhone($search_word)
    {
        $admins = Admin::where('name', 'like', '%' . $search_word . '%')
            ->orwhere('phone', 'like', '%' . $search_word . '%')->orderby('id', 'desc')->get();
        return $admins;
    }


    /*
     * 设置管理员信息，用于编辑
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function setAdmin($admin, $data)
    {
        if (array_key_exists('role', $data)) {
            $admin->role = array_get($data, 'role');
        }
        if (array_key_exists('phone', $data)) {
            $admin->phone = array_get($data, 'phone');
        }
        if (array_key_exists('name', $data)) {
            $admin->name = array_get($data, 'name');
        }
        if (array_key_exists('status', $data)) {
            $admin->status = array_get($data, 'status');
        }
        if (array_key_exists('password', $data)) {
            $admin->password = array_get($data, 'password');
        }
        if (array_key_exists('nickname', $data)) {
            $admin->nickname = array_get($data, 'nickname');
        }
        if (array_key_exists('avatar', $data)) {
            $admin->avatar = array_get($data, 'avatar');
        }
        return $admin;
    }

    /*
     * 根据级别获取管理员信息
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function getInfoByLevel($admin, $level)
    {
        //带管理员身份
        if(strpos($level,'0')!== false){
            $admin->role_str=Utils::admin_role[$admin->role];
        }
        //手机号脱敏
        if(strpos($level,'1')!== false){
            $admin->phone=Utils::remove_sensitive('phone',$admin->phone);
        }
        return $admin;
    }
}
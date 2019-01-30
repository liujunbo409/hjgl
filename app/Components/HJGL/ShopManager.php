<?php

namespace App\Components\HJGL;

use App\Models\HJGL\Shop;
use App\Components\Utils;

class ShopManager{
    /*
     * 根据id获取商家
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function getById($id){
        $shop = Shop::where('id','=',$id)->first();
        return $shop;
    }

    /*
     * 根据phone获取商家
     *
     * By Yuyang
     *
     * 2018-12-27
     */
    public static function getByPhone($phone){
        $shop = Shop::where('phone','=',$phone)->first();
        return $shop;
    }

    /*
     * 根据条件商家列表
     *
     * By Yuyang
     *
     * 2018-12-28
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $shops = new Shop();
        //相关条件
        if (array_key_exists('name', $con_arr) && !Utils::isObjNull($con_arr['name'])) {
            $shops = $shops->where('name', '=', $con_arr['name']);
        }
        if (array_key_exists('phone', $con_arr) && !Utils::isObjNull($con_arr['phone'])) {
            $shops = $shops->where('phone', '=', $con_arr['phone']);
        }
        if (array_key_exists('address', $con_arr) && !Utils::isObjNull($con_arr['address'])) {
            $shops = $shops->where('address', '=', $con_arr['address']);
        }
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $shops = $shops->where('status', '=', $con_arr['status']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $shops = $shops->where(function ($query) use ($keyword) {
                $query->where('shop_name', 'like', "%{$keyword}%");
            });
        }
        $shops = $shops->orderBy('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $shops = $shops->paginate(Utils::PAGE_SIZE);
        } else {
            $shops = $shops->get();
        }

        return $shops;
    }

    /*
     * 设置商家信息，用于编辑
     *
     * By Yuyang
     *
     * 2018-12-28
     */
    public static function setShop($shop, $data)
    {
        if (array_key_exists('password', $data)) {
            $shop->password = array_get($data, 'password');
        }
        if (array_key_exists('shop_name', $data)) {
            $shop->shop_name = array_get($data, 'shop_name');
        }
        if (array_key_exists('name', $data)) {
            $shop->name = array_get($data, 'name');
        }
        if (array_key_exists('phone', $data)) {
            $shop->phone = array_get($data, 'phone');
        }
        if (array_key_exists('address', $data)) {
            $shop->address = array_get($data, 'address');
        }
        return $shop;
    }

}
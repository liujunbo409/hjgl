<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\HJGL\Admin;

use App\Components\HJGL\UserOrderManager;
use Illuminate\Http\Request;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;

class UserAccountController{
    /*
     * 设备列表
     *
     * By Yuyang
     *
     * 2019/01/08
     */
    public function rent(Request $request){
        $data = $request->all();
        //条件搜索
        $search_word = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }
        if (array_key_exists('order_status', $data) && !Utils::isObjNull($data['order_status'])) {
            $order_status = $data['order_status'];
        }else{
            $order_status = '';
        }
        $con_arr = array(
            'search_word' => $search_word,
            'order_status' => $order_status,
        );
        $orders = UserOrderManager::getListByCon($con_arr,true);
        return view('HJGL.admin.userAccount.rent', [ 'datas' => $orders, 'con_arr' => $con_arr]);
    }

    public function deposit(Request $request){
        $data = $request->all();
        //条件搜索
        $search_word = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }
        if (array_key_exists('order_status', $data) && !Utils::isObjNull($data['order_status'])) {
            $order_status = $data['order_status'];
        }else{
            $order_status = '';
        }
        $con_arr = array(
            'search_word' => $search_word,
            'order_status' => $order_status,
        );
        $orders = UserOrderManager::getListByCon($con_arr,true);
        return view('HJGL.admin.userAccount.deposit', [ 'datas' => $orders, 'con_arr' => $con_arr]);
    }


}
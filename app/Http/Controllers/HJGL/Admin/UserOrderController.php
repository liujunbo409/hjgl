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
use App\Components\HJGL\ToolLoanManager;
use Illuminate\Http\Request;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;

class UserOrderController{
    /*
     * 用户订单列表
     *
     * By Yuyang
     *
     * 2019/01/08
     */
    public function index(Request $request){
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
        return view('HJGL.admin.userOrder.index', [ 'datas' => $orders, 'con_arr' => $con_arr]);
    }

    public function info(Request $request){
        $data = $request->all();
        //条件搜索
        $search_word = null;
        if(!array_key_exists('order_number',$data) || Utils::isObjNull($data['order_number'])){
            return '订单号缺失';
        }
        $user_order = UserOrderManager::getByOrderNumber($data['order_number']);
        $user_order->long_time = ceil((strtotime("now")-strtotime($user_order->create_time))/3600);
        $tool_loans = ToolLoanManager::getByOrderNumber($data['order_number']);
        $con_arr_rent = array(
            'order_number' => $data['order_number'],
            'rent_status' => 1,
        );
        $con_arr_deposit = array(
            'order_number' => $data['order_number'],
            'deposit_status' => 1,
        );
        $user_order->rent_sum = ToolLoanManager::getBySumRent($con_arr_rent);
        $user_order->deposit_sum = ToolLoanManager::getBySumDeposit($con_arr_deposit);
        return view('HJGL.admin.userOrder.info', [ 'user_order' => $user_order , 'datas' => $tool_loans]);
    }


}
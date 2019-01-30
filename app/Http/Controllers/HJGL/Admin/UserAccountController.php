<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\HJGL\Admin;

use App\Components\HJGL\FinishAccountManager;
use App\Components\HJGL\SystemParameterManager;
use App\Components\HJGL\UserLoanManager;
use App\Components\HJGL\UserOrderManager;
use App\Models\HJGL\FinishAccount;
use Illuminate\Http\Request;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;

class UserAccountController{
    /*
     * 租金首页
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
            if(is_array($data['order_status'])){
                $order_status = $data['order_status'];
            }else{
                $order_status = explode(',',$data['order_status']);
            }
        }else{
            $order_status[] = '1';
        }
        $con_arr = array(
            'search_word' => $search_word,
            'order_status' => $order_status,
        );
        $orders = UserOrderManager::getListByCon($con_arr,true);
        $con = array(
            'order_number_s'=>array(),
        );
        foreach($orders as $v){
            $con['order_number_s'][] = $v->order_number;
        }
        $user_loans = UserLoanManager::getListByCon($con,false);
        $rent_account = array();
        foreach($user_loans as $v){
            if(isset($rent_account[$v->order_number])){
                $rent_account[$v->order_number]['rent_total'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_unpaid'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }
            }else{
                $rent_account[$v->order_number]['rent_total'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_unpaid'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }else{
                    $rent_account[$v->order_number]['rent_unpaid'] = 0;
                }
            }
        }
        foreach($orders as $v){
            $v['rent_total'] = $rent_account[$v->order_number]['rent_total'];
            $v['rent_unpaid'] = $rent_account[$v->order_number]['rent_unpaid'];
        }
        return view('HJGL.admin.userAccount.rent', [ 'datas' => $orders, 'con_arr' => $con_arr]);
    }

    /*
     * 总租金统计
     *
     * By Yuyang
     *
     * 2019/01/29
     */
    public function rent_total(Request $request){
        $con_arr = array(
            'order_status' => ['1'],
        );
        $orders = UserOrderManager::getListByCon($con_arr,false);
        $con = array(
            'order_number_s'=>array(),
        );
        foreach($orders as $v){
            $con['order_number_s'][] = $v->order_number;
        }
        $user_loans = UserLoanManager::getListByCon($con,false);
        $rent_account = array();
        foreach($user_loans as $v){
            if(isset($rent_account[$v->order_number])){
                $rent_account[$v->order_number]['rent_total'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_unpaid'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }
            }else{
                $rent_account[$v->order_number]['rent_total'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_unpaid'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }else{
                    $rent_account[$v->order_number]['rent_unpaid'] = 0;
                }
            }
        }
        $re = array(
            'rent_total' =>0,
            'rent_unpaid' =>0,
        );
        foreach($orders as $v){
            $re['rent_total'] += $rent_account[$v->order_number]['rent_total'];
            $re['rent_unpaid'] += $rent_account[$v->order_number]['rent_unpaid'];
        }
        return ApiResponse::makeResponse(true, $re, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 月租金统计
     *
     * By Yuyang
     *
     * 2019/01/29
     */
    public function rent_month(Request $request){
        $data = $request->all();
        if(!array_key_exists('month_i',$data) || Utils::isObjNull($data['month_i'])){
            return ApiResponse::makeResponse(false, '参数month_i缺失', ApiResponse::MISSING_PARAM);
        }
        //所查询月的信息
        $start_time = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")+1-$data['month_i'],1,date("Y")));
        $end_time = date("Y-m-d H:i:s",mktime(23,59,59,date("m")+2-$data['month_i'] ,0,date("Y")));
        $orders = UserOrderManager::getByTimeRange($start_time,$end_time);
        $con = array(
            'order_number_s'=>array(),
        );
        foreach($orders as $v){
            $con['order_number_s'][] = $v->order_number;
        }
        $user_loans = UserLoanManager::getListByCon($con,false);
        $rent_account = array();
        foreach($user_loans as $v){
            if(isset($rent_account[$v->order_number])){
                $rent_account[$v->order_number]['rent_total'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_unpaid'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }
            }else{
                $rent_account[$v->order_number]['rent_total'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_unpaid'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }else{
                    $rent_account[$v->order_number]['rent_unpaid'] = 0;
                }
            }
        }
        $re = array(
            'rent_total' =>0,
            'search_time' =>'',
            'rent_compare' =>0,
        );
        foreach($orders as $v){
            $re['rent_total'] += $rent_account[$v->order_number]['rent_total'];
        }
        $re['search_time'] = date('Y-m-d',strtotime($start_time));
        //所查询月前一月的信息
        $start_time_front = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-$data['month_i'],1,date("Y")));
        $end_time_front = date("Y-m-d H:i:s",mktime(23,59,59,date("m")+1-$data['month_i'] ,0,date("Y")));
        $orders_front = UserOrderManager::getByTimeRange($start_time_front,$end_time_front);
        $con_front = array(
            'order_number_s'=>array(),
        );
        foreach($orders_front as $v){
            $con_front['order_number_s'][]= $v->order_number;
        }
        $user_loans_front = UserLoanManager::getListByCon($con_front,false);
        $rent_account_front = array();
        foreach($user_loans_front as $v){
            if(isset($rent_account_front[$v->order_number])){
                $rent_account_front[$v->order_number]['rent_total'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
            }else{
                $rent_account_front[$v->order_number]['rent_total'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
            }
        }
        $re_front = array(
            'rent_total' =>0,
        );
        foreach($orders_front as $v){
            $re_front['rent_total'] += $rent_account_front[$v->order_number]['rent_total'];
        }
        if($re_front['rent_total'] != 0 ){
            $re['rent_compare'] = $re['rent_total']/$re_front['rent_total'];
        }
        return ApiResponse::makeResponse(true, $re, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 日租金统计
     *
     * By Yuyang
     *
     * 2019/01/29
     */
    public function rent_day(Request $request){
        $data = $request->all();
        if(!array_key_exists('day_i',$data) || Utils::isObjNull($data['day_i'])){
            return ApiResponse::makeResponse(false, '参数day_i缺失', ApiResponse::MISSING_PARAM);
        }
        //所查询的天
        $start_time = date("Y-m-d H:i:s",mktime(0, 0, 0, date('m'), date('d')+1-$data['day_i'], date('Y')));
        $end_time = date("Y-m-d H:i:s",mktime(23, 59, 59, date('m'), date('d')+1-$data['day_i'], date('Y')));
        $orders = UserOrderManager::getByTimeRange($start_time,$end_time);
        $con = array(
            'order_number_s'=>array(),
        );
        foreach($orders as $v){
            $con['order_number_s'][] = $v->order_number;
        }
        $user_loans = UserLoanManager::getListByCon($con,false);
        $rent_account = array();
        foreach($user_loans as $v){
            if(isset($rent_account[$v->order_number])){
                $rent_account[$v->order_number]['rent_total'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_unpaid'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }
            }else{
                $rent_account[$v->order_number]['rent_total'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_unpaid'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }else{
                    $rent_account[$v->order_number]['rent_unpaid'] = 0;
                }
            }
        }
        $re = array(
            'rent_total' =>0,
            'search_time' =>'',
            'rent_compare' =>0,
        );
        foreach($orders as $v){
            $re['rent_total'] += $rent_account[$v->order_number]['rent_total'];
        }
        $re['search_time'] = date('Y-m-d',strtotime($start_time));
        //所查询天的前一天
        $start_time_front = date("Y-m-d H:i:s",mktime(0, 0, 0, date('m'), date('d')-$data['day_i'], date('Y')));
        $end_time_front = date("Y-m-d H:i:s",mktime(23, 59, 59, date('m'), date('d')-$data['day_i'], date('Y')));
        $orders_front = UserOrderManager::getByTimeRange($start_time_front,$end_time_front);
        $con_front = array(
            'order_number_s'=>array(),
        );
        foreach($orders_front as $v){
            $con_front['order_number_s'][] = $v->order_number;
        }
        $user_loans_front = UserLoanManager::getListByCon($con_front,false);
        $rent_account_front = array();
        foreach($user_loans_front as $v){
            if(isset($rent_account_front[$v->order_number])){
                $rent_account_front[$v->order_number]['rent_total'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
            }else{
                $rent_account_front[$v->order_number]['rent_total'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
            }
        }
        $re_front = array(
            'rent_total' =>0,
        );
        foreach($orders_front as $v){
            $re_front['rent_total'] += $rent_account_front[$v->order_number]['rent_total'];
        }
        if($re_front['rent_total'] != 0 ){
            $re['rent_compare'] = $re['rent_total']/$re_front['rent_total'];
        }
        return ApiResponse::makeResponse(true, $re, ApiResponse::SUCCESS_CODE);
    }

    public function rent_range(Request $request){
        $data = $request->all();
        if(!array_key_exists('start_time',$data) || Utils::isObjNull($data['start_time'])){
            return ApiResponse::makeResponse(false, '开始时间参数缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('end_time',$data) || Utils::isObjNull($data['end_time'])){
            return ApiResponse::makeResponse(false, '结束时间参数缺失', ApiResponse::MISSING_PARAM);
        }
        $orders = UserOrderManager::getByTimeRange($data['start_time'],$data['end_time']);
        $con = array(
            'order_number_s'=>array(),
        );
        foreach($orders as $v){
            $con['order_number_s'][] = $v->order_number;
        }
        $user_loans = UserLoanManager::getListByCon($con,false);
        $rent_account = array();
        foreach($user_loans as $v){
            if(isset($rent_account[$v->order_number])){
                $rent_account[$v->order_number]['rent_range_total'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_range_unpaid'] += SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }
            }else{
                $rent_account[$v->order_number]['rent_range_total'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                if($v->rent_status == 1){
                    $rent_account[$v->order_number]['rent_range_unpaid'] = SystemParameterManager::getRent($v->create_time,date('Y-m-d H:i:s'));
                }else{
                    $rent_account[$v->order_number]['rent_range_unpaid'] = 0;
                }
            }
        }
        $re = array(
            'rent_range_total' =>0,
            'rent_range_unpaid' =>0,
        );
        foreach($orders as $v){
            $re['rent_range_total'] += $rent_account[$v->order_number]['rent_range_total'];
            $re['rent_range_unpaid'] += $rent_account[$v->order_number]['rent_range_unpaid'];
        }
        return ApiResponse::makeResponse(true, $re, ApiResponse::SUCCESS_CODE);
    }

    public function deposit(Request $request){
        $data = $request->all();
        //条件搜索
        $search_word = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }
        if (array_key_exists('order_status', $data) && !Utils::isObjNull($data['order_status'])) {
            if(is_array($data['order_status'])){
                $order_status = $data['order_status'];
            }else{
                $order_status = explode(',',$data['order_status']);
            }
        }else{
            $order_status[] = '1';
        }
        $con_arr = array(
            'search_word' => $search_word,
            'order_status' => $order_status,
        );
        $orders = UserOrderManager::getListByCon($con_arr,true);
        $con = array(
            'order_number_s'=>array(),
        );
        foreach($orders as $v){
            $con['order_number_s'][] = $v->order_number;
        }
        $user_loans = UserLoanManager::getListByCon($con,false);
        $rent_account = array();
        foreach($user_loans as $v){
            if(isset($rent_account[$v->order_number])){
                $rent_account[$v->order_number]['deposit_total'] += $v->deposit;
                if($v->deposit_status == 1){
                    $rent_account[$v->order_number]['deposit_unpaid'] += $v->deposit;
                }
            }else{
                $rent_account[$v->order_number]['deposit_total'] = $v->deposit;
                if($v->deposit_status == 1){
                    $rent_account[$v->order_number]['deposit_unpaid'] = $v->deposit;
                }else{
                    $rent_account[$v->order_number]['deposit_unpaid'] = 0;
                }
            }
        }
        foreach($orders as $v){
            $v['deposit_total'] = $rent_account[$v->order_number]['deposit_total'];
            $v['deposit_unpaid'] = $rent_account[$v->order_number]['deposit_unpaid'];
        }
        return view('HJGL.admin.userAccount.deposit', [ 'datas' => $orders, 'con_arr' => $con_arr]);
    }


}
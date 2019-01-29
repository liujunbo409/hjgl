<?php

namespace App\Components\HJGL;

use App\Models\HJGL\UserLoan;
use App\Components\Utils;

class UserLoanManager{
    /*
     * 根据id获取设备借用
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getById($id){
        $user_loan = UserLoan::where('id','=',$id)->first();
        return $user_loan;
    }

    /*
     * 根据order_number获取设备借用
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getByOrderNumber($order_number){
        $user_loans = UserLoan::where('order_number','=',$order_number)->paginate(Utils::PAGE_SIZE);
        return $user_loans;
    }

    /*
     * 根据多个order_number获取设备借用
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getByOrderorNumber($order_number = array()){
        $user_loans = UserLoan::whereIn('order_number',$order_number)->get();
        return $user_loans;
    }

    /*
     * 根据tool_id获取单个设备借用信息
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getByToolId($tool_id , $loan_status){
        $user_loan = UserLoan::where('tool_id','=',$tool_id)->where('loan_status', '=' , $loan_status)->first();
        return $user_loan;
    }

    /*
     * 根据条件获取列表
     *
     * By Yuyang
     *
     * 2019-01-11
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $user_loans = new UserLoan();
        //相关条件
        if (array_key_exists('order_number', $con_arr) && !Utils::isObjNull($con_arr['order_number'])) {
            $user_loans = $user_loans->where('order_number', '=', $con_arr['order_number']);
        }
        if (array_key_exists('tool_id', $con_arr) && !Utils::isObjNull($con_arr['tool_id'])) {
            $user_loans = $user_loans->where('tool_id', '=', $con_arr['tool_id']);
        }
        if (array_key_exists('tool_number', $con_arr) && !Utils::isObjNull($con_arr['tool_number'])) {
            $user_loans = $user_loans->where('tool_number', '=', $con_arr['tool_number']);
        }
        if (array_key_exists('rent_status', $con_arr) && !Utils::isObjNull($con_arr['rent_status'])) {
            $user_loans = $user_loans->where('rent_status', '=', $con_arr['rent_status']);
        }
        if (array_key_exists('deposit_status', $con_arr) && !Utils::isObjNull($con_arr['deposit_status'])) {
            $user_loans = $user_loans->where('deposit_status', '=', $con_arr['deposit_status']);
        }
        if (array_key_exists('loan_status', $con_arr) && !Utils::isObjNull($con_arr['loan_status'])) {
            $user_loans = $user_loans->where('loan_status', '=', $con_arr['loan_status']);
        }
        $user_loans = $user_loans->orderby('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $user_loans = $user_loans->paginate(Utils::PAGE_SIZE);
        } else {
            $user_loans = $user_loans->get();
        }

        return $user_loans;
    }

    /*
     * 设置设备借用信息，用于编辑
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function setUserLoan($user_loan, $data)
    {
        if (array_key_exists('order_number', $data)) {
            $user_loan->order_number = array_get($data, 'order_number');
        }
        if (array_key_exists('tool_id', $data)) {
            $user_loan->tool_id = array_get($data, 'tool_id');
        }
        if (array_key_exists('tool_number', $data)) {
            $user_loan->tool_number = array_get($data, 'tool_number');
        }
        if (array_key_exists('detection_address', $data)) {
            $user_loan->detection_address = array_get($data, 'detection_address');
        }
        if (array_key_exists('detection_duration', $data)) {
            $user_loan->detection_duration = array_get($data, 'detection_duration');
        }
        if (array_key_exists('lease_duration', $data)) {
            $user_loan->lease_duration = array_get($data, 'lease_duration');
        }
        if (array_key_exists('out_time', $data)) {
            $user_loan->out_time = array_get($data, 'out_time');
        }
        if (array_key_exists('back_time', $data)) {
            $user_loan->back_time = array_get($data, 'back_time');
        }
        if (array_key_exists('plan_maxbacktime', $data)) {
            $user_loan->plan_maxbacktime = array_get($data, 'plan_maxbacktime');
        }
        if (array_key_exists('rent', $data)) {
            $user_loan->rent = array_get($data, 'rent');
        }
        if (array_key_exists('rent_status', $data)) {
            $user_loan->rent_status = array_get($data, 'rent_status');
        }
        if (array_key_exists('deposit', $data)) {
            $user_loan->deposit = array_get($data, 'deposit');
        }
        if (array_key_exists('deposit_status', $data)) {
            $user_loan->deposit_status = array_get($data, 'deposit_status');
        }
        if (array_key_exists('loan_status', $data)) {
            $user_loan->loan_status = array_get($data, 'loan_status');
        }
        if (array_key_exists('feedback', $data)) {
            $user_loan->feedback = array_get($data, 'feedback');
        }
        return $user_loan;
    }

    /*
     * 根据条搜索件获取设备借用总租金
     *
     * By Yuyang
     *
     * 2019-01-10
     */
    public static function getBySumRent($con_arr){
        $sum = new UserLoan();
        if (array_key_exists('order_number', $con_arr) && !Utils::isObjNull($con_arr['order_number'])) {
            $sum = $sum->where('order_number','=',$con_arr['order_number']);
        }
        if (array_key_exists('rent_status', $con_arr) && !Utils::isObjNull($con_arr['rent_status'])) {
            $sum = $sum->where('rent_status','=',$con_arr['rent_status']);
        }
        $sum = $sum->sum('rent');
        return $sum;
    }
    /*
     * 根据条搜索件获取设备借用总押金
     *
     * By Yuyang
     *
     * 2019-01-10
     */
    public static function getBySumDeposit($con_arr){
        $sum = new UserLoan();
        if (array_key_exists('order_number', $con_arr) && !Utils::isObjNull($con_arr['order_number'])) {
            $sum = $sum->where('order_number','=',$con_arr['order_number']);
        }
        if (array_key_exists('deposit_status', $con_arr) && !Utils::isObjNull($con_arr['deposit_status'])) {
            $sum = $sum->where('deposit_status','=',$con_arr['deposit_status']);
        }
        $sum = $sum->sum('deposit');
        return $sum;
    }

}
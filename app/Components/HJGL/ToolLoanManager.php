<?php

namespace App\Components\HJGL;

use App\Models\HJGL\ToolLoan;
use App\Components\Utils;

class ToolLoanManager{
    /*
     * 根据id获取设备借用
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getById($id){
        $tool_loan = ToolLoan::where('id','=',$id)->first();
        return $tool_loan;
    }

    /*
     * 根据order_number获取设备借用
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getByOrderNumber($order_number){
        $tool_loans = ToolLoan::where('order_number','=',$order_number)->paginate(Utils::PAGE_SIZE);
        return $tool_loans;
    }

    /*
     * 设置设备借用信息，用于编辑
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function setToolLoan($tool_loan, $data)
    {
        if (array_key_exists('order_number', $data)) {
            $tool_loan->order_number = array_get($data, 'order_number');
        }
        if (array_key_exists('tool_id', $data)) {
            $tool_loan->tool_id = array_get($data, 'tool_id');
        }
        if (array_key_exists('tool_number', $data)) {
            $tool_loan->tool_number = array_get($data, 'tool_number');
        }
        if (array_key_exists('detection_address', $data)) {
            $tool_loan->detection_address = array_get($data, 'detection_address');
        }
        if (array_key_exists('detection_duration', $data)) {
            $tool_loan->detection_duration = array_get($data, 'detection_duration');
        }
        if (array_key_exists('lease_duration', $data)) {
            $tool_loan->lease_duration = array_get($data, 'lease_duration');
        }
        if (array_key_exists('out_time', $data)) {
            $tool_loan->out_time = array_get($data, 'out_time');
        }
        if (array_key_exists('back_time', $data)) {
            $tool_loan->back_time = array_get($data, 'back_time');
        }
        if (array_key_exists('back_maxtime', $data)) {
            $tool_loan->back_maxtime = array_get($data, 'back_maxtime');
        }
        if (array_key_exists('rent', $data)) {
            $tool_loan->rent = array_get($data, 'rent');
        }
        if (array_key_exists('rent_status', $data)) {
            $tool_loan->rent_status = array_get($data, 'rent_status');
        }
        if (array_key_exists('deposit', $data)) {
            $tool_loan->deposit = array_get($data, 'deposit');
        }
        if (array_key_exists('deposit_status', $data)) {
            $tool_loan->deposit_status = array_get($data, 'deposit_status');
        }
        if (array_key_exists('loan_status', $data)) {
            $tool_loan->loan_status = array_get($data, 'loan_status');
        }
        if (array_key_exists('feedback', $data)) {
            $tool_loan->feedback = array_get($data, 'feedback');
        }
        return $tool_loan;
    }

}
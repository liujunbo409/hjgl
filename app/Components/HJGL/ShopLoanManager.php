<?php

namespace App\Components\HJGL;

use App\Models\HJGL\ShopLoan;
use App\Components\Utils;

class ShopLoanManager{
    /*
     * 根据id获取设备借用
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getById($id){
        $user_loan = ShopLoan::where('id','=',$id)->first();
        return $user_loan;
    }

    /*
     * 根据shop_id和tool_id查询
     *
     * By Yuyang
     *
     * 2019-01-09
     */
    public static function getByConId($shop_id,$tool_id){
        $shop_loan = new ShopLoan();
        if(empty($shop_id) || empty($tool_id)){
            return false;
        }else{
            $shop_loan = $shop_loan->where('shop_id','=',$shop_id)->where('tool_id','=',$tool_id)->first();
        }
        return $shop_loan;
    }


}
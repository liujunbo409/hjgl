<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2
 * Time: 14:23
 */

namespace App\Components\HJGL;

use App\Models\HJGL\VertifyModel;
use Illuminate\Support\Facades\Mail;

class VertifyManager
{
    /*
       * 生成验证码
       *
       * By TerryQi
       */
    public static function sendVertify($phone)
    {
        $vertify_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);  //生成4位验证码
        $vertify = new VertifyModel();
        $vertify->phone = $phone;
        $vertify->code = $vertify_code;
        $vertify->save();
        /*
         * 预留，需要触发短信端口进行验证码下发
         */
        if ($vertify) {
//            SMSManager::sendSMSVerification($phone, $vertify_code);
            return true;
        }
        return false;
    }

    /*
     * 校验验证码
     *
     * By TerryQi
     *
     * 2017-11-28
     */
    public static function judgeVertifyCode($phone, $vertify_code)
    {
        $vertify = VertifyModel::where('phone', $phone)
            ->where('code', $vertify_code)->where('status', '1')->first();
        if ($vertify) {
            //验证码置为失效
            $vertify->status = '2';
            $vertify->save();
            return true;
        } else {
            return false;
        }
    }

}
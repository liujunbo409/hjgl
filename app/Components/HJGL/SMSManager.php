<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components\HJGL;

use App\Components\Utils;
use Illuminate\Support\Facades\Log;
use Qiniu\Auth;

class SMSManager
{

    // 秒嘀的url基础请求地址
    public static $BASE_URL = "http://common.isart.me/api/common/sms/sendSMS";

    // key
    public static $ACCOUNT_SID = "54aaecef86ff460c8765f224f3c4c39d";
    // token
    public static $AUTH_TOKEN = "15fbd5d2124e4e0ba97c252b71aa4b0b";

    // http请求字符编码
    public static $CONTENT_TYPE = "application/x-www-form-urlencoded";

    // http提交数据方式
    public static $ACCEPT = "application/json";

    // 路由请求入口
    public static function sendSMSVerification($telphone, $vertify_code)
    {
        $param = array(
            'phonenum' => $telphone,       //项目pro_code应该统一管理，建议在Utils中定义一个通用变量
            'template_id' => '189079653',
            'pro_code' => Utils::PRO_CODE,
            'sms_txt' => $vertify_code . ",5"
        );
        $result = Utils::curl('http://common.isart.me/api/common/sms/sendSMS', $param, true);   //访问接口
        $result = json_decode($result, true);   //因为返回的已经是json数据，为了适配makeResponse方法，所以进行json转数组操作
        return $result;
    }


    //发送通知
    public static function sendSMS($telphone, $templated_id, $sms_txt)
    {
        $param = array(
            'phonenum' => $telphone,       //项目pro_code应该统一管理，建议在Utils中定义一个通用变量
            'template_id' => $templated_id,
            'pro_code' => Utils::PRO_CODE,
            'sms_txt' => $sms_txt
        );
        Log::info("sendSMS param:" . json_encode($param));
        $result = Utils::curl('http://common.isart.me/api/common/sms/sendSMS', $param, true);   //访问接口
        Log::info("result:" . json_encode($result));
        $result = json_decode($result, true);   //因为返回的已经是json数据，为了适配makeResponse方法，所以进行json转数组操作
        return $result;
    }


    // 生成基础的请求参数
    public static function createBasicAuthData()
    {
        $timestamp = date("YmdHis");

        // 签名
        $sig = md5(self::$ACCOUNT_SID . self::$AUTH_TOKEN . $timestamp);
        return array("accountSid" => self::$ACCOUNT_SID, "timestamp" => $timestamp, "sig" => $sig, "respDataType" => "JSON");
    }

    public static function createUrl($funAndOperate)
    {
        // 时间戳
        date_default_timezone_set("Asia/Shanghai");
        $timestamp = date("YmdHis");

        return self::$BASE_URL . $funAndOperate;
    }

    // 创建请求头
    public static function createHeaders()
    {
        $headers = array('Content-type: ' . self::$CONTENT_TYPE, 'Accept: ' . self::$ACCEPT);

        return $headers;
    }

    //
    public static function post($funAndOperate, $body)
    {

        // 构造请求数据
        $url = self::createUrl($funAndOperate);
        $headers = self::createHeaders();

//        echo $url;
//        echo json_encode($body);
//        var_dump($headers);

        // 拼接字符串
        $fields_string = "";
        foreach ($body as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        // 提交请求
        $con = curl_init();
        curl_setopt($con, CURLOPT_URL, $url);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($con, CURLOPT_HEADER, 0);
        curl_setopt($con, CURLOPT_POST, 1);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($con, CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($con);
        curl_close($con);

        return "" . $result;
    }

}
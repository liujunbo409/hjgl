<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/12/4
 * Time: 9:23
 */

namespace App\Components;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Utils
{

    //默认分页数
    const PAGE_SIZE = 15;
    const PAGE_SIZE_S = 5;

    //项目编码
    const PRO_CODE = "jtoa2T8q8p4OPzwbguFNLmLi3QAElBUp";


    //常量配置
    const status = ['0' => '否', '1' => '是'];
    //生效类型
    const status_str = ['0' => '不生效', '1' => '已生效'];
    //使用状态类型
    const status_fasong = ['0' => '未发送', '1' => '已发送'];
    //性别
    const gender_val = ['1' => '男', '2' => '女'];
    //后台管理员类型
    const admin_role = ['0' => '超级管理员', '1' => '账目管理员 ', '2' => '商家设备管理员 ', '3' => '配置管理员 '];
    //设备管理--设备状态
    const tool_status = ['1' => '开启', '2' => '禁用 '];
    //设备管理--设备借出状态
    const tool_loan_status = ['1' => '未借出', '2' => '已借出 ', '3' => '待校准 '];
    //设备处理--处理过程
    const tool_dispose_process = ['1' => '待取回', '2' => '待处理 ', '3' => '待送回 ', '4' => '已完成 '];
    //订单管理--订单状态
    const order_status = ['1' => '进行中', '2' => '已完成 '];

    /*
     * 判断一个对象是不是空
     *
     * By TerryQi
     *
     * 2017-12-23
     *
     */
    public static function isObjNull($obj)
    {
        if ($obj == null || $obj == "") {
            return true;
        }
        return false;
    }

    /*
     * 生成订单号
     *
     * By TerryQi
     *
     * 2017-01-12
     *
     */
    public static function generateTradeNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);;
    }

    /**
     * @param $url 请求网址
     * @param bool $params 请求参数
     * @param int $ispost 请求方式
     * @param int $https https协议
     * @return bool|mixed
     */
    public static function curl($url, $params = false, $ispost = 0, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

    /*
     * 冒泡排序-倒叙
     *
     * By mtt
     *
     * 2018-5-11
     */
    public static function bubble_order_desc($arr, $orderValue)
    {
        //得到长度
        $count_num = count($arr);
        //倒叙
        for ($k = 1; $k < $count_num; $k++) {
            //对长度越来越少的一组数据 找出最大让其浮到最后
            for ($i = 0; $i < $count_num - $k; $i++) {
                if (self::isObjNull($orderValue)) {
                    if ($arr[$i] < $arr[$i + 1]) {//相邻比较
                        $tem = $arr[$i];
                        $arr[$i] = $arr[$i + 1];
                        $arr[$i + 1] = $tem;
                    }
                } else {
                    if ($arr[$i][$orderValue] < $arr[$i + 1][$orderValue]) {//相邻比较
                        $tem = $arr[$i];
                        $arr[$i] = $arr[$i + 1];
                        $arr[$i + 1] = $tem;
                    }
                }
            }
        }
        return $arr;
    }

    /*
    * 冒泡排序-正序
    *
    * By mtt
    *
    * 2018-5-11
    */
    public static function bubble_order_asc($arr, $orderValue)
    {
        //得到长度
        $count_num = count($arr);
        //正序
        for ($k = 1; $k < $count_num; $k++) {
            //对长度越来越少的一组数据 找出最大让其浮到最后
            for ($i = 0; $i < $count_num - $k; $i++) {
                if (self::isObjNull($orderValue)) {
                    if ($arr[$i] > $arr[$i + 1]) {//相邻比较
                        $tem = $arr[$i];
                        $arr[$i] = $arr[$i + 1];
                        $arr[$i + 1] = $tem;
                    }
                } else {
                    if ($arr[$i][$orderValue] > $arr[$i + 1][$orderValue]) {//相邻比较
                        $tem = $arr[$i];
                        $arr[$i] = $arr[$i + 1];
                        $arr[$i + 1] = $tem;
                    }
                }
            }
        }
        return $arr;
    }

    /*
     * 脱敏处理方法
     *
     * By TerryQi
     *
     * 2018-06-06
     *
     * type为脱敏数据类型
     *
     * name：为姓名进行脱敏
     * phone：为手机号进行脱敏
     *
     */
    public static function remove_sensitive($type, $val)
    {
        switch ($type) {
            case "phone":
                return substr_replace($val, "****", 4, 4);
            case "name":
                return self::substr_cut($val);
        }
    }

    /*
     * PHP只显示姓名首尾字符，隐藏中间字符并用*替换
     */
    public static function substr_cut($user_name)
    {
        $strlen = mb_strlen($user_name, 'utf-8');
        $firstStr = mb_substr($user_name, 0, 1, 'utf-8');
        $lastStr = mb_substr($user_name, -1, 1, 'utf-8');

        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
    }


    /**
     * 请求接口LOG
     * @param string $logPath 请求接口
     * @param string $logIp IP地址
     * @param array $logData  请求参数
     */
    public static function requestLog($logPath="",$logIp="",$logData=[]){
        $LOGO_NO = 'LOG'.date('Ymdhis',time()).rand(1000000,10000000);
        Session::put('LOGO_NO', $LOGO_NO);
        Log::info('[Request]  '.$LOGO_NO.'  '. $logPath . "(" . $logIp . ")   " .json_encode($logData));
    }
    /**
     * 过程中接口LOG
     * @param string $logModular 模块
     * @param string $logData 数据
     * @param string $logContent 备注
     */
    public static function processLog($logModular="", $logContent="", $logData=""){
        $LOGO_NO = Session::get("LOGO_NO");
        if(is_array($logData)){
            $logData = json_encode($logData,true);
        }
        if($logContent){
            Log::info('[Process]  '.$LOGO_NO.'  '.$logContent.'  '.$logModular .'  ' . $logData );
        }
        else{
            Log::info('[Process]  '.$LOGO_NO.'  '.$logModular .'  ' . $logData );
        }
    }
    /**
     * 返回接口LOG
     * @param string $logModular 模块
     * @param array $logData 数据
     */
    public static function backLog($logModular="", $logData=[]){
        $LOGO_NO = Session::get("LOGO_NO");
        $log = array(
            'code' => $logData['code'],
            'result' => $logData['result'],
            'message' => $logData['message'],
        );
        if(array_key_exists('ret',$logData)){
            $log['ret'] = $logData['ret'];
        }
        Log::info('[Back]  '.$LOGO_NO.'  '. $logModular .'  ' .json_encode($log,true));
        Session::remove("LOGO_NO");
    }
    /**
     * 过程报错接口LOG
     * @param string $logData 数据
     */
    public static function errorLog($logData=""){
        $LOGO_NO = Session::get("LOGO_NO");
        if(!$LOGO_NO){
            $LOGO_NO = 'LOG'.date('Ymdhis',time()).rand(1000000,10000000);
            Session::put('LOGO_NO', $LOGO_NO);
        }
        if(is_array($logData)){
            $logData = json_encode($logData,true);
        }
        Log::info('[Error]  '.$LOGO_NO.'  '. $logData );
        Session::remove("LOGO_NO");
    }
}
<?php
/**
 * File_Name:ApiResponse.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 14:37
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Components\Utils;
use App\Components\HJGL\SystemParameterManager;

class ApiResponse
{
    //成功
    const SUCCESS_CODE = 200;

    //通用错误
    const UNKNOW_ERROR = 999;   //未知错误
    const MISSING_PARAM = 901;   //缺少参数
    const INNER_ERROR = 902;    //逻辑错误
    const PARAM_ERROR = 903;    //参数错误
    const POWER_ERROR = 904;    //权限不足

    //细化错误
    const TOKEN_LOST = 101; //缺少token
    const TOKEN_ERROR = 102;     //token校验失败
    const USER_ID_LOST = 103;   //用户编码丢失
    const REGISTER_FAILED = 104;     //注册失败
    const NO_USER = 105;    //未找到用户
    const VERTIFY_ERROR = 106;   //验证码验证失败
    const PHONE_DUP = 107;   //手机号重复
    const PHONE_HAS_BEEN_SELECTED = 108; //号码已经被申请
    const PHONE_IS_NOT_EXIST = 109;  //号码不存在
    const UITIFY_ORDER_FAILED = 110;     //统一下单失败
    const PASSWORD_ERROR = 111;     //密码错误
    const SM_VERTIFY_SEND_ERROR= 112;     //短信验证码发送失败
    const PHONE_LOST= 113;     //手机号缺失
    const SM_VERTIFY_LOST= 114;     //短信验证码缺失
    const PASSWORD_LOST= 115;     //密码缺失
    const SM_VERTIFY_ERROR= 116;     //短信验证码验证失败
    const INVITE_LOST= 117;     //邀请码缺失
    const INVITE_ERROR= 118;     //错误的邀请码
    const INVITE_ZERO= 119;     //邀请码失效或使用次数达到上限
    const OPENID_LOST= 120;     //openid缺失
    const VERTIFY_LOST= 121;     //验证码缺失
    const TOOL_IS_NOT_EXIST = 122;  //设备不存在

    //公共接口相关错误
    const API_PROCODE_ERROR = 201;  //公共接口授权失败
    const API_PROCODE_LOST = 202;   //缺少pro_code


    //日期类错误
    const DATE_EARLY = 301; //早于日期
    const DATE_LATE = 302;  //晚于日期
    const DATE_NOT_INT_SCOPE = 303; //日期不在范围内

    //业务类错误
    const NO_VALID_ENTERPRISE = 401; //没有进行企业认证
    const NO_VALID_USERINFO = 402;    //没有进行个人信息认证

    //沈机动力错误码
    const NO_ENTERPRISE = 501;  //暂无企业信息

    //映射错误信息
    public static $returnMessage = [
        self::SUCCESS_CODE => '操作成功',
//
        self::UNKNOW_ERROR => '未知错误',
        self::MISSING_PARAM => '缺少参数',
        self::INNER_ERROR => '内部错误',
//
        self::TOKEN_LOST => '缺少token',
        self::TOKEN_ERROR => 'token校验失败',
        self::USER_ID_LOST => '缺少用户编码',
        self::REGISTER_FAILED => '注册失败',
        self::NO_USER => '未找到用户',
        self::VERTIFY_ERROR => '验证码验证失败',
        self::PHONE_DUP => '手机号重复',
        self::PHONE_HAS_BEEN_SELECTED => '号码已经被申请',
        self::PHONE_IS_NOT_EXIST => '号码不存在',
        self::UITIFY_ORDER_FAILED => '统一下单失败',

        self::API_PROCODE_ERROR => '公共接口授权失败',
        self::API_PROCODE_LOST => '缺少pro_code',

        self::DATE_EARLY => '早于日期',
        self::DATE_LATE => '晚于日期',
        self::DATE_NOT_INT_SCOPE => '日期不在范围内',

        self::NO_VALID_ENTERPRISE => '没有进行企业认证',
        self::NO_VALID_USERINFO => '没有进行个人信息认证',

        self::NO_ENTERPRISE => '暂无企业信息',

        self::TOOL_IS_NOT_EXIST => '设备不存在',

    ];

    //格式化返回(旧)
    public static function makeResponse($result, $ret, $code, $mapping_function = null, $params = [])
    {
        $rsp = [];
        $rsp['code'] = $code;
        if ($result === true) {
            $rsp['result'] = true;
            $rsp['message'] = self::$returnMessage[$code];
            if ($ret) {
                $rsp['ret'] = $ret;
            }
        } else {
            $rsp['result'] = false;
            if ($ret) {
                $rsp['message'] = $ret;
            } else {
                if (array_key_exists($code, self::$returnMessage)) {
                    $rsp['message'] = self::$returnMessage[$code];
                } else {
                    $rsp['message'] = 'undefind error code';
                }
            }
        }
        Utils::backLog(__METHOD__, $rsp);
        return response()->json($rsp);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/19 0019
 * Time: 上午 10:28
 */
namespace App\Components\HJGL;

use App\Models\HJGL\UserInfo;
use App\Components\Utils;
class UserInfoManager{
    /*
     * 根据id获取用户
     *
     * By Yuyang
     *
     * 2019-02-19
     */
    public static function getById($id){
        $info = UserInfo::where('id','=',$id)->first();
        return $info;
    }

    /*
     * 根据openid获取用户
     *
     * By Yuyang
     *
     * 2019-02-19
     */
    public static function getByOpenId($openid){
        $info = UserInfo::where('openid','=',$openid)->first();
        return $info;
    }

    /*
     * 根据手机号获取用户
     *
     * By Yuyang
     *
     * 2019-02-19
     */
    public static function getByPhone($phone){
        $info = UserInfo::where('hj_phone','=',$phone)->first();
        return $info;
    }

    /*
     * 编辑信息
     *
     * By Yuyang
     *
     * 2019-02-19
     */
    public static function serInfo($info,$data){
        if (array_key_exists('type', $data) && !Utils::isObjNull($data['type'])) {
            $info->type = array_get($data, 'type');
        }
        if (array_key_exists('openid', $data) && !Utils::isObjNull($data['openid'])) {
            $info->openid = array_get($data, 'openid');
        }
        if (array_key_exists('nick_name', $data) && !Utils::isObjNull($data['nick_name'])) {
            $info->nick_name = array_get($data, 'nick_name');
        }
        if (array_key_exists('sex', $data) && !Utils::isObjNull($data['sex'])) {
            $info->sex = array_get($data, 'sex');
        }
        if (array_key_exists('city', $data) && !Utils::isObjNull($data['city'])) {
            $info->city = array_get($data, 'city');
        }
        if (array_key_exists('province', $data) && !Utils::isObjNull($data['province'])) {
            $info->province = array_get($data, 'province');
        }
        if (array_key_exists('country', $data) && !Utils::isObjNull($data['country'])) {
            $info->country = array_get($data, 'country');
        }
        if (array_key_exists('headimgurl', $data) && !Utils::isObjNull($data['headimgurl'])) {
            $info->headimgurl = array_get($data, 'headimgurl');
        }
        if (array_key_exists('hj_phone', $data) && !Utils::isObjNull($data['hj_phone'])) {
            $info->hj_phone = array_get($data, 'hj_phone');
        }
        if (array_key_exists('hj_name', $data) && !Utils::isObjNull($data['hj_name'])) {
            $info->hj_name = array_get($data, 'hj_name');
        }
        if (array_key_exists('hj_sex', $data) && !Utils::isObjNull($data['hj_sex'])) {
            $info->hj_sex = array_get($data, 'hj_sex');
        }
        if (array_key_exists('hj_city', $data) && !Utils::isObjNull($data['hj_city'])) {
            $info->hj_city = array_get($data, 'hj_city');
        }
        if (array_key_exists('hj_province', $data) && !Utils::isObjNull($data['hj_province'])) {
            $info->hj_province = array_get($data, 'hj_province');
        }
        if (array_key_exists('hj_country', $data) && !Utils::isObjNull($data['hj_country'])) {
            $info->hj_country = array_get($data, 'hj_country');
        }
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $info->status = array_get($data, 'status');
        }
        return $info;
    }
}
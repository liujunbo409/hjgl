<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/18 0018
 * Time: 上午 9:56
 */
namespace App\Components\HJGL;

use App\Models\HJGL\AccessToken;
use App\Components\Utils;

class AccessTokenManager{
    /*
     * 根据条件获取access_token
     *
     * By Yuyang
     *
     * 2019-2-18
     */
    public static function getOne(){
        $info=new AccessToken();
        $info = $info->orderby('id', 'desc');
        $info = $info->first();
        return $info;
    }

    public static function setInfo($info,$data){
        if (array_key_exists('access_token', $data) && !Utils::isObjNull($data['access_token'])) {
            $info->access_token = array_get($data, 'access_token');
        }
        if (array_key_exists('get_time', $data) && !Utils::isObjNull($data['get_time'])) {
            $info->get_time = array_get($data, 'get_time');
        }
        if (array_key_exists('max_time', $data) && !Utils::isObjNull($data['max_time'])) {
            $info->max_time = array_get($data, 'max_time');
        }
        return $info;
    }
}
<?php

namespace App\Http\Controllers\HJGL\API;

use App\Components\HJGL\UserInfoManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\HJGL\VertifyManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;


class MyController extends Controller{

    public function index(Request $request){
        $session = $request->session()->get('wechat_user');
        if(!isset($session['original']['openid']) || empty($session['original']['openid'])){
            return view('HJGL.user.index.lose');
        }
        $user_info = UserInfoManager::getByOpenId($session['original']['openid']);
        $user_info->nick_name = isset($session['original']['nickname'])?$session['original']['nickname'] :$user_info->nick_name ;
        $user_info->sex = isset($session['original']['sex'])?$session['original']['sex'] :$user_info->sex ;
        $user_info->headimgurl = isset($session['original']['headimgurl'])?$session['original']['headimgurl'] :$user_info->headimgurl ;

        return view('HJGL.user.my.index',['user_info'=>$user_info]);
    }

    public function info(Request $request){
        $session = $request->session()->get('wechat_user');
        if(!isset($session['original']['openid']) || empty($session['original']['openid'])){
            return view('HJGL.user.index.lose');
        }
        $user_info = UserInfoManager::getByOpenId($session['original']['openid']);
        return view('HJGL.user.my.info',['user_info'=>$user_info]);
    }

    public function info_save(Request $request){
        $data = $request->all();
        $session = $request->session()->get('hj','');
        if(empty($session) || !isset($session['hj_phone']) || empty($session['hj_phone'])){
            return ApiResponse::makeResponse(false, '登录信息已失效,请重新进入', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('openid', $data) || Utils::isObjNull($data['openid'])) {
            return ApiResponse::makeResponse(false, '关键信息获取失败', ApiResponse::MISSING_PARAM);
        }
        $info = UserInfoManager::getByOpenId($data['openid']);
        if(empty($info)){
            return view('HJGL.user.perfect.perfectPhone');
        }else{
            $data = $request->all();
            if (!array_key_exists('hj_name', $data) || Utils::isObjNull($data['hj_name'])) {
                return ApiResponse::makeResponse(false, '姓氏缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('hj_sex', $data) || Utils::isObjNull($data['hj_sex'])) {
                return ApiResponse::makeResponse(false, '姓别缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('hj_province', $data) || Utils::isObjNull($data['hj_province'])) {
                return ApiResponse::makeResponse(false, '省缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('hj_city', $data) || Utils::isObjNull($data['hj_city'])) {
                return ApiResponse::makeResponse(false, '市缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('hj_area', $data) || Utils::isObjNull($data['hj_area'])) {
                return ApiResponse::makeResponse(false, '区缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('hj_address', $data) || Utils::isObjNull($data['hj_address'])) {
                return ApiResponse::makeResponse(false, '详细地址缺失', ApiResponse::MISSING_PARAM);
            }

            $user = UserInfoManager::serInfo($info,$data);
            $user->save();
            return ApiResponse::makeResponse(true, '修改个人信息成功', ApiResponse::SUCCESS_CODE);
        }
    }

    public function phone(Request $request){
        $session = $request->session()->get('wechat_user');
        if(!isset($session['original']['openid']) || empty($session['original']['openid'])){
            return view('HJGL.user.index.lose');
        }
        $user_info = UserInfoManager::getByOpenId($session['original']['openid']);
        return view('HJGL.user.my.phone',['user_info'=>$user_info]);
    }

    public function phone_save(Request $request){

    }

}
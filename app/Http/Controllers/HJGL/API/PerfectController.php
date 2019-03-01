<?php

namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use App\Components\HJGL\UserInfoManager;
use App\Models\HJGL\UserInfo;
use Illuminate\Http\Request;
use App\Components\HJGL\VertifyManager;
use App\Http\Controllers\ApiResponse;
use App\Components\Utils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use EasyWeChat\Factory;

class PerfectController extends Controller{
    //判断微信用户是否录入手机号信息
    public function perfect_phone(Request $request){
        $session = $request->session()->get('wechat_user','');
        $openid = isset($session['original']['openid']) ? $session['original']['openid'] : '';
        if(empty($session) || empty($openid)){
            return view('HJGL.user.index.lose');
        }
        $user = UserInfoManager::getByOpenId($openid);
        if(empty($user) || empty($user->hj_phone)){
            return view('HJGL.user.perfect.perfectPhone');
        }else{
            return redirect('/api/perfect_info');
        }
    }

    public function perfect_phone_save(Request $request){
        $data = $request->all();
        $session = $request->session()->get('wechat_user','');
        $openid = isset($session['original']['openid']) ? $session['original']['openid'] : '';
        if(empty($session) || empty($openid)){
            return view('HJGL.user.perfect.lose');
        }
        if (!array_key_exists('hj_phone', $data) || Utils::isObjNull($data['hj_phone'])) {
            return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::PHONE_LOST);
        }
        if (!array_key_exists('sm_validate', $data) || Utils::isObjNull($data['sm_validate'])) {
            return ApiResponse::makeResponse(false, '短信验证码缺失', ApiResponse::SM_VERTIFY_LOST);
        }
        $user=UserInfoManager::getByPhone($data['hj_phone']);
        if($user){
            return ApiResponse::makeResponse(false, '手机号已存在', ApiResponse::PHONE_HAS_BEEN_SELECTED);
        }else{
            $ys_sm = VertifyManager::judgeVertifyCode($data['hj_phone'], $data['sm_validate']);
            if (!$ys_sm) {
                return ApiResponse::makeResponse(false, '短信验证码验证失败', ApiResponse::SM_VERTIFY_ERROR);
            }
            $info = new UserInfo();
            $user = UserInfoManager::serInfo($info,$data);
            if (array_key_exists('nickname', $session['original']) && !Utils::isObjNull($session['original']['nickname'])) {
                $user->nick_name = $session['original']['nickname'];
            }
            if (array_key_exists('sex', $session['original']) && !Utils::isObjNull($session['original']['sex'])) {
                $user->sex = $session['original']['sex'];
            }
            if (array_key_exists('city', $session['original']) && !Utils::isObjNull($session['original']['city'])) {
                $user->city = $session['original']['city'];
            }
            if (array_key_exists('province', $session['original']) && !Utils::isObjNull($session['original']['province'])) {
                $user->province = $session['original']['province'];
            }
            if (array_key_exists('headimgurl', $session['original']) && !Utils::isObjNull($session['original']['headimgurl'])) {
                $user->headimgurl = $session['original']['headimgurl'];
            }
            if (array_key_exists('country', $session['original']) && !Utils::isObjNull($session['original']['country'])) {
                $user->country = $session['original']['country'];
            }
            $user->openid = $openid;
            $user->save();
            $put = array(
                'hj_phone'=>$data['hj_phone']
            );
            $request->session()->put('hj',$put);
            return ApiResponse::makeResponse(true, '首次保存个人手机号码成功', ApiResponse::SUCCESS_CODE);
        }
    }

    //判断用户是否录入详细信息
    public function perfect_info(Request $request){
        $session = $request->session()->get('wechat_user','');
        $openid = isset($session['original']['openid']) ? $session['original']['openid'] : '';
        if(empty($openid)){
            return view('HJGL.user.perfect.perfectPhone');
        }else{
            $user = UserInfoManager::getByOpenId($openid);
            if(empty($user) || empty($user->hj_phone)){
                return redirect('/api/perfect_phone');
            }else if(empty($user->hj_name) || empty($user->hj_sex) || empty($user->hj_province) || empty($user->hj_city) || empty($user->hj_area) || empty($user->hj_address)){
                return view('HJGL.user.perfect.perfectInfo');
            }else{
                return redirect('/api/hjjc/index');
            }
        }
    }

    public function perfect_info_save(Request $request){
        $session = $request->session()->get('hj','');
        if(empty($session) || !isset($session['hj_phone']) || empty($session['hj_phone'])){
            return ApiResponse::makeResponse(false, '登录信息已失效,请重新进入', ApiResponse::MISSING_PARAM);
        }
        $info = UserInfoManager::getByPhone($session['hj_phone']);
        if(empty($info) || !isset($info['hj_phone']) || empty($info['hj_phone'])){
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
            return ApiResponse::makeResponse(true, '首次保存个人信息成功', ApiResponse::SUCCESS_CODE);
        }
    }

    //发送验证码
    public function validateNewPhone(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('hj_phone', $data) || Utils::isObjNull($data['hj_phone'])) {
            return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::PHONE_LOST);
        }
        $result = VertifyManager::sendVertify($data['hj_phone']);
        if($result){
            return ApiResponse::makeResponse(true,'短信验证码已发送', ApiResponse::SUCCESS_CODE);
        }
        return ApiResponse::makeResponse(false, '短信验证码发送失败', ApiResponse::SM_VERTIFY_SEND_ERROR);
    }
}
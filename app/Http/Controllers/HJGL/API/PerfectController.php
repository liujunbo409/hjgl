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
    public function perfect_phone(Request $request){
        $session = $request->session()->get('wechat_user','');
        if(empty($session)){
            return view('HJGL.user.perfect.perfectPhone');
        }
        $openid = isset($session['original']['openid']) ? $session['original']['openid'] : '';
        $user = UserInfoManager::getByOpenId($openid);
        if(empty($user) || empty($user->hj_phone)){
            return view('HJGL.user.perfect.perfectPhone');
        }else{
            $put = array(
                'phone'=>$user->hj_phone,
            );
            $request->session()->put('hj', $put );
            return redirect('/api/perfect_info');
        }
    }

    public function perfect_phone_save(Request $request){
        $data = $request->all();
        if (!array_key_exists('hj_phone', $data) || Utils::isObjNull($data['hj_phone'])) {
            return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::PHONE_LOST);
        }
        if (!array_key_exists('sm_validate', $data) || Utils::isObjNull($data['sm_validate'])) {
            return ApiResponse::makeResponse(false, '短信验证码缺失', ApiResponse::SM_VERTIFY_LOST);
        }
        $user=UserInfoManager::getByPhone($data['hj_phone']);
        if($user){
            return ApiResponse::makeResponse(false, '手机号已存在', ApiResponse::PHONE_HAS_BEEN_SELECTED);
        }
        $ys_sm = VertifyManager::judgeVertifyCode($data['hj_phone'], $data['sm_validate']);
        if (!$ys_sm) {
            return ApiResponse::makeResponse(false, '短信验证码验证失败', ApiResponse::SM_VERTIFY_ERROR);
        }
        $info = new UserInfo();
        $user = UserInfoManager::serInfo($info,$data);
        $user->save();
        $put = array(
            'phone'=>$data['hj_phone']
        );
        $request->session()->put('hj',$put);
        return ApiResponse::makeResponse(true, '首次保存个人手机号码成功', ApiResponse::SUCCESS_CODE);
    }

    public function perfect_info(Request $request){
        $session = $request->session()->get('hj','');
        if(empty($session) || empty($session['hj_phone'])){
            return view('HJGL.user.perfect.perfectPhone');
        }else{
            $user = UserInfoManager::getByPhone($session['hj_phone']);
            if(empty($user) || empty($user->hj_phone) || empty($user->hj_name) || empty($user->hj_sex) || empty($user->hj_province) || empty($user->hj_city) || empty($user->hj_area) || empty($user->hj_address)){
                return view('HJGL.user.perfect.perfectInfo');
            }else{
                return redirect('/api/hjjc/index');
            }
        }
    }

    public function perfect_info_save(Request $request){
        $info = UserInfoManager::getByPhone($request->session()->get('hj',''));
        if(empty($info) || !isset($info['hj_phone']) || empty($info['hj_phone'])){
            return view('HJGL.user.perfect.perfectPhone');
        }else{
            $data = $request->all();
            if (!array_key_exists('hj_name', $data) || Utils::isObjNull($data['hj_name'])) {
                return ApiResponse::makeResponse(false, '姓氏缺失', ApiResponse::PHONE_LOST);
            }
            if (!array_key_exists('hj_sex', $data) || Utils::isObjNull($data['hj_sex'])) {
                return ApiResponse::makeResponse(false, '姓别缺失', ApiResponse::PHONE_LOST);
            }
            if (!array_key_exists('hj_province', $data) || Utils::isObjNull($data['hj_province'])) {
                return ApiResponse::makeResponse(false, '省缺失', ApiResponse::PHONE_LOST);
            }
            if (!array_key_exists('hj_city', $data) || Utils::isObjNull($data['hj_city'])) {
                return ApiResponse::makeResponse(false, '市缺失', ApiResponse::PHONE_LOST);
            }
            if (!array_key_exists('hj_area', $data) || Utils::isObjNull($data['hj_area'])) {
                return ApiResponse::makeResponse(false, '区缺失', ApiResponse::PHONE_LOST);
            }
            if (!array_key_exists('hj_address', $data) || Utils::isObjNull($data['hj_address'])) {
                return ApiResponse::makeResponse(false, '详细地址缺失', ApiResponse::PHONE_LOST);
            }
            $user = UserInfoManager::serInfo($info,$data);
            $user->save();
            $put = array(
                'phone'=>$data['hj_phone'],
                ''
            );
            $request->session()->put('hj',$put);
            return ApiResponse::makeResponse(true, '首次保存个人信息成功', ApiResponse::SUCCESS_CODE);
        }
    }

    public function validateNewPhone(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('hj_phone', $data) || Utils::isObjNull($data['hj_phone'])) {
            return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::PHONE_LOST);
        }
        $is_have=UserInfoManager::getByPhone($data['hj_phone']);
        if($is_have){
            return ApiResponse::makeResponse(false, '手机号已存在', ApiResponse::PHONE_HAS_BEEN_SELECTED);
        }
        $result = VertifyManager::sendVertify($data['hj_phone']);
        if($result){
            return ApiResponse::makeResponse(true,'短信验证码已发送', ApiResponse::SUCCESS_CODE);
        }
        return ApiResponse::makeResponse(false, '短信验证码发送失败', ApiResponse::SM_VERTIFY_SEND_ERROR);
    }
}
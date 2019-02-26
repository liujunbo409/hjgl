<?php

namespace App\Http\Controllers\HJGL\API;

use App\Components\HJGL\UserInfoManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\HJGL\VertifyManager;
use App\Http\Controllers\ApiResponse;


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

    public function info(){
        return view('HJGL.user.my.info');
    }

    public function phone(){
        return view('HJGL.user.my.phone');
    }

}
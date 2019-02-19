<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/19 0019
 * Time: 上午 11:12
 */
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use EasyWeChat\Factory;
use App\Components\HJGL\UserInfoManager;
use App\Models\HJGL\UserInfo;

class PerfectController extends Controller{
    public function perfect_phone(Request $request){
        return view('HJGL.user.perfect.perfectPhone');
        return redirect('/api/perfect_info');
        $session = $request->session()->get('wechat_user','');
        $openid = isset($session['original']['openid']) ? $session['original']['openid'] : '';
        $user = UserInfoManager::getByOpenId($openid);
        if(empty($user) || empty($user->phone)){
            return view('HJGL.user.perfect.perfectPhone');
        }else{
            return redirect('/api/perfect_info');
        }
    }

    public function perfect_info(Request $request){
        return view('HJGL.user.perfect.perfectInfo');
    }
}
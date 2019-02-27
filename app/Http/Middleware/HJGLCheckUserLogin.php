<?php

/**
 * 检测后台用户是否登录中间件
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Components\HJGL\UserInfoManager;
use App\Models\HJGL\UserInfo;

class HJGLCheckUserLogin
{
    public function handle($request,Closure $next)
    {
        $tool_id = isset($request->tool_id)?$request->tool_id:'';
        if(!empty($tool_id)){
            $request->session()->put('tool_id',$tool_id);
        }

//        Log::info("cookie:" . json_encode($request->cookie('')));
//        if (!$request->cookie('admin')) {
//            cache(['admin' => 1], Carbon::now()->addSeconds(10));
//            return redirect('/admin/login');
//        }

//        if (!Cache::pull('wechat_user')) {
//            return redirect('/api/webScope');
//        }else{
//            cache(['wechat_user' => 1], Carbon::now()->addSeconds(60*30));
//        }


        //检测session中是否有登录信息
        if (!$request->session()->has('wechat_user')) {
            return redirect('/api/webScope');
        }
        $session = $request->session()->get('wechat_user','');
        $openid = isset($session['original']['openid']) ? $session['original']['openid'] : '';
        if(empty(!$session) || empty(!$openid)){
            return redirect('/api/lose');
        }else{
            $user = UserInfoManager::getByOpenId($openid);
            if(empty($user) || empty($user->hj_phone)){
                return redirect('/api/perfect_phone');
            }else if(empty($user->hj_name) || empty($user->hj_sex) || empty($user->hj_province) || empty($user->hj_city) || empty($user->hj_area) || empty($user->hj_address)){
                return redirect('/api/perfect_info');
            }else{
                return $next($request);
            }
        }
    }

}

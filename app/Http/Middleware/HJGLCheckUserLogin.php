<?php

/**
 * 检测后台用户是否登录中间件
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HJGLCheckUserLogin
{
    public function handle($request, Closure $next)
    {
//        Log::info("cookie:" . json_encode($request->cookie('')));
//        if (!$request->cookie('admin')) {
//            cache(['admin' => 1], Carbon::now()->addSeconds(10));
//            return redirect('/admin/login');
//        }
        if (!Cache::pull('wechat_user')) {
            return redirect('/api/webScope');
        }else{
            cache(['wechat_user' => 1], Carbon::now()->addSeconds(60*30));
        }


        //检测session中是否有登录信息
        if (!$request->session()->has('wechat_user')) {
            return redirect('/api/webScope');
        }
        return $next($request);
    }

}

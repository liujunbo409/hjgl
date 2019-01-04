<?php

/**
 * 检测后台用户是否登录中间件
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HJGLCheckAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        Log::info("cookie:" . json_encode($request->cookie('')));
//        if (!$request->cookie('admin')) {
//            cache(['admin' => 1], Carbon::now()->addSeconds(10));
//            return redirect('/admin/login');
//        }
        if (!Cache::pull('admin')) {
            return redirect('/admin/login');
        }else{
            cache(['admin' => 1], Carbon::now()->addSeconds(60*30));
        }


        //检测session中是否有登录信息
        if (!$request->session()->has('admin')) {
            return redirect('/admin/login');
        }
        return $next($request);
    }

}

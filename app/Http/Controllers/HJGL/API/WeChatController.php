<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class WeChatController extends Controller{
    public function test(Request $request){
        $data = $request->all();
        dd($data);
    }

    public function serve()
    {
        dd('13456');
//            Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志
//            $user = session('wechat.oauth_user'); // 拿到授权用户资料
//            Log::info($user);
//            $app = app('wechat.official_account');
//            $app->server->push(function($message){
//                return "欢迎关注 overtrue！";
//            });
//
//            return $app->server->serve();
    }

}
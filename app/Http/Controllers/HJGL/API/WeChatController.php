<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class WeChatController extends Controller{
    public function test(Request $request){
        $data = $request->all();
        Log::info($data);
        $echostr = isset($data["echostr"])? $data["echostr"]:'';
        $timestamp = isset($data["timestamp"])?$data["timestamp"]:'';
        $nonce = isset($data["nonce"])?$data["nonce"]:'';
        $signature  = isset($data["signature"])?$data["signature"]:'';
        $token = 'Token1234';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr,SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1( $tmpStr );
        if($tmpStr==$signature){
            return($echostr);
        }else{
            return('');
        }
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
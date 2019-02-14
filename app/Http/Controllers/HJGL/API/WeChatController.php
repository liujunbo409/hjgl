<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class WeChatController extends Controller{
//array(2) { ["access_token"]=> string(136) "18_JG5lzp4nOwqjdCqK6SXMHw2a6X8_JJla8MR-C1qTu9PJQqUQT9z87wSmiaKB4de71eT1fAdkRRR2QrDp3E9TfvCaZG_8MRz3CTSybHFKY2Hr6wny4C1FegiCpPwNZRcABACOG" ["expires_in"]=> int(7200) }
    public function getAccessToken(){
        dd('1');
        //请求url地址
        $appId = 'wxa683153d92d8a626';
        $appSecret = 'fd91eb650fe3aa85439fcacb40e1393a';
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
        //初始化curl
        $ch = curl_init($url);
        //3.设置参数
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //4.调用接口
        $res = curl_exec($ch);
        if(curl_errno($ch)){
            var_dump(curl_error($ch));
        }
        $resArr = json_decode($res,1);
        var_dump($resArr);
        //5.关闭curl
        curl_close($ch);
    }


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
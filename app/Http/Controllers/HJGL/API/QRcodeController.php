<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use EasyWeChat\Factory;


class QRcodeController extends Controller{

    public function test(Request $request){
        $jsapi_ticket = cache('jsapi_ticket', null);
        if(empty($jsapi_ticket)){
            $config = Config::get("wechat.official_account.default");
            $app = Factory::officialAccount($config);
            $accessToken = $app->access_token;
            $token = $accessToken->getToken();
            $url='https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$token['access_token'].'&type=jsapi';
            $json = file_get_contents($url);
            $re = json_decode($json);
            $jsapi_ticket = $re->ticket;
            cache(['jsapi_ticket'=>$re->ticket],$re->expires_in - 200);
        }
        $time = time();
        $noncestr = 'hkhKHKHJKhjk';
        $str_array = array(
            'noncestr'=>$noncestr,
            'jsapi_ticket'=>$jsapi_ticket,
            'timestamp'=>$time,
            'url'=>'http://hj.lljiankang.top/api/QRcode/test',
        );
        ksort($str_array);
        $str = '';
        foreach($str_array as $k=>$v){
            $str .= $k.'='.$v.'&';
        }
        $str = substr($str,0,-1);
        $signature = sha1($str);
        $data = array(
            'appId'=>'wxa683153d92d8a626',
            'timestamp'=>$time,
            'nonceStr'=>$noncestr,
            'signature'=> $signature,
            'jsApiList'=> '[scanQRCode]'
        );
        return view('HJGL.user.qrcode.test',['data'=>$data]);
    }

    public function index($tool_id){
        dd($tool_id);
        return('到达测试');
    }


}
<?php
namespace App\Services;

use EasyWeChat\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class WeChat{

	public static function sendAlertMsg($title, $service, $status, $message, $remark) {
        $config = Config::get("wechat.official_account.default");
        date_default_timezone_set('Asia/Shanghai');
        $app = Factory::officialAccount($config); // 公众号
        $templateId = "yKeLl9ouZasvoz258RQyZ41YT7w-L1JVpufpcAkSiyo";   //这里是模板ID，自行去公众号获取
        $currentTime = date('Y-m-d H:i:s',time());
        $host = "hj.lljiankang.top";   //你的域名

        $openids = ["oBOlU543B3spolssPAOvA7jToUdQ"];   //关注微信公众号的openid，前往公众号获取
        foreach ($openids as $v) {
            $result = $app->template_message->send([
                'touser' => $v,
                'template_id' => $templateId,
                'url' => 'hj.lljiankang.top',  //上边的域名
                'data' => [
                    'first' => $title,
                    'keyword1' => $currentTime,
                    'keyword2' => $host,
                    'keyword3' => $service,
                    'keyword4' => $status,
                    'keyword5' =>$message,
                    'remark' => $remark,
                ]
            ]);
            Log::info("template send result:", $result);
        }
        return Config::get("error.0");
    }


}
<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use EasyWeChat\Factory;

class WeChatController extends Controller{
    public function serve(){
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志
        $app = app('wechat.official_account');
        dd($app);
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });
        return $app->server->serve();
    }

    //网页授权
    public function webScope(Request $request){
        $session = $request->session()->get('wechat_user','');
        if(empty($session)){
            $config = Config::get("wechat.official_account.default");
            $app = Factory::officialAccount($config); // 公众号
            $response = $app->oauth->scopes(['snsapi_userinfo'])->setRequest($request)->redirect();
            return $response;
        }else{
            return redirect('/api/perfect_phone'); // 跳转
        }
    }

    public function getInfo(Request $request){
        $config = Config::get("wechat.official_account.default");
        $app = Factory::officialAccount($config); // 公众号
        $user = $app->oauth->user();
        $request->session()->put('wechat_user', $user->toArray());//写入session
        return redirect('/api/perfect_phone'); // 跳转
    }

    //自定义菜单查询
    public function getMenu(){
        $config = Config::get("wechat.official_account.default");
        $app = Factory::officialAccount($config); // 公众号
        $list = $app->menu->list();
        dd($list);
    }

    //自定义菜单创建
    public function createMenu(){
        $config = Config::get("wechat.official_account.default");
        $app = Factory::officialAccount($config); // 公众号
        $buttons = [
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "环境检测",
                        "url"  => "http://hj.lljiankang.top/api/hjjc/index"
                    ]
                ],
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "订单",
                        "url"  => "http://hj.lljiankang.top/api/order/index"
                    ]
                ],
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我的",
                        "url"  => "http://hj.lljiankang.top/api/my/index"
                    ]
                ],
            ],
        ];
        return $app->menu->create($buttons);
    }

    //删除自定义菜单
    public function delMenu(){
        $access = self::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$access->access_token;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        dd($output);
    }


}
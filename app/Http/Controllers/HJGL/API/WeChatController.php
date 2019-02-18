<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Services\WeChat;
use EasyWeChat\Factory;
use App\Components\HJGL\AccessTokenManager;
use App\Models\HJGL\AccessToken;

class WeChatController extends Controller{

    public function serve(){
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });
        return $app->server->serve();
    }

    //获取token
    public function getAccessToken(){
        $access_token = AccessTokenManager::getOne();
        if(empty($access_token) || date('Y-m-d H:i:s') > $access_token->max_time){
            $config = Config::get("wechat.official_account.default");
            $app = Factory::officialAccount($config); // 公众号
            $accessToken = $app->access_token;
            $token = $accessToken->getToken();
            if(isset($token['errcode'])){
                return('系统繁忙，此时请开发者稍候再试');
            }
            if(!isset($token['access_token']) || empty($token['access_token'])){
                return('参数缺失');
            }
            $info = new AccessToken();
            $info->access_token = $token['access_token'];
            $info->get_time = date('Y-m-d H:i:s');
            $info->max_time = date('Y-m-d H:i:s',time()+5400);
            $re = AccessTokenManager::setInfo($info,$info);
            $re->save();
            return $re;
        }
        return $access_token;
    }

    public function getInfo(Request $request){
        $config = Config::get("wechat.official_account.default");
        $app = Factory::officialAccount($config); // 公众号
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        $request->session()->put('wechat_user', $user->toArray());//写入session
        $session=$request->session('wechat_user');
        $targetUrl = empty($session->target_url) ? '/' : $session->target_url;
        header('location:'. $targetUrl); // 跳转到 user/profile
    }

    //网页授权
    public function webScope(Request $request){
        $config = Config::get("wechat.official_account.default");
        $app = Factory::officialAccount($config); // 公众号
        $oauth = $app->oauth;
        // 未登录
        $session=$request->session('wechat_user');
        dd($session);
        if (empty($session)) {
            $request->session()->put('target_url', '/api/webScope');//写入session
            return $oauth->redirect();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            // $oauth->redirect()->send();
        }
        // 已经登录过
        $user = $session;
        dd($user);
    }

    //自定义菜单查询
    public function getMenu(){
        $access = self::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$access->access_token;
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

    //自定义菜单创建
    public function createMenu(){
        $access = self::getAccessToken();
        $data = '{
      "button":[
      {
            "name":"菜单",
           "sub_button":[
            {
                "type":"view",
                "name":"进入网页",
                "url":"http://www.baidu.com"
            }]
       },
       {
            "name":"菜单",
           "sub_button":[
            {
                "type":"view",
                "name":"进入网页",
                "url":"http://www.baidu.com"
            }]
       }]
 }';
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access->access_token;
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

    //发送模板消息
    public function sendAlertMsg() {
        WeChat::sendAlertMsg("param1", "param2", "param3", "param4", "param5");
    }

    public function hjjc(Request $request){

        $data = $request->all();
        $infos = array(
            'ordernumber'=>'123456',
            'time1'=>'2019-01-01 00:00:00',
            'tool_ss' => array(
                array(
                    'toolid'=>'111111',
                    'time2'=>'2019-01-01 00:00:00',
                    'time_long'=>'24',
                    'about'=>'',
                    'CH2O'=>json_encode(array('1','2','3','4','5','6','7'),true),
                    'C6H6'=>array('31','61','20','61'),
                    'C8H10'=>array('12','56','75','12'),
                    'voc'=>array('12','46','23','86'),
                ),
                array(
                    'toolid'=>'222222',
                    'time2'=>'2019-01-01 00:00:00',
                    'time_long'=>'24',
                    'about'=>'',
                    'CH2O'=>"[123,456,78,48,48,49]",
                    'C6H6'=>array('31','61','20','61'),
                    'C8H10'=>array('12','56','75','12'),
                    'voc'=>array('12','46','23','86'),
                ),
                array(
                    'toolid'=>'333333',
                    'time2'=>'2019-01-01 00:00:00',
                    'time_long'=>'24',
                    'about'=>'',
                    'CH2O'=>"[111,222,333,444,555,49]",
                    'C6H6'=>array('31','61','20','61'),
                    'C8H10'=>array('12','56','75','12'),
                    'voc'=>array('12','46','23','86'),
                ),
            ),
        );
        $a = json_encode(array('1111','2222','3333'),true);
        return view('HJGL.user.hjjc.index', ['infos'=>$infos,'a'=>$a]);
    }


}
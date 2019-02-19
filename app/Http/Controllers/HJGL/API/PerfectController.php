<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/19 0019
 * Time: 上午 11:12
 */
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Services\WeChat;
use EasyWeChat\Factory;
use App\Components\HJGL\AccessTokenManager;
use App\Models\HJGL\AccessToken;

class PerfectController extends Controller{
    public function perfect_phone(Request $request){
        $config = Config::get("wechat.official_account.default");
        $app = Factory::officialAccount($config); // 公众号
        $response = $app->oauth->scopes(['snsapi_userinfo'])->setRequest($request)->redirect();
        return $response;
    }

    public function perfect_info(){
        $config = Config::get("wechat.official_account.default");
        $app = Factory::officialAccount($config); // 公众号
        $user = $app->oauth->user();
        dd($user);
    }
}
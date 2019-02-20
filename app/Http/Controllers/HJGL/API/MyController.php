<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/20 0020
 * Time: 上午 9:20
 */
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Services\WeChat;
use EasyWeChat\Factory;

class MyController extends Controller{

    public function index(Request $request){
        $user_info = $request->session()->get('wechat_user');

        return view('HJGL.user.my.index');
    }

    public function info(){
        return view('HJGL.user.my.info');
    }

    public function phone(){
        return view('HJGL.user.my.phone');
    }

}
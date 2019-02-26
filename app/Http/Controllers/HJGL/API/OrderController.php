<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/20 0020
 * Time: 上午 9:16
 */

namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use EasyWeChat\Factory;

class OrderController extends Controller{

    public function index(Request $request){
        $user_info = $request->session()->get('wechat_user');

        return view('HJGL.user.order.index');
    }

    public function loan(Request $request){
        $user_info = $request->session()->get('wechat_user');

        return view('HJGL.user.order.loan');
    }
}
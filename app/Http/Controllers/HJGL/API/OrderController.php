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
use App\Models\HJGL\UserOrder;
use App\Components\HJGL\UserOrderManager;
use App\Models\HJGL\UserLoan;
use App\Components\HJGL\UserLoanManager;

class OrderController extends Controller{

    public function index(Request $request){
        $session = $request->session()->get('wechat_user');

        $con_doing = array(
            'user_openid'=>$session['original']['openid'],
            'order_status'=>1
        );
        $order_doing = UserOrderManager::getListByCon($con_doing,false);

        $con_finish = array(
            'user_openid'=>$session['original']['openid'],
            'order_status'=>2
        );
        $order_finish = UserOrderManager::getListByCon($con_finish,false);

        return view('HJGL.user.order.index',['order_doing'=>$order_doing,'order_finish'=>$order_finish]);
    }

    public function loan(Request $request){
        $user_info = $request->session()->get('wechat_user');

        return view('HJGL.user.order.loan');
    }
}
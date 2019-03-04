<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/20 0020
 * Time: 上午 9:16
 */

namespace App\Http\Controllers\HJGL\API;

use App\Components\Utils;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HJGL\UserOrder;
use App\Components\HJGL\UserOrderManager;
use App\Models\HJGL\UserLoan;
use App\Components\HJGL\UserLoanManager;
use App\Models\HJGL\SystemParameter;
use App\Components\HJGL\SystemParameterManager;

class OrderController extends Controller{

    public function index(Request $request){
        $session = $request->session()->get('wechat_user');
        $con_doing = array(
            'user_openid'=>$session['original']['openid'],
            'order_status'=>array('1')
        );
        $order_doing = UserOrderManager::getListByCon($con_doing,false);
        $order_doing_tool = array();
        foreach($order_doing as $v){
            $order_doing_tool[$v->order_number] = explode(',',$v->tool_numstr);
        }

        $con_finish = array(
            'user_openid'=>$session['original']['openid'],
            'order_status'=>array('2')
        );
        $order_finish = UserOrderManager::getListByCon($con_finish,false);
        $order_finish_tool = array();
        foreach($order_finish as $v){
            $order_finish_tool[$v->order_number] = explode(',',$v->tool_numstr);
        }
        return view('HJGL.user.order.index',['order_doing'=>$order_doing,'order_doing_tool'=>$order_doing_tool,'order_finish'=>$order_finish,'order_finish_tool'=>$order_finish_tool]);
    }

    public function loan(Request $request){
        $data = $request->all();
        if(!array_key_exists('order_number',$data) || Utils::isObjNull($data['order_number'])){
            return('订单号未获取到');
        }
        $order = UserOrderManager::getByOrderNumber($data['order_number']);
        $con_arr = array(
            'order_number'=>$data['order_number'],
        );
        $user_loan = UserLoanManager::getListByCon($con_arr,false);
        foreach($user_loan as $v){
            $v->out_time = date('Y-m-d H:i:s',strtotime("now")-strtotime($order->created_at));
            $v->rent_total = SystemParameterManager::getRent($v->created_at,date('Y-m-d H:i:s'));
        }
        return view('HJGL.user.order.loan',['user_loan'=>$user_loan,'order'=>$order]);
    }
}
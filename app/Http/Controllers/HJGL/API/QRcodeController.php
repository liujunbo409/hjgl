<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HJGL\UserNopay;
use App\Components\HJGL\UserNopayManager;
use App\Components\HJGL\ToolManager;
use App\Components\Utils;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QRcodeController extends Controller{

    public function index(Request $request){
//        $con_arr = array(
//            'start_time'=>'1999-01-01 00:00:00',
//            'end_time'=>date('Y-m-d H:i:s',time() - 6),
//        );
//        $del = UserNopayManager::getListByCon($con_arr,false);
//        if(!empty($del) && $del->count() > 0){
//            foreach($del as $v){
//                $v->delete();
//            }
//        }
        $session = $request->session()->get('wechat_user','');
        $data = $request->all();
        $tool_num = isset($data['tool_num']) ? $data['tool_num'] : '';
        if(empty($tool_num)){
            return('设备编码获取失败');
        }
        if(is_numeric($tool_num)){
            return('设备编码错误');
        }
        $tool = ToolManager::getByNumber($tool_num);
        if(empty($tool)){
            return('设备获取失败');
        }
        if($tool->status != 2){
            return('该设备未启用');
        }
        if($tool->loan_status != 1){
            return('该设备已被借出或待校准');
        }
        if(!empty(cache($tool_num))){
            return('该设备正在被下单中');
        }else{
            $nopay = UserNopayManager::getById($session['original']['openid']);
            if(!empty($nopay)){
                $nopay->updated_at = date('Y-m-d H:i:s');
            }else{
                $nopay = new UserNopay();
                $nopay->user_openid = $session['original']['openid'];
                $nopay->tool_num = $tool_num;
            }
//            $nopay->save();
            $con_arr1 = array(
                'user_openid'=>$session['original']['openid'],
            );
            $nopay_s = UserNopayManager::getListByCon($con_arr1,false);
            $numbers = array();
            foreach($nopay_s as $v){
                $numbers[] = $v->tool_num;
//                cache([$v->tool_num=>$v->user_openid],0.1);
            }
            $number_json = json_encode($numbers);
            return view('HJGL.user.qrcode.nopay',['nopay_s'=>$nopay_s,'number_json'=>$number_json]);
        }
    }


    public function pay_PPhone(Request $request){
        $data = $request->all();
        $order_1 = explode(',',$data['order']);
        foreach($order_1 as $k=>$v){
            if($k/4 == ceil($k/4)){
                $nopay = UserNopayManager::getByToolNum($v);
                $nopay->work_start = $order_1[$k+1].' '.$order_1[$k+2];
                $nopay->work_time = $order_1[$k+3];
                $nopay->save();
            }
        }
        Log::info($order_1);
    }


}
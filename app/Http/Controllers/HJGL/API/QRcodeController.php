<?php
namespace App\Http\Controllers\HJGL\API;

use App\Models\HJGL\UserInfo;
use App\Components\HJGL\UserInfoManager;
use App\Models\HJGL\UserOrder;
use App\Components\HJGL\UserOrderManager;
use App\Models\HJGL\UserLoan;
use App\Components\HJGL\UserLoanManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HJGL\UserNopay;
use App\Components\HJGL\UserNopayManager;
use App\Components\HJGL\ToolManager;
use App\Components\Utils;
use App\Components\HJGL\VertifyManager;

class QRcodeController extends Controller{

    public function index(Request $request){
        $con_arr = array(
            'start_time'=>'1999-01-01 00:00:00',
            'end_time'=>date('Y-m-d H:i:s',time() - 60 * 10),
        );
        $del = UserNopayManager::getListByCon($con_arr,false);
        if(!empty($del) && $del->count() > 0){
            foreach($del as $v){
                $v->delete();
            }
        }
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
            $con_arr2 = array(
                'user_openid'=>$session['original']['openid'],
            );
            $nopay = UserNopayManager::getListByCon($con_arr2,false);
            if($nopay->count() > 0){
                foreach($nopay as $v){
                    $v->updated_at = date('Y-m-d H:i:s');
                    $v->save();
                }
            }
            $nopay = new UserNopay();
            $nopay->user_openid = $session['original']['openid'];
            $nopay->tool_num = $tool_num;
            $nopay->shop_id = $tool->shop_id;
            $nopay->shop_name = $tool->shop_name;
            $nopay->save();

            $con_arr1 = array(
                'user_openid'=>$session['original']['openid'],
            );
            $nopay_s = UserNopayManager::getListByCon($con_arr1,false);
            $numbers = array();
            foreach($nopay_s as $v){
                $numbers[] = $v->tool_num;
                cache([$v->tool_num=>$v->user_openid],10);
            }
            $number_json = json_encode($numbers);
            return view('HJGL.user.qrcode.nopay',['nopay_s'=>$nopay_s,'number_json'=>$number_json]);
        }
    }

    public function order_list(Request $request){
        $data = $request->all();
        $session = $request->session()->get('wechat_user','');
        $con_arr = array(
            'user_openid'=>$session['original']['openid']
        );
        $nopay = UserNopayManager::getListByCon($con_arr,false);
        if($nopay->count() == 0){
            return('您未选择检测器');
        }
        $order_1 = explode(',',$data['order']);
        foreach($order_1 as $k=>$v){
            if($k/4 == ceil($k/4)){
                $nopay = UserNopayManager::getByToolNum($v);
                $nopay->work_start = $order_1[$k+1].' '.$order_1[$k+2];
                $nopay->work_time = $order_1[$k+3];
                $nopay->save();
                cache([$nopay->tool_num=>$nopay->user_openid],10);
            }
        }
        return ApiResponse::makeResponse(true,'', ApiResponse::SUCCESS_CODE);
    }

    public function orderPhone(Request $request){
        $session = $request->session()->get('wechat_user','');
        $user_info = UserInfoManager::getByOpenId($session['original']['openid']);
        $con_arr = array(
            'user_openid'=>$session['original']['openid']
        );
        $nopay = UserNopayManager::getListByCon($con_arr,false);
        if($nopay->count() == 0){
            return('您未选择检测器');
        }
        return view('HJGL.user.qrcode.orderPhone',['user_info'=>$user_info]);
    }

    public function orderPhoneSave(Request $request){
        $data = $request->all();
        $session = $request->session()->get('wechat_user','');
        $con_arr = array(
            'user_openid'=>$session['original']['openid']
        );
        $nopay = UserNopayManager::getListByCon($con_arr,false);
        if($nopay->count() == 0){
            return ApiResponse::makeResponse(false, '您未选择检测器', ApiResponse::INNER_ERROR);
        }
        if($nopay->count() == 0){
            return ApiResponse::makeResponse(false, '相关信息获取失败', ApiResponse::MISSING_PARAM);
        }else{
            if (!array_key_exists('phone', $data) || Utils::isObjNull($data['phone'])) {
                return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('sm_validate', $data) || Utils::isObjNull($data['sm_validate'])) {
                return ApiResponse::makeResponse(false, '短信验证码缺失', ApiResponse::SM_VERTIFY_LOST);
            }
            $ys_sm = VertifyManager::judgeVertifyCode($data['phone'], $data['sm_validate']);
            if (!$ys_sm) {
                return ApiResponse::makeResponse(false, '短信验证码验证失败', ApiResponse::SM_VERTIFY_ERROR);
            }
            foreach($nopay as $v){
                $v->user_phone = $data['phone'];
                $v->save();
            }
        }
        return ApiResponse::makeResponse(true, '手机号验证成功', ApiResponse::SUCCESS_CODE);
    }

    public function paying(Request $request){
        $session = $request->session()->get('wechat_user','');
        $nopay_one = UserNopayManager::getById($session['original']['openid']);
        if(empty($nopay_one)){
            return('您未选择检测器');
        }
        $user = UserInfoManager::getByOpenId($session['original']['openid']);

        $con_arr = array(
            'user_openid'=>$session['original']['openid']
        );
        $nopay = UserNopayManager::getListByCon($con_arr,false);

        $tool_total = 0;
        $tool_numstr = '';
        foreach($nopay as $v){
            $tool_total++;
            if(empty($tool_numstr)){
                $tool_numstr = $v->tool_num;
            }else{
                $tool_numstr .= ','.$v->tool_num;
            }
        }
        if(empty($tool_numstr)){
            return('检测器编号未获取');
        }
        $order = array(
            'order_number' => date('YmdHis').mt_rand(1000,9999),
            'shop_id'=>$nopay_one->shop_id,
            'shop_name'=>$nopay_one->shop_name,
            'user_id'=>$user->id,
            'user_openid'=>$nopay_one->user_openid,
            'user_phone'=>$nopay_one->user_phone,
            'user_name'=>$user->hj_name,
            'tool_numstr'=>$tool_numstr,
            'tool_total'=>$tool_total,
            'address'=>$user->hj_address,
            'work_time'=>$nopay_one->work_time,
            'plan_minbacktime'=>date('Y-m-d H:i:s',strtotime($nopay_one->work_start) + $nopay_one->work_time * 60 * 60),
            'rent_total'=>'0',
            'rent_unpaid'=>'0',
            'deposit_total'=>'0',
            'deposit_unpaid'=>'0',
            'order_status'=>'1',
        );

        $data = new UserOrder();
        $order_in = UserOrderManager::setUserOrder($data,$order);
        $order_in->save();

        foreach($nopay as $v){
            $tool = ToolManager::getByNumber($v->tool_num);
            $tool->loan_status=2;
            $tool->save();

            $user_loan = new UserLoan();
            $data2 = array(
                'order_number' => $order['order_number'],
                'tool_id'=>$tool->id,
                'tool_number'=>$v->tool_num,
                'detection_address'=>$user->hj_address,
                'detection_duration'=>$v->work_time,
                'work_start'=>$v->work_start,
                'out_time'=>date('Y-m-d H:i:s'),
                'plan_minbacktime'=>date('Y-m-d H:i:s',strtotime($v->work_start) + $v->work_time * 60 * 60),
                'rent'=>'0',
                'rent_status'=>1,
                'deposit'=>'0',
                'deposit_status'=>'2',
                'loan_status'=>'1',
            );
            $user_loan_in = UserLoanManager::setInfo($user_loan,$data2);
            $user_loan_in->save();

            cache([$v->tool_num=>$nopay_one->user_openid],0.1);

            $v->delete();
        }
        return('支付成功');
    }

}
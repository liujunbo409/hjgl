<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HJGL\UserNopay;
use App\Components\HJGL\UserNopayManager;
use App\Components\HJGL\ToolManager;
use App\Components\Utils;
use Illuminate\Support\Facades\Cache;

class QRcodeController extends Controller{

    public function index(Request $request){
        $con_arr = array(
            'start_time'=>'1999-01-01 00:00:00',
            'end_time'=>date('Y-m-d H:i:s',time() - 60 * 10),
        );
        $del = ToolManager::getListByCon($con_arr,false);
        if(!empty($del) && $del->count() > 0){
            foreach($del as $v){
                $v->delete();
            }
        }
        dd($del);
        $session = $request->session()->get('wechat_user','');
        $data = $request->all();
        $tool_num = isset($data['tool_num']) ? $data['tool_num'] : '';
        if(empty($tool_num)){
            return('设备编码获取失败');
        }
        if(is_numeric($tool_num)){
            return('设备编码错误');
        }
        if(!empty(cache($tool_num))){
            return('该设备正在被下单中');
        }else{
            $nopay = UserNopayManager::getById($session['original']['openid']);
            if(empty($nopay) || $nopay->count() == 0){
                $nopay = new UserNopay();
                $nopay->user_openid = $session['original']['openid'];
                $nopay->tool_num = $tool_num;
            }
            $tool_array_num = explode(',',$nopay->tool_num);
            if(!in_array($tool_num,$tool_array_num)){
                $nopay->tool_num = $nopay->tool_num.','.$tool_num;
            }
            $nopay->save();
            foreach($tool_array_num as $v){
                cache([$v=>$nopay->user_openid],0.1);
            }
            $tool_arr = array(
                'numbers' =>$tool_array_num,
            );
            $tool_array = ToolManager::getListByCon($tool_arr,false);
            dd($tool_array);
        }
    }


}
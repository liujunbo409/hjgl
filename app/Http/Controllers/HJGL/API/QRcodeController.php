<?php
namespace App\Http\Controllers\HJGL\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HJGL\UserNopay;
use App\Components\HJGL\UserNopayManager;
use App\Components\HJGL\ToolManager;



class QRcodeController extends Controller{

    public function index(Request $request){
        $session = $request->session()->get('wechat_user');
        $tool_num = $request->tool_num;
        if(empty($tool_num)){
            return('设备编码获取失败');
        }
        if(is_numeric($tool_num)){
            return('设备编码错误');
        }
        $con_arr = array(
            'user_openid'=>$session['original']['openid'],
        );
        $nopay = UserNopayManager::getListByCon($con_arr,false);
        if($nopay->count() == 0){
            $nopay = new UserNopay();
            $nopay->user_openid = $session['original']['openid'];
            $nopay->tool_num = $tool_num;
        }else{
            $nopay->tool_num = $nopay->tool_num.','.$tool_num;
        }
        $tool_array_num = explode(',',$nopay->tool_num);
        foreach($tool_array_num as $v){
            cache([$v=>$nopay->user_openid],1);
        }
        $tool_arr = array(
            'numbers' =>$tool_array_num,
        );
        $tool_array = ToolManager::getListByCon($tool_arr,false);
        dd($tool_array);
        return('1');
    }


}
<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HJGL\UserNopay;
use App\Components\HJGL\UserNopayManager;



class QRcodeController extends Controller{

    public function index(Request $request){
        $session = $request->session()->get('wechat_user');
        $con_arr = array(
            'user_openid'=>$session['original']['openid'],
        );
        $nopay = UserNopayManager::getListByCon($con_arr,false);
        dd($nopay);
        dd($request->tool_id);
        return('到达测试');
    }


}
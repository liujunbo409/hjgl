<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/20 0020
 * Time: 上午 9:31
 */
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Services\WeChat;
use EasyWeChat\Factory;

class HjjcController extends Controller{

    public function index(Request $request){
        $user_info = $request->session()->get('wechat_user');

        $data = $request->all();
        $infos = array(
            'ordernumber'=>'123456',
            'time1'=>'2019-01-01 00:00:00',
            'tool_ss' => array(
                array(
                    'toolid'=>'111111',
                    'time2'=>'2019-01-01 00:00:00',
                    'time_long'=>'24',
                    'about'=>'',
                    'CH2O'=>json_encode(array('1','2','3','4','5','6','7'),true),
                    'C6H6'=>array('31','61','20','61'),
                    'C8H10'=>array('12','56','75','12'),
                    'voc'=>array('12','46','23','86'),
                ),
                array(
                    'toolid'=>'222222',
                    'time2'=>'2019-01-01 00:00:00',
                    'time_long'=>'24',
                    'about'=>'',
                    'CH2O'=>"[123,456,78,48,48,49]",
                    'C6H6'=>array('31','61','20','61'),
                    'C8H10'=>array('12','56','75','12'),
                    'voc'=>array('12','46','23','86'),
                ),
                array(
                    'toolid'=>'333333',
                    'time2'=>'2019-01-01 00:00:00',
                    'time_long'=>'24',
                    'about'=>'',
                    'CH2O'=>"[111,222,333,444,555,49]",
                    'C6H6'=>array('31','61','20','61'),
                    'C8H10'=>array('12','56','75','12'),
                    'voc'=>array('12','46','23','86'),
                ),
            ),
        );
        $a = json_encode(array('1111','2222','3333'),true);
        return view('HJGL.user.hjjc.index', ['infos'=>$infos,'a'=>$a]);
    }
}
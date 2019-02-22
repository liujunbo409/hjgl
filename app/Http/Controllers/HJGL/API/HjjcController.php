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
use App\Http\Controllers\ApiResponse;

class HjjcController extends Controller{
    public function index(Request $request){
        $user_info = $request->session()->get('wechat_user');

        $infos = array(
            'ordernumber'=>'123456',
            'time1'=>'2019-01-01 00:00:00',
            'tool_ss' => array(
                array(
                    'toolid'=>'111111',
                    'time2'=>'2019-01-01 00:00:00',
                    'time_long'=>'24',
                    'about'=>'',
                ),
                array(
                    'toolid'=>'222222',
                    'time2'=>'2019-01-01 00:00:00',
                    'time_long'=>'24',
                    'about'=>'',
                ),
                array(
                    'toolid'=>'333333',
                    'time2'=>'2019-01-01 00:00:00',
                    'time_long'=>'24',
                    'about'=>'',
                ),
            ),
        );
        $tool_ids = '[';
        foreach($infos['tool_ss'] as $v){
            $tool_ids .= $v['toolid'].',';
        }
        $tool_ids = trim($tool_ids,',');
        $tool_ids .=']';
        return view('HJGL.user.hjjc.index', ['infos'=>$infos,'tool_ids'=>$tool_ids]);
    }

    public function getCH2O(Request $request){
        $data = $request->all();
        if(empty($data['tool_id'])){
            return ApiResponse::makeResponse(true, $data, ApiResponse::MISSING_PARAM);
        }
        $a1 = 1;
        $b1 = 3;
        $c1 = 5;
        $d1 = 1;
        $data1 = '['.$a1.','.$b1.','.$c1.','.$d1.']';
        $a2 = 3;
        $b2 = 5;
        $c2 = 8;
        $d2 = 3;
        $data2 = '['.$a2.','.$b2.','.$c2.','.$d2.']';
        $a3 = 2;
        $b3 = 5;
        $c3 = 8;
        $d3 = 1;
        $data3 = '['.$a3.','.$b3.','.$c3.','.$d3.']';
        $arr = array(
            '111111'=>$data1,
            '222222'=>$data2,
            '333333'=>$data3,
        );
        if(!array_key_exists($data['tool_id'], $arr)){
            return ApiResponse::makeResponse(true, '不存在的设备编码', ApiResponse::MISSING_PARAM);
        }
        return ApiResponse::makeResponse(true,$arr[$data['tool_id']], ApiResponse::SUCCESS_CODE);
    }

    public function getC6H6(Request $request){
        $data = $request->all();
        if(empty($data['tool_id'])){
            return ApiResponse::makeResponse(true, $data, ApiResponse::MISSING_PARAM);
        }
        $a1 = 1;
        $b1 = 3;
        $c1 = 1;
        $d1 = 3;
        $data1 = '['.$a1.','.$b1.','.$c1.','.$d1.']';
        $a2 = 1;
        $b2 = 2;
        $c2 = 3;
        $d2 = 4;
        $data2 = '['.$a2.','.$b2.','.$c2.','.$d2.']';
        $a3 = 6;
        $b3 = 6;
        $c3 = 6;
        $d3 = 9;
        $data3 = '['.$a3.','.$b3.','.$c3.','.$d3.']';
        $arr = array(
            '111111'=>$data1,
            '222222'=>$data2,
            '333333'=>$data3,
        );
        if(!array_key_exists($data['tool_id'], $arr)){
            return ApiResponse::makeResponse(true, '不存在的设备编码', ApiResponse::MISSING_PARAM);
        }
        return ApiResponse::makeResponse(true,$arr[$data['tool_id']], ApiResponse::SUCCESS_CODE);
    }

    public function getC8H10(Request $request){
        $data = $request->all();
        if(empty($data['tool_id'])){
            return ApiResponse::makeResponse(true, $data, ApiResponse::MISSING_PARAM);
        }
        $a1 = 9;
        $b1 = 3;
        $c1 = 3;
        $d1 = 3;
        $data1 = '['.$a1.','.$b1.','.$c1.','.$d1.']';
        $a2 = 2;
        $b2 = 5;
        $c2 = 7;
        $d2 = 22;
        $data2 = '['.$a2.','.$b2.','.$c2.','.$d2.']';
        $a3 = 1;
        $b3 = 9;
        $c3 = 6;
        $d3 = 2;
        $data3 = '['.$a3.','.$b3.','.$c3.','.$d3.']';
        $arr = array(
            '111111'=>$data1,
            '222222'=>$data2,
            '333333'=>$data3,
        );
        if(!array_key_exists($data['tool_id'], $arr)){
            return ApiResponse::makeResponse(true, '不存在的设备编码', ApiResponse::MISSING_PARAM);
        }
        return ApiResponse::makeResponse(true,$arr[$data['tool_id']], ApiResponse::SUCCESS_CODE);
    }

    public function getVOC(Request $request){
        $data = $request->all();
        if(empty($data['tool_id'])){
            return ApiResponse::makeResponse(true, $data, ApiResponse::MISSING_PARAM);
        }
        $a1 = 1;
        $b1 = 31;
        $c1 = 52;
        $d1 = 12;
        $data1 = '['.$a1.','.$b1.','.$c1.','.$d1.']';
        $a2 = 31;
        $b2 = 51;
        $c2 = 18;
        $d2 = 31;
        $data2 = '['.$a2.','.$b2.','.$c2.','.$d2.']';
        $a3 = 12;
        $b3 = 51;
        $c3 = 18;
        $d3 = 11;
        $data3 = '['.$a3.','.$b3.','.$c3.','.$d3.']';
        $arr = array(
            '111111'=>$data1,
            '222222'=>$data2,
            '333333'=>$data3,
        );
        if(!array_key_exists($data['tool_id'], $arr)){
            return ApiResponse::makeResponse(true, '不存在的设备编码', ApiResponse::MISSING_PARAM);
        }
        return ApiResponse::makeResponse(true,$arr[$data['tool_id']], ApiResponse::SUCCESS_CODE);
    }

}
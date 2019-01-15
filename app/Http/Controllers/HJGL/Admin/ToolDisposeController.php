<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\HJGL\Admin;

use App\Components\HJGL\ToolManager;
use App\Components\HJGL\ToolDisposeManager;
use Illuminate\Http\Request;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;

class ToolDisposeController{
    /*
     * 设备列表
     *
     * By Yuyang
     *
     * 2018/12/28
     */
    public function index(Request $request){
        $data = $request->all();
        $curr_admin = $request->session()->get('admin');
        //条件搜索
        $search_word = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }else{
            $search_word = '';
        }
        if (array_key_exists('process', $data) && !Utils::isObjNull($data['process'])) {
            $process = array(
                "0" => $data['process'],
            );
        }else{
            $process = array(
                "0" => 99,
                "1" => 1,
                "2" => 2,
                "3" => 3,
            );
        }
        $con_arr = array(
            'search_word' => $search_word,
            'process' => $process,
        );
        $tools = ToolDisposeManager::getListByCon($con_arr,true);
        return view('HJGL.admin.toolDispose.index', [ 'datas' => $tools, 'con_arr' => $con_arr]);
    }

    /*
     * 设置设备状态
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        if (is_numeric($id) !== true) {
            return ApiResponse::makeResponse(false, '合规校验失败，请检查参数设备处理id', ApiResponse::INNER_ERROR);
        }
        $tooldispose = ToolDisposeManager::getById($id);
        if($tooldispose->process >3){
            return ApiResponse::makeResponse(false, '已是最终状态,无法继续操作', ApiResponse::INNER_ERROR);
        }else{
            $tooldispose->process = $data['process']+1;
            if($tooldispose->process == 4){
                $tool = ToolManager::getById($tooldispose->tool_id);
                $tool->status = 2;
                $tool->save();
            }
            $tooldispose->save();
            return ApiResponse::makeResponse(true, $tooldispose, ApiResponse::SUCCESS_CODE);
        }
    }

    /*
     * 设置处理详情
     *
     * By Yuyang
     *
     * 2019/01/08
     */
    public function info(Request $request){
        $data = $request->all();
        if(!array_key_exists('id', $data) || $data['id'] == ''){
            return ApiResponse::makeResponse(false, '设备id缺失', ApiResponse::MISSING_PARAM);
        }
        $toolDispose = ToolDisposeManager::getById($data['id']);
        $tool = ToolManager::getById($toolDispose->tool_id);
        if(empty($tool)){
            return '设备不存在';
        }else{
            $con_arr = array(
                'shop_id' => $data['id'],
            );
            return view('HJGL.admin.toolDispose.info', [ 'tool' => $tool ,'con_arr' => $con_arr , 'data' => $toolDispose]);
        }
    }
}
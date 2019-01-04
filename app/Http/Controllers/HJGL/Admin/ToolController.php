<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\HJGL\Admin;

use App\Components\HJGL\ShopManager;
use App\Components\HJGL\ToolManager;
use App\Models\HJGL\Tool;
use Hamcrest\Util;
use Illuminate\Http\Request;
use App\Components\Utils;
use App\Components\QNManager;
use App\Http\Controllers\ApiResponse;
use App\Components\HJGL\VertifyManager;

class ToolController{
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
        }
        $con_arr = array(
            'search_word' => $search_word,
        );
        $tools = ToolManager::getListByCon($con_arr,true);
        return view('HJGL.admin.tool.index', [ 'datas' => $tools, 'con_arr' => $con_arr]);
    }

    /*
     * 新建或修改设备
     *
     * By Yuyang
     *
     * 2018/12/28
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $tool = new Tool();
        if (array_key_exists('id', $data)) {
            $tool = ToolManager::getById($data['id']);
        }
        $upload_token = QNManager::uploadToken();
        $admin = $request->session()->get('admin');
        return view('HJGL.admin.tool.edit', ['admin' => $admin, 'data' => $tool,'upload_token' => $upload_token]);
    }


    //新建或编辑设备->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        if(!array_key_exists('id', $data) || $data['id'] == ''){
            if (!array_key_exists('number', $data) || $data['number'] == '') {
                return ApiResponse::makeResponse(false, '设备编号缺失', ApiResponse::MISSING_PARAM);
            }
            $tool = new Tool();
            $e_tool = ToolManager::getByNumber($data['number']);
            if ($e_tool) {
                return ApiResponse::makeResponse(false, "设备编号重复", ApiResponse::PHONE_DUP);
            }
            $tool = ToolManager::setTool($tool, $data);
        }else{
            $tool = ToolManager::getById($data['id']);
            $tool->number = $data['number'];
        }
        $tool->save();
        return ApiResponse::makeResponse(true, $tool, ApiResponse::SUCCESS_CODE);
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
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数设备id$id']);
        }
        $tool = ToolManager::getById($id);
        $tool->status = $data['status'];
        $tool->save();
        return ApiResponse::makeResponse(true, $tool, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 设置设备状态
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function info(Request $request){
        $data = $request->all();
        if(!array_key_exists('id', $data) || $data['id'] == ''){
            return ApiResponse::makeResponse(false, '设备id缺失', ApiResponse::MISSING_PARAM);
        }
        $tool = ToolManager::getById($data['id']);
        $con_arr = array(
            'shop_id' => $data['id'],
        );
        return view('HJGL.admin.tool.info', [ 'tool' => $tool ,'con_arr' => $con_arr]);
    }

    /*
     * 设备选择商家
     *
     * By Yuyang
     *
     * 2019/01/02
     */
    public function chooseShop(Request $request){
        $data = $request->all();
        if(!array_key_exists('id', $data) || $data['id'] == ''){
            return '设备id缺失';
        }
        $tool = ToolManager::getById($data['id']);
        $search_word = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'status' => '1',
            'search_word' => $search_word,
        );
        $shops = ShopManager::getListByCon($con_arr,true);
        return view('HJGL.admin.tool.chooseShop', [ 'tool' => $tool , 'datas' => $shops ,'con_arr' => $con_arr]);
    }

    /*
     * 选择商家
     *
     * By Yuyang
     *
     * 2019/01/02
     */
    public function chooseShopSave(Request $request)
    {
        $data = $request->all();
        if(!array_key_exists('shop_id', $data) || $data['shop_id'] == ''){
            return ApiResponse::makeResponse(false, '商家ID缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('tool_id', $data) || $data['tool_id'] == ''){
            return ApiResponse::makeResponse(false, '设备ID缺失', ApiResponse::MISSING_PARAM);
        }
        $tool = ToolManager::getById($data['tool_id']);
        if($tool->status == 0){
            return ApiResponse::makeResponse(false, '该设备未启用', ApiResponse::MISSING_PARAM);
        }
        if(!empty($tool->shop_id)){
            return ApiResponse::makeResponse(false, '该设备已被其他商家选择', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['shop_id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        if($shop->status == 0){
            return ApiResponse::makeResponse(false, '该商家未启用', ApiResponse::MISSING_PARAM);
        }
        $tool->shop_id = $data['shop_id'];
        $tool->loan = '1';
        $tool->save();
        return ApiResponse::makeResponse(true, $tool, ApiResponse::SUCCESS_CODE);
    }
}
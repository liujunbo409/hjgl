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
use App\Models\HJGL\Shop;
use Illuminate\Http\Request;
use App\Components\Utils;
use App\Components\QNManager;
use App\Http\Controllers\ApiResponse;
use App\Components\HJGL\HandleRecordManager;

class ShopController{
    /*
     * 商家列表
     *
     * By Yuyang
     *
     * 2018/12/28
     */
    public function index(Request $request){
        $data = $request->all();
        //条件搜索
        $search_word = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'search_word' => $search_word,
        );
        $shops = ShopManager::getListByCon($con_arr,true);
        return view('HJGL.admin.shop.index', [ 'datas' => $shops, 'con_arr' => $con_arr]);
    }

    /*
     * 新建或修改商家
     *
     * By Yuyang
     *
     * 2018/12/28
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $shop = new Shop();
        if (array_key_exists('id', $data)) {
            $shop = ShopManager::getById($data['id']);
        }
        $upload_token = QNManager::uploadToken();
        $admin = $request->session()->get('admin');
        return view('HJGL.admin.shop.edit', ['admin' => $admin, 'data' => $shop,'upload_token' => $upload_token]);
    }


    //新建或编辑商家->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (!array_key_exists('shop_name', $data) || $data['shop_name'] == '') {
            return ApiResponse::makeResponse(false, '商家名称缺失', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('address', $data) || $data['address'] == '') {
            return ApiResponse::makeResponse(false, '商家地址缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('id', $data) || $data['id'] == ''){
            if (!array_key_exists('name', $data) || $data['name'] == '') {
                return ApiResponse::makeResponse(false, '管理员姓名缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('phone', $data) || $data['phone'] == '') {
                return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('password', $data) || $data['password'] == '') {
                return ApiResponse::makeResponse(false, '登录密码缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('confirm_password', $data) || $data['confirm_password'] == '') {
                return ApiResponse::makeResponse(false, '确认密码缺失', ApiResponse::MISSING_PARAM);
            }
            if($data['password'] != $data['confirm_password']){
                return ApiResponse::makeResponse(false, '两次输入的密码不一致', ApiResponse::MISSING_PARAM);
            }
            $shop = new Shop();
            $e_shop = ShopManager::getByPhone($data['phone']);
            if ($e_shop) {
                return ApiResponse::makeResponse(false, "手机号重复", ApiResponse::PHONE_DUP);
            }
            $shop = ShopManager::setShop($shop, $data);
            $shop->save();
        }else{
            $shop = ShopManager::getById($data['id']);
            $shop->shop_name = $data['shop_name'];
            $shop->address = $data['address'];
            $shop->save();
        }

        return ApiResponse::makeResponse(true, $shop, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 设置商家状态
     *
     * By Yuyang
     *
     * 2018/12/28
     */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数商家id$id']);
        }
        $shop = ShopManager::getById($id);
        $shop->status = $data['status'];
        $shop->save();
        return ApiResponse::makeResponse(true, $shop, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 商家详情
     *
     * By Yuyang
     *
     * 2018/12/28
     */
    public function info(Request $request)
    {
        $data = $request->all();
        if(!array_key_exists('id', $data) || $data['id'] == ''){
            return ApiResponse::makeResponse(false, '商家id缺失', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['id']);
        $con_arr = array(
            'shop_id' => $data['id'],
        );
        $tools = ToolManager::getListByCon($con_arr,true);
        return view('HJGL.admin.shop.info', [ 'shop' => $shop , 'datas' => $tools ,'con_arr' => $con_arr]);
    }

    /*
     * 商家选择设备
     *
     * By Yuyang
     *
     * 2019/01/02
     */
    public function chooseTool(Request $request){
        $data = $request->all();
        if(!array_key_exists('id', $data) || $data['id'] == ''){
            return '商家id缺失';
        }
        $shop = ShopManager::getById($data['id']);
        $search_word = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'status' => '2',
            'loan_status' => '1',
            'search_word' => $search_word,
        );
        $my_con_arr = array(
            'shop_id' => $shop->id,
        );
        $my_tools = ToolManager::getListByCon($my_con_arr,true);
        $tools = ToolManager::getListByCon($con_arr,true);

        return view('HJGL.admin.shop.chooseTool', [ 'shop' => $shop , 'datas' => $tools , 'my_tools' => $my_tools ,'con_arr' => $con_arr]);
    }

    /*
     * 选择商家
     *
     * By Yuyang
     *
     * 2019/01/02
     */
    public function chooseToolSave(Request $request)
    {
        $data = $request->all();
        if(!array_key_exists('shop_id', $data) || $data['shop_id'] == ''){
            return ApiResponse::makeResponse(false, '商家ID缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('tool_id', $data) || $data['tool_id'] == ''){
            return ApiResponse::makeResponse(false, '设备ID缺失', ApiResponse::MISSING_PARAM);
        }
        $tool = ToolManager::getById($data['tool_id']);
        if($tool->status == 1){
            return ApiResponse::makeResponse(false, '该设备未启用', ApiResponse::MISSING_PARAM);
        }
        if(!empty($tool->shop_id)){
            return ApiResponse::makeResponse(false, '该设备已被其他商家选择', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['shop_id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        if($shop->status == 1){
            return ApiResponse::makeResponse(false, '该商家未启用', ApiResponse::MISSING_PARAM);
        }
        $tool->shop_id = $data['shop_id'];
        $tool->loan_status = '2';
        $tool->save();
        $shop->tool_qty = $shop->tool_qty + 1;
        $shop->save();
        return ApiResponse::makeResponse(true, $tool, ApiResponse::SUCCESS_CODE);
    }

}
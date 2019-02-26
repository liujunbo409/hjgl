<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\HJGL\Admin;

use App\Components\HJGL\ShopLoanManager;
use App\Components\HJGL\ShopManager;
use App\Components\HJGL\ToolManager;
use App\Models\HJGL\Shop;
use App\Models\HJGL\ShopLoan;
use Illuminate\Http\Request;
use App\Components\Utils;
use App\Components\QNManager;
use App\Http\Controllers\ApiResponse;

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
        if (!array_key_exists('shop_name', $data) || Utils::isObjNull($data['shop_name'])) {
            return ApiResponse::makeResponse(false, '商家名称缺失', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('address', $data) || Utils::isObjNull($data['address'])) {
            return ApiResponse::makeResponse(false, '商家地址缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('id', $data) || Utils::isObjNull($data['id'])){
            if (!array_key_exists('name', $data) || Utils::isObjNull($data['name'])) {
                return ApiResponse::makeResponse(false, '管理员姓名缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('phone', $data) || Utils::isObjNull($data['phone'])) {
                return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('password', $data) || Utils::isObjNull($data['password'])) {
                return ApiResponse::makeResponse(false, '登录密码缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('confirm_password', $data) || Utils::isObjNull($data['confirm_password'])) {
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
            if(empty($shop)){
                return ApiResponse::makeResponse(false, "不存在该商家", ApiResponse::INNER_ERROR);
            }
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
    public function setStatus(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (!array_key_exists('id',$data) || Utils::isObjNull($data['id'])) {
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数商家id$id']);
        }
        $id = $data['id'];
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数商家id$id']);
        }
        $shop = ShopManager::getById($id);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, "不存在该商家", ApiResponse::INNER_ERROR);
        }
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
        if(!array_key_exists('id', $data) || Utils::isObjNull($data['id'])){
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
        $shop = ShopManager::getById($data['id']);
        $search_word = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'shop_ids' => array('0',$shop->id),
            'status' => '2',
            'search_word' => $search_word,
        );
        $tools = ToolManager::getListByCon($con_arr,true);
        return view('HJGL.admin.shop.chooseTool', [ 'shop' => $shop , 'datas' => $tools ,'con_arr' => $con_arr]);
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
        if(!array_key_exists('shop_id', $data) || Utils::isObjNull($data['shop_id'])){
            return ApiResponse::makeResponse(false, '商家ID缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('tool_id', $data) || Utils::isObjNull($data['tool_id'])){
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
        if($shop->status != 2){
            return ApiResponse::makeResponse(false, '该商家未启用', ApiResponse::MISSING_PARAM);
        }

        $shop_loan = new ShopLoan();
        $shop_loan ->shop_id = $shop->id;
        $shop_loan ->shop_name = $shop->shop_name;
        $shop_loan ->tool_id = $tool->id;
        $shop_loan ->tool_number = $tool->number;
        $shop_loan->save();

        $tool->shop_id = $data['shop_id'];
        $tool->shop_name = $shop->shop_name;
        $tool->save();
        $shop->tool_qty = $shop->tool_qty + 1;
        $shop->save();

        return ApiResponse::makeResponse(true, '成功', ApiResponse::SUCCESS_CODE);
    }

    /*
     * 设备移除
     *
     * By Yuyang
     *
     * 2018/12/28
     */
    public function removeTool(Request $request)
    {
        $data = $request->all();
        if(!array_key_exists('shop_id', $data) || Utils::isObjNull($data['shop_id'])){
            return ApiResponse::makeResponse(false, '商家ID缺失', ApiResponse::MISSING_PARAM);
        }
        if(!array_key_exists('tool_id', $data) || Utils::isObjNull($data['tool_id'])){
            return ApiResponse::makeResponse(false, '设备ID缺失', ApiResponse::MISSING_PARAM);
        }
        $shop = ShopManager::getById($data['shop_id']);
        if(empty($shop)){
            return ApiResponse::makeResponse(false, '不存在该商家', ApiResponse::MISSING_PARAM);
        }
        $tool = ToolManager::getById($data['tool_id']);
        if(empty($tool)){
            return ApiResponse::makeResponse(false, '不存在该设备', ApiResponse::MISSING_PARAM);
        }
        if($tool->loan_status != 1){
            return ApiResponse::makeResponse(false, '该设备只有在"未借出"状态才能被移除', ApiResponse::INNER_ERROR);
        }
        $shop_loan = ShopLoanManager::getByConId($shop->id,$tool->id);
        if(empty($shop_loan)){
            return ApiResponse::makeResponse(false, '不存在所属关系', ApiResponse::MISSING_PARAM);
        }
        $shop_loan->status = 2;
        $shop_loan->save();
        $tool->shop_id = '0';
        $tool->shop_name = '';
        $tool->save();
        return ApiResponse::makeResponse(true, $shop_loan, ApiResponse::SUCCESS_CODE);
    }

}
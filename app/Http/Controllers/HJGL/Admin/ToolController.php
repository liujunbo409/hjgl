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
use App\Components\HJGL\UserLoanManager;
use App\Components\HJGL\ToolManager;
use App\Components\HJGL\UserOrderManager;
use App\Models\HJGL\ShopLoan;
use App\Models\HJGL\Tool;
use Illuminate\Http\Request;
use App\Components\Utils;
use App\Components\QNManager;
use App\Http\Controllers\ApiResponse;

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
        $admin = $request->session()->get('admin');
        //条件搜索
        $search_word = null;
        $loan_status = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }
        if(array_key_exists('loan_status',$data) && !Utils::isObjNull($data['loan_status'])){
            $loan_status = $data['loan_status'];
        }
        $con_arr = array(
            'search_word' => $search_word,
            'loan_status' => $loan_status,
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
        if(!array_key_exists('id', $data) || Utils::isObjNull($data['id'])){
            if (!array_key_exists('number', $data) || Utils::isObjNull($data['number'])) {
                return ApiResponse::makeResponse(false, '设备编号缺失', ApiResponse::MISSING_PARAM);
            }
            $tool = new Tool();
            $e_tool = ToolManager::getByNumber($data['number']);
            if ($e_tool) {
                return ApiResponse::makeResponse(false, "设备编号重复", ApiResponse::PHONE_DUP);
            }
            $tool->code = 'http://hj.lljiankang.top/api/QRcode/index?tool_num='.$data['number'];
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
    public function setStatus(Request $request)
    {
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数设备id$id']);
        }
        $id = $data['id'];
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数设备id$id']);
        }
        $tool = ToolManager::getById($id);
        $tool->status = $data['status'];
        $tool->save();
        return ApiResponse::makeResponse(true, $tool, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 设备详情
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function info(Request $request){
        $data = $request->all();
        if(!array_key_exists('id', $data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '设备id缺失', ApiResponse::MISSING_PARAM);
        }
        $tool = ToolManager::getById($data['id']);
        $user_loan = UserLoanManager::getByToolId($data['id'],1);
        if(empty($user_loan)){
            $order = array();
        }else{
            $order = UserOrderManager::getByOrderNumber($user_loan->order_number);
            $user_loan->user_phone = $order->user_phone;
        }
        return view('HJGL.admin.tool.info', [ 'tool' => $tool , 'user_loan' => $user_loan]);
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
        if(!array_key_exists('id', $data) || Utils::isObjNull($data['id'])){
            exit('设备id缺失');
        }
        $tool = ToolManager::getById($data['id']);
        $search_word = null;
        if(array_key_exists('search_word',$data) && !Utils::isObjNull($data['search_word'])){
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'status' => '2',
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
        $admin = $request->session()->get('admin');
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
        $tool->code_status = 2;
        $tool->save();
        $shop->tool_qty = $shop->tool_qty + 1;
        $shop->save();

        return ApiResponse::makeResponse(true, '成功', ApiResponse::SUCCESS_CODE);
    }
}
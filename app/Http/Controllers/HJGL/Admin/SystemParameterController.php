<?php
namespace App\Http\Controllers\HJGL\Admin;

use App\Components\HJGL\SystemParameterManager;
use App\Models\HJGL\SystemParameter;
use App\Http\Controllers\ApiResponse;
use Illuminate\Http\Request;
use App\Components\Utils;
use App\Components\HJGL\HandleRecordManager;

class SystemParameterController{
    public function index(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //相关搜素条件
        $search_word = null;
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'search_word' => $search_word,
        );
        $parameters = SystemParameterManager::getListByCon($con_arr, true);
        return view('HJGL.admin.systemParameter.index', ['admin' => $admin, 'datas' => $parameters, 'con_arr' => $con_arr]);
    }

    /*
     * 新建或编辑系统参数-get
     *
     * By Yuyang
     *
     * 2019-01-03
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $parameter = new SystemParameter();
        if (array_key_exists('id', $data)) {
            $parameter = SystemParameterManager::getById($data['id']);
        }
        return view('HJGL.admin.systemParameter.edit', ['admin' => $admin, 'data' => $parameter]);
    }

    /*
     * 新建或编辑系统参数->post
     *
     * By Yuyang
     *
     * 2019-01-07
     */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (!array_key_exists('parameter_name', $data) || $data['parameter_name'] == '') {
            return ApiResponse::makeResponse(false, '系统参数名缺失', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('parameter', $data) || $data['parameter'] == '') {
            return ApiResponse::makeResponse(false, '系统参数标识缺失', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('parameter_val', $data) || $data['parameter_val'] == '') {
            return ApiResponse::makeResponse(false, '系统参数值缺失', ApiResponse::MISSING_PARAM);
        }
        $parameter = new SystemParameter();
        //存在id是编辑
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $parameter = SystemParameterManager::getById($data['id']);
            $parameter = SystemParameterManager::setInfo($parameter, $data);
            $parameter->save();
            $re_arr=array(
                't_table'=>'system_parameter',
                't_id'=>$data['id'],
                'type'=>'update',
                'role'=>$admin['role'],
                'role_id'=>$admin['id'],
            );
            HandleRecordManager::record($re_arr);
        }else{
            $parameter = SystemParameterManager::setInfo($parameter, $data);
            $parameter->save();
            $re_arr=array(
                't_table'=>'system_parameter',
                't_id'=>$parameter->id,
                'type'=>'create',
                'role'=>$admin['role'],
                'role_id'=>$admin['id'],
            );
            HandleRecordManager::record($re_arr);
        }
        return ApiResponse::makeResponse(true, $parameter, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 删除系统参数
     *
     * By Yuyang
     *
     * 2019-01-07
     */
    public function del(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (!array_key_exists('id', $data) || $data['id'] == '') {
            return ApiResponse::makeResponse(false, 'id缺失', ApiResponse::MISSING_PARAM);
        }
        $parameter = SystemParameterManager::getById($data['id']);
        if (!$parameter) {
            return ApiResponse::makeResponse(false, '不存在的系统参数', ApiResponse::PARAM_ERROR);
        }
        $parameter->delete();
        $re_arr=array(
            't_table'=>'system_parameter',
            't_id'=>$data['id'],
            'type'=>'delete',
            'role'=>$admin['role'],
            'role_id'=>$admin['id'],
        );
        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true, '删除成功', ApiResponse::SUCCESS_CODE);
    }

    /*
     * 设置系统参数状态
     *
     * By Yuyang
     *
     * 2019/01/07
     */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数系统参数id$id']);
        }
        $parameter = SystemParameterManager::getById($id);
        $parameter->status = $data['status'];
        $parameter->save();
        $re_arr=array(
            't_table'=>'system_parameter',
            't_id'=>$id,
            'type'=>'update',
            'role'=>$admin['role'],
            'role_id'=>$admin['id'],
        );
        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true, $parameter, ApiResponse::SUCCESS_CODE);
    }
}
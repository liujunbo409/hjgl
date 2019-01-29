<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\HJGL\Admin;

use App\Components\HJGL\AdminManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\HJGL\Admin;
use Illuminate\Http\Request;
use App\Components\HJGL\VertifyManager;
use App\Components\HJGL\HandleRecordManager;


class AdminController
{

    /*
     * 管理员列表
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $curr_admin = $request->session()->get('admin');
        if(!isset($curr_admin['id']) || empty($curr_admin['id'])){
            return ApiResponse::makeResponse(false, '身份信息丢失', ApiResponse::MISSING_PARAM);
        }
        $curr_admin = AdminManager::getById($curr_admin['id']);
        //相关搜素条件
        $search_word = null;
        $role = null;
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('role', $data) && !Utils::isObjNull($data['role'])) {
            $role = $data['role'];
        }
        $con_arr = array(
            'search_word' => $search_word,
            'role' => $role
        );
        $admins = AdminManager::getListByCon($con_arr, true);
        foreach($admins as $admin){
            $admin=AdminManager::getInfoByLevel($admin,'1');
        }
        return view('HJGL.admin.admin.index', ['admin' => $curr_admin, 'datas' => $admins, 'con_arr' => $con_arr]);
    }

    /*
     * 修改个人资料
     *
     * By Yuyang
     *
     * 2018/12/27
    */
    public function editMySelf(Request $request)
    {
        $admin = $request->session()->get('admin');
        if(!isset($admin['id']) || empty($admin['id'])){
            return ApiResponse::makeResponse(false, '身份信息丢失', ApiResponse::MISSING_PARAM);
        }
        $admin = AdminManager::getById($admin['id']);
        $admin = AdminManager::getInfoByLevel($admin, '0');
        $upload_token = QNManager::uploadToken();
        return view('HJGL.admin.admin.editMySelf', ['admin' => $admin, 'upload_token' => $upload_token]);
    }

    /*
     * 修改个人资料
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function editMySelfPost(Request $request)
    {
        $data = $request->all();
        $curr_admin = $request->session()->get('admin');
        if (!array_key_exists('id', $data) || $data['id'] == '') {
            return ApiResponse::makeResponse(false, '没有用户id', ApiResponse::USER_ID_LOST);
        }
        if (!array_key_exists('name', $data) || $data['name'] == '') {
            return ApiResponse::makeResponse(false, '姓名缺失', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('avatar', $data) || $data['avatar'] == '') {
            return ApiResponse::makeResponse(false, '头像缺失', ApiResponse::MISSING_PARAM);
        }
        $admin = AdminManager::getById($data['id']);
        $admin->name = isset($data['name']) ? $data['name'] : '';
        $admin->avatar = isset($data['avatar']) ? $data['avatar'] : '';
        $admin->save();
        $re_arr=array(
            't_table'=>'admin',
            't_id'=>$data['id'],
            'type'=>'update',
            'role'=>$curr_admin['role'],
            'role_id'=>$curr_admin['id'],
        );
        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true, $admin, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 修改密码
     *
     * By Yuyang
     *
     * 2018-12-27
     */

    public function editMyPass(Request $request)
    {
        $admin = $request->session()->get('admin');
        if(!isset($admin['id']) || empty($admin['id'])){
            return ApiResponse::makeResponse(false, '身份信息丢失', ApiResponse::MISSING_PARAM);
        }
        $admin = AdminManager::getById($admin['id']);
        return view('HJGL.admin.admin.editMyPass', ['admin' => $admin]);
    }


    /*
     * 修改密码-Post
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function editMyPassPost(Request $request)
    {
        $data = $request->all();
        $curr_admin = $request->session()->get('admin');
        if(!isset($admin['id']) || empty($admin['id'])){
            return ApiResponse::makeResponse(false, '身份信息丢失', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('id', $data) || $data['id'] == '') {
            return ApiResponse::makeResponse(false, '没有用户id', ApiResponse::USER_ID_LOST);
        }
        if (!array_key_exists('password', $data) || $data['password'] == '') {
            return ApiResponse::makeResponse(false, '密码缺失', ApiResponse::PASSWORD_LOST);
        }
        if (!array_key_exists('new_password', $data) || $data['new_password'] == '') {
            return ApiResponse::makeResponse(false, '密码缺失', ApiResponse::PASSWORD_LOST);
        }
        if (!array_key_exists('confirm_password', $data) || $data['confirm_password'] == '') {
            return ApiResponse::makeResponse(false, '密码缺失', ApiResponse::PASSWORD_LOST);
        }
        if ($data['new_password'] != $data['confirm_password']) {
            return ApiResponse::makeResponse(false, '新密码的两次输入不一致', ApiResponse::PASSWORD_LOST);
        }
        $id=$data['id'];
        $admin = AdminManager::getById($id);
        if($admin){
            $password=$data['password'];
            if($password!=$admin->password){
                return ApiResponse::makeResponse(false, '密码错误', ApiResponse::PASSWORD_ERROR);
            }
            $admin->password=$data['new_password'];
            $admin->save();
            $re_arr=array(
                't_table'=>'admin',
                't_id'=>$data['id'],
                'type'=>'update',
                'role'=>$curr_admin['role'],
                'role_id'=>$curr_admin['id'],
            );
            HandleRecordManager::record($re_arr);
            return ApiResponse::makeResponse(true,$admin, ApiResponse::SUCCESS_CODE);
        }else{
            return ApiResponse::makeResponse(false, '没有此用户', ApiResponse::NO_USER);
        }
    }

    /*
     * 修改手机号
     *
     * By Yuyang
     *
     * 2018-12-27
     */

    public function editMyTel(Request $request)
    {
        $admin = $request->session()->get('admin');
        $ys_sm = VertifyManager::judgeVertifyCode('13644197638', '2938');
        $admin = AdminManager::getById($admin['id']);
        return view('HJGL.admin.admin.editMyTel', ['admin' => $admin]);

    }

    /*
     * 修改手机号-Post
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function editMyTelPost(Request $request)
    {
        $data = $request->all();
        $curr_admin = $request->session()->get('admin');
        if (!array_key_exists('id', $data) || $data['id'] == '') {
            return ApiResponse::makeResponse(false, '没有用户id', ApiResponse::USER_ID_LOST);
        }
        if (!array_key_exists('phone', $data) || $data['phone'] == '') {
            return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::PHONE_LOST);
        }
        if (!array_key_exists('sm_validate', $data) || $data['sm_validate'] == '') {
            return ApiResponse::makeResponse(false, '短信验证码缺失', ApiResponse::SM_VERTIFY_LOST);
        }
        $id=$data['id'];
        $admin = AdminManager::getById($id);
        if ($admin) {
            $is_have=AdminManager::getByPhone($data['phone']);
            if($is_have){
                return ApiResponse::makeResponse(false, '手机号已存在', ApiResponse::PHONE_HAS_BEEN_SELECTED);
            }
            $ys_sm = VertifyManager::judgeVertifyCode($data['phone'], $data['sm_validate']);
            if (!$ys_sm) {
                return ApiResponse::makeResponse(false, '短信验证码验证失败', ApiResponse::SM_VERTIFY_ERROR);
            }
            $admin->phone=$data['phone'];
            $admin->save();
            $re_arr=array(
                't_table'=>'admin',
                't_id'=>$data['id'],
                'type'=>'update',
                'role'=>$curr_admin['role'],
                'role_id'=>$curr_admin['id'],
            );
            HandleRecordManager::record($re_arr);
            return ApiResponse::makeResponse(true,$admin, ApiResponse::SUCCESS_CODE);
        }else{
            return ApiResponse::makeResponse(false, '没有此用户', ApiResponse::NO_USER);
        }
    }

    /*
      * 向用户新手机号发送短信
      *
      * By Yuyang
      *
      * 2018/12/27
      */
    public function validateNewPhone(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('phone', $data) || $data['phone'] == '') {
            return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::PHONE_LOST);
        }
        $is_have=AdminManager::getByPhone($data['phone']);
        if($is_have){
            return ApiResponse::makeResponse(false, '手机号已存在', ApiResponse::PHONE_HAS_BEEN_SELECTED);
        }
        $result = VertifyManager::sendVertify($data['phone']);
        if($result){
            return ApiResponse::makeResponse(true,'短信验证码已发送', ApiResponse::SUCCESS_CODE);
        }
        return ApiResponse::makeResponse(false, '短信验证码发送失败', ApiResponse::SM_VERTIFY_SEND_ERROR);
    }



    //删除管理员(不建议使用，现关闭）
    public function del(Request $request, $id)
    {
        return ApiResponse::makeResponse(false, "功能被禁止", ApiResponse::INNER_ERROR);
//        if (is_numeric($id) !== true) {
//            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数管理员id']);
//        }
//        $admin = AdminManager::getById($id);
//        //如果不存在管理员，则返回失败1
//        if (!$admin) {
//            return ApiResponse::makeResponse(false, "管理员不存在", ApiResponse::INNER_ERROR);
//        }
//        //非根管理员
//        if ($admin->type != '0') {
//            $admin->delete();
//            return ApiResponse::makeResponse(true, "删除管理员成功", ApiResponse::SUCCESS_CODE);
//        } else {
//            return ApiResponse::makeResponse(false, "不允许删除超级管理员", ApiResponse::SUCCESS_CODE);
//        }
    }

    /*
     * 设置管理员状态
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        $curr_admin = $request->session()->get('admin');
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数管理员id$id']);
        }
        $admin = AdminManager::getById($id);
        if($admin->id == $curr_admin['id']){
            return ApiResponse::makeResponse(false, '不能对自己进行操作', ApiResponse::INNER_ERROR);
        }
        $admin->status = $data['status'];
        $admin->save();
        $re_arr=array(
            't_table'=>'admin',
            't_id'=>$id,
            'type'=>'update',
            'role'=>$curr_admin['role'],
            'role_id'=>$curr_admin['id'],
        );
        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true, '成功', ApiResponse::SUCCESS_CODE);
    }

    /*
     * 新建或修改管理员
     *
     * By Yuyang
     *
     * 2018/12/27
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $curr_admin = $request->session()->get('admin');
        $admin = new Admin();
        if (array_key_exists('id', $data)) {
            $admin = AdminManager::getById($data['id']);
            if(!empty($admin)){
                if($admin->id == $curr_admin['id']){
                    return('不能对自己操作');
                }
            }
        }
        $upload_token = QNManager::uploadToken();
        return view('HJGL.admin.admin.edit', ['admin' => $curr_admin, 'data' => $admin,'upload_token' => $upload_token]);
    }


    //新建或编辑管理员->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $curr_admin = $request->session()->get('admin');
        if (!array_key_exists('role', $data) || $data['role'] == '') {
            return ApiResponse::makeResponse(false, '角色缺失', ApiResponse::MISSING_PARAM);
        }
        if ($data['role'] == 0) {
            return ApiResponse::makeResponse(false, '添加错误的角色', ApiResponse::INNER_ERROR);
        }
        if(!array_key_exists('id', $data) || $data['id'] == ''){
            if (!array_key_exists('name', $data) || $data['name'] == '') {
                return ApiResponse::makeResponse(false, '姓名缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('phone', $data) || $data['phone'] == '') {
                return ApiResponse::makeResponse(false, '手机号缺失', ApiResponse::MISSING_PARAM);
            }
            if (!array_key_exists('password', $data) || $data['password'] == '') {
                return ApiResponse::makeResponse(false, '密码缺失', ApiResponse::MISSING_PARAM);
            }
            $admin = new Admin();
            $e_admin = AdminManager::getByPhone($data['phone']);
            if ($e_admin) {
                return ApiResponse::makeResponse(false, "手机号重复", ApiResponse::PHONE_DUP);
            }
            $admin = AdminManager::setAdmin($admin, $data);
            $admin->save();
            $re_arr=array(
                't_table'=>'admin',
                't_id'=>$admin->id,
                'type'=>'create',
                'role'=>$curr_admin['role'],
                'role_id'=>$curr_admin['id'],
            );
            HandleRecordManager::record($re_arr);
        }else{
            $admin = AdminManager::getById($data['id']);
            if($curr_admin['id'] == $admin->id){
                return ApiResponse::makeResponse(false, "不能对自己操作", ApiResponse::INNER_ERROR);
            }
            $admin->role = $data['role'];
            $admin->save();
            $re_arr=array(
                't_table'=>'admin',
                't_id'=>$data['id'],
                'type'=>'update',
                'role'=>$curr_admin['role'],
                'role_id'=>$curr_admin['id'],
            );
            HandleRecordManager::record($re_arr);
        }
        return ApiResponse::makeResponse(true, $admin, ApiResponse::SUCCESS_CODE);
    }

}
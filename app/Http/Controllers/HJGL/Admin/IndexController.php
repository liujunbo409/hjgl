<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\HJGL\Admin;

use Illuminate\Http\Request;
use App\Libs\ServerUtils;

class IndexController
{
    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        return view('HJGL.admin.index.index', ['admin' => $admin]);
    }

    //首页--系统信息
    public function info(Request $request)
    {
        $serverInfo = ServerUtils::getServerInfo();
        $admin = $request->session()->get('admin');
        return view('HJGL.admin.index.info', ['serverInfo' => $serverInfo, 'admin' => $admin]);
    }

    //错误
    public function error(Request $request)
    {
        $data = $request->all();
        $msg = null;
        if (array_key_exists('msg', $data)) {
            $msg = $data['msg'];
        }
        $admin = $request->session()->get('admin');
        return view('HJGL.admin.index.error500', ['msg' => $msg, 'admin' => $admin]);
    }
}
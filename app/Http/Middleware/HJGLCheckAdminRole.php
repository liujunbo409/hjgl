<?php

/**
 * 检测后台用户是否登录中间件
 */

namespace App\Http\Middleware;

use App\Components\HJGL\AdminManager;
use App\Components\Utils;
use Closure;

class HJGLCheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  \从路由中获得的可以使用此页面的管理员参数
     * @return mixed
     */
    public function handle($request, Closure $next,$roles)
    {

        $admin = $request->session()->get('admin');//从session取出管理员id
        $admin = AdminManager::getById($admin['id']);//获取管理员信息
        $type=$admin->role;//获取管理员权限分组
        $roles= explode('-',$roles);//把从路由中获取的参数转换为数组形式
        //如果管理员权限分组符合条件进入页面，若不符合则进入错误提示页
       foreach ($roles as $role) {
           if($role==Utils::admin_role[$type]){
               return $next($request);
           }
       }
        return redirect('/admin/error');
    }
}

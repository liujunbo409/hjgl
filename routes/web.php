<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//测试
Route::get('test/index', 'HJGL\Test\TestController@index');  //登录

//登录相关
Route::get('admin/login', 'HJGL\Admin\LoginController@login');  //登录
Route::post('admin/login', 'HJGL\Admin\LoginController@loginPost');  //登录-post

Route::group(['prefix' => 'admin', 'middleware' => ['hjgl.adminLogin']], function () {

    //错误页面
    Route::any('/error/500', 'HJGL\Admin\IndexController@error');  //错误页面

    //首页
    Route::get('/', 'HJGL\Admin\IndexController@index');       //首页
    Route::get('/index', 'HJGL\Admin\IndexController@index');  //首页
    Route::get('/index/index', 'HJGL\Admin\IndexController@info');  //首页-系统信息
    Route::get('/loginout', 'HJGL\Admin\LoginController@loginout');  //首页-登出

    //系统参数管理
    Route::any('/systemParameter/index', 'HJGL\Admin\SystemParameterController@index')->middleware('hjgl.AdminRole:超级管理员');  //系统参数管理首页
    Route::get('/systemParameter/edit', 'HJGL\Admin\SystemParameterController@edit')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑系统参数
    Route::post('/systemParameter/edit', 'HJGL\Admin\SystemParameterController@editPost')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑系统参数
//    Route::get('/systemParameter/del', 'HJGL\Admin\SystemParameterController@del')->middleware('hjgl.AdminRole:超级管理员');  //系统参数删除
    Route::get('/systemParameter/setStatus/{id}', 'HJGL\Admin\SystemParameterController@setStatus')->middleware('hjgl.AdminRole:超级管理员');  //设置系统参数状态

    //管理员个人信息
    Route::get('/admin/editMySelf', 'HJGL\Admin\AdminController@editMySelf')->name('editMySelf');  //修改个人资料
    Route::post('/admin/editMySelf', 'HJGL\Admin\AdminController@editMySelfPost');  //修改个人资料
    Route::get('/admin/editMyPass', 'HJGL\Admin\AdminController@editMyPass');  //修改密码
    Route::post('/admin/editMyPass', 'HJGL\Admin\AdminController@editMyPassPost');  //修改密码-Post
    Route::get('/admin/validateOldPhone', 'HJGL\Admin\AdminController@validateOldPhone');  //修改密码向用户旧手机号发送短信(根据管理员id)
    Route::get('/admin/editMyTel', 'HJGL\Admin\AdminController@editMyTel');  //修改手机号
    Route::post('/admin/editMyTel', 'HJGL\Admin\AdminController@editMyTelPost');  //修改手机号-Post
    Route::get('/admin/validateNewPhone', 'HJGL\Admin\AdminController@validateNewPhone');  //向用户新手机号发送短信

    //管理员管理
    Route::any('/admin/index', 'HJGL\Admin\AdminController@index')->middleware('hjgl.AdminRole:超级管理员');  //管理员管理首页
    Route::get('/admin/setStatus/{id}', 'HJGL\Admin\AdminController@setStatus')->middleware('hjgl.AdminRole:超级管理员');  //设置管理员状态
    Route::get('/admin/del/{id}', 'HJGL\Admin\AdminController@del')->middleware('hjgl.AdminRole:超级管理员'); //删除管理员(不建议使用，现关闭）
    Route::get('/admin/edit', 'HJGL\Admin\AdminController@edit')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑管理员
    Route::post('/admin/edit', 'HJGL\Admin\AdminController@editPost')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑管理员

    //设备管理
    Route::any('/tool/index', 'HJGL\Admin\ToolController@index')->middleware('hjgl.AdminRole:超级管理员');  //设备管理首页
    Route::get('/tool/setStatus/{id}', 'HJGL\Admin\ToolController@setStatus')->middleware('hjgl.AdminRole:超级管理员');  //设置设备状态
    Route::get('/tool/edit', 'HJGL\Admin\ToolController@edit')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑设备
    Route::post('/tool/edit', 'HJGL\Admin\ToolController@editPost')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑设备
    Route::get('/tool/info', 'HJGL\Admin\ToolController@info')->middleware('hjgl.AdminRole:超级管理员');  //设备详情
    Route::get('/tool/chooseShop', 'HJGL\Admin\ToolController@chooseShop')->middleware('hjgl.AdminRole:超级管理员');  //设备选择商家
    Route::get('/tool/chooseShopSave', 'HJGL\Admin\ToolController@chooseShopSave')->middleware('hjgl.AdminRole:超级管理员');  //设备选择商家

    //设备处理
    Route::any('/toolDispose/index', 'HJGL\Admin\ToolDisposeController@index')->middleware('hjgl.AdminRole:超级管理员');  //设备处理首页
    Route::get('/toolDispose/info', 'HJGL\Admin\ToolDisposeController@info')->middleware('hjgl.AdminRole:超级管理员');  //设备详情
    Route::get('/toolDispose/setStatus/{id}', 'HJGL\Admin\ToolDisposeController@setStatus')->middleware('hjgl.AdminRole:超级管理员');  //设置设备处理状态

    //商家管理
    Route::any('/shop/index', 'HJGL\Admin\ShopController@index')->middleware('hjgl.AdminRole:超级管理员');  //商家管理首页
    Route::get('/shop/setStatus/{id}', 'HJGL\Admin\ShopController@setStatus')->middleware('hjgl.AdminRole:超级管理员');  //设置商家状态
    Route::get('/shop/edit', 'HJGL\Admin\ShopController@edit')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑商家
    Route::post('/shop/edit', 'HJGL\Admin\ShopController@editPost')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑商家
    Route::get('/shop/info', 'HJGL\Admin\ShopController@info')->middleware('hjgl.AdminRole:超级管理员');  //商家详情
    Route::get('/shop/chooseTool', 'HJGL\Admin\ShopController@chooseTool')->middleware('hjgl.AdminRole:超级管理员');  //商家选择设备
    Route::get('/shop/chooseToolSave', 'HJGL\Admin\ShopController@chooseToolSave')->middleware('hjgl.AdminRole:超级管理员');  //商家选择设备
    Route::get('/shop/removeTool', 'HJGL\Admin\ShopController@removeTool')->middleware('hjgl.AdminRole:超级管理员');  //设备移除

    //文章管理
    Route::any('/article/index', 'HJGL\Admin\ArticleController@index')->middleware('hjgl.AdminRole:超级管理员');  //文章管理首页
    Route::any('/article/articleList', 'HJGL\Admin\ArticleController@articleList')->middleware('hjgl.AdminRole:超级管理员');  //文章管理首页
    Route::get('/article/addArticle', 'HJGL\Admin\ArticleController@addArticle')->middleware('hjgl.AdminRole:超级管理员');  //文章分类添加文章
    Route::post('/article/addArticle', 'HJGL\Admin\ArticleController@addArticlePost')->middleware('hjgl.AdminRole:超级管理员');  //文章分类添加文章
    Route::get('/article/sort', 'HJGL\Admin\ArticleController@sort')->middleware('hjgl.AdminRole:超级管理员');  //文章排序
    Route::get('/article/info', 'HJGL\Admin\ArticleController@info')->middleware('hjgl.AdminRole:超级管理员');  //文章详情
    Route::get('/article/edit', 'HJGL\Admin\ArticleController@edit')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑文章
    Route::post('/article/edit', 'HJGL\Admin\ArticleController@editPost')->middleware('hjgl.AdminRole:超级管理员');  //新建或编辑文章
    Route::get('/article/delArticle', 'HJGL\Admin\ArticleController@delArticle')->middleware('hjgl.AdminRole:超级管理员');  //删除文章分类所属文章
    Route::get('/article/setStatus/{id}', 'HJGL\Admin\ArticleController@setStatus')->middleware('hjgl.AdminRole:超级管理员');  //设置文章状态
    Route::get('/article/upArticle', 'HJGL\Admin\ArticleController@upArticle')->middleware('hjgl.AdminRole:超级管理员');  //文章向上移动
    Route::get('/article/downArticle', 'HJGL\Admin\ArticleController@downArticle')->middleware('hjgl.AdminRole:超级管理员');  //文章向下移动
    Route::get('/article/moveArticleList', 'HJGL\Admin\ArticleController@moveArticleList')->middleware('hjgl.AdminRole:超级管理员');  //文章选择根目录
    Route::post('/article/moveArticleSave', 'HJGL\Admin\ArticleController@moveArticleSave')->middleware('hjgl.AdminRole:超级管理员'); //文章选择根目录

//    Route::get('/article/chooseArticle', 'HJGL\Admin\ArticleController@chooseArticle')->middleware('hjgl.AdminRole:超级管理员');  //文章分类选择文章
//    Route::get('/article/chooseArticleSave', 'HJGL\Admin\ArticleController@chooseArticleSave')->middleware('hjgl.AdminRole:超级管理员');  //文章分类选择文章


    //文章目录管理
    Route::any('/articleType/index', 'HJGL\Admin\ArticleTypeController@index')->middleware('hjgl.AdminRole:超级管理员');  //文章分类首页
    Route::get('/articleType/edit', 'HJGL\Admin\ArticleTypeController@edit')->middleware('hjgl.AdminRole:超级管理员');  //编辑文章分类
    Route::post('/articleType/edit', 'HJGL\Admin\ArticleTypeController@editPost')->middleware('hjgl.AdminRole:超级管理员');  //编辑文章分类
    Route::get('/articleType/del', 'HJGL\Admin\ArticleTypeController@del')->middleware('hjgl.AdminRole:超级管理员');  //文章分类删除
    Route::get('/articleType/upType', 'HJGL\Admin\ArticleTypeController@upType')->middleware('hjgl.AdminRole:超级管理员');  //文章分类向上移动
    Route::get('/articleType/downType', 'HJGL\Admin\ArticleTypeController@downType')->middleware('hjgl.AdminRole:超级管理员');  //文章分类向下移动
    Route::post('/articleType/addTypeFather', 'HJGL\Admin\ArticleTypeController@addTypeFather')->middleware('hjgl.AdminRole:超级管理员');  //新建文章父分类
    Route::get('/articleType/moveTypeList', 'HJGL\Admin\ArticleTypeController@moveTypeList')->middleware('hjgl.AdminRole:超级管理员');  //文章分类选择根目录
    Route::post('/articleType/moveTypeSave', 'HJGL\Admin\ArticleTypeController@moveTypeSave')->middleware('hjgl.AdminRole:超级管理员');  //文章分类选择根目录


    //用户订单管理
    Route::any('/userOrder/index', 'HJGL\Admin\UserOrderController@index')->middleware('hjgl.AdminRole:超级管理员');  //用户订单管理首页
    Route::get('/userOrder/info', 'HJGL\Admin\UserOrderController@info')->middleware('hjgl.AdminRole:超级管理员');  //用户订单详细信息

    //账目管理
    Route::any('/userAccount/rent', 'HJGL\Admin\UserAccountController@rent')->middleware('hjgl.AdminRole:超级管理员');//租金管理
    Route::any('/userAccount/rent_total', 'HJGL\Admin\UserAccountController@rent_total')->middleware('hjgl.AdminRole:超级管理员');//租金总统计
    Route::any('/userAccount/rent_month', 'HJGL\Admin\UserAccountController@rent_month')->middleware('hjgl.AdminRole:超级管理员');//租金月统计
    Route::any('/userAccount/rent_day', 'HJGL\Admin\UserAccountController@rent_day')->middleware('hjgl.AdminRole:超级管理员');//租金日统计
    Route::any('/userAccount/rent_range', 'HJGL\Admin\UserAccountController@rent_range')->middleware('hjgl.AdminRole:超级管理员');//租金范围统计
    Route::any('/userAccount/deposit', 'HJGL\Admin\UserAccountController@deposit')->middleware('hjgl.AdminRole:超级管理员');//押金管理

});

Route::group(['prefix' => 'app'], function () {
    Route::any('/login', 'HJGL\App\LoginController@login');
});
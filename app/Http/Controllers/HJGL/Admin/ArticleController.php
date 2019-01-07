<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Medicineistrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\HJGL\Admin;

use App\Components\HJGL\ArticleManager;
use App\Components\HJGL\ArticleTypeManager;
use App\Components\HJGL\ArticleAscriptionManager;
use App\Models\HJGL\Article;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use Illuminate\Http\Request;
use App\Components\HJGL\HandleRecordManager;


class ArticleController
{
    /*
     * 首页
     *
     * By Yuyang
     *
     * 2019-01-03
     */
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
        $articles = ArticleManager::getListByCon($con_arr, true);
        return view('HJGL.admin.article.index', ['admin' => $admin, 'datas' => $articles, 'con_arr' => $con_arr]);
    }

    /*
     * 新建或编辑文章-get
     *
     * By Yuyang
     *
     * 2019-01-03
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $article = new Article();
        if (array_key_exists('id', $data)) {
            $article = ArticleManager::getById($data['id']);
        }
        return view('HJGL.admin.article.edit', ['admin' => $admin, 'data' => $article]);
    }

    /*
     * 新建或编辑文章->post
     *
     * By Yuyang
     *
     * 2019-01-03
     */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $article = new Article();
        //存在id是编辑
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $article = ArticleManager::getById($data['id']);
            $article = ArticleManager::setInfo($article, $data);
            $article->save();
            $re_arr=array(
                't_table'=>'article_info',
                't_id'=>$data['id'],
                'type'=>'update',
                'role'=>$admin['role'],
                'role_id'=>$admin['id'],
            );
            HandleRecordManager::record($re_arr);
        }else{
            $article = ArticleManager::setInfo($article, $data);
            $article->oper_type = $admin->role;
            $article->oper_id = $admin->id;
            $article->save();
            $re_arr=array(
                't_table'=>'article_info',
                't_id'=>$article->id,
                'type'=>'create',
                'role'=>$admin['role'],
                'role_id'=>$admin['id'],
            );
            HandleRecordManager::record($re_arr);
        }
        return ApiResponse::makeResponse(true, $article, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 删除文章
     *
     * By Yuyang
     *
     * 2019-01-03
     */
    public function del(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (!array_key_exists('id', $data) || $data['id'] == '') {
            return ApiResponse::makeResponse(false, 'id缺失', ApiResponse::MISSING_PARAM);
        }
        $article = ArticleManager::getById($data['id']);
        if (!$article) {
            return ApiResponse::makeResponse(false, '不存在的文章', ApiResponse::PARAM_ERROR);
        }
        $articleascription = ArticleAscriptionManager::getByArticleId($data['id']);
        if($articleascription != null) {
            $articleascriptions = ArticleAscriptionManager::getByCon($data['id']);
            $res = ArticleTypeManager::getInfoByorId($articleascriptions,0);
            if($res != false){
                $names = '';
                foreach($res as $v){
                    $names .= $v->name.',';
                }
                $names = rtrim($names,",");
                return ApiResponse::makeResponse(false, '已被文章分类所选择('.$names.')', ApiResponse::PARAM_ERROR);
            }else{
                return ApiResponse::makeResponse(false, '不存在的文章分类', ApiResponse::PARAM_ERROR);
            }
        }
        $article->delete();
        $re_arr=array(
            't_table'=>'article_info',
            't_id'=>$data['id'],
            'type'=>'delete',
            'role'=>$admin['role'],
            'role_id'=>$admin['id'],
        );
        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true, '删除成功', ApiResponse::SUCCESS_CODE);
    }


}
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
        $search_word = null;
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'search_word' => $search_word,
        );
        $articletypes = ArticleTypeManager::getListByCon($con_arr, false);
        $infos = array();
        foreach ($articletypes as $articletype) {
            $info = (object)array();
            $info->name = $articletype->name;
            $info->pId = $articletype->parent_id;
            $info->id = $articletype->id;
            $info->click="openMulu(".$articletype->id.")";
            array_push($infos, $info);
        }
        $infos = json_encode($infos);
        return view('HJGL.admin.article.index', ['admin' => $admin, 'datas' => $infos]);
    }

    public function articleList(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (!array_key_exists('id', $data)) {
            return view('HJGL.admin.index.blank');
        }
        $search_word = null;
        $status = null;
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }
        $con_arr = array(
            'search_word' => $search_word,
            'status' => $status,
        );
        $ascription_sign = array();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $articleType = ArticleTypeManager::getById($data['id']);
            if(empty($articleType)){
                return('目录不存在');
            }
            $ids = ArticleTypeManager::getByfatherAllId($data['id'],array());
            $ids = array_unique($ids);
            array_push($ids,$data['id']);
            $ascriptions = ArticleAscriptionManager::getByTypeorCon($ids,'1');
            foreach($ascriptions as $v){
                $ascription_sign[$v->article_id] = $v->type_id;
            }
            $article_ids = array();
            foreach($ascriptions as $v){
                $article_ids[] = $v->article_id;
            }
            $articles = ArticleManager::getInfoByorId($article_ids,$con_arr,true);
            return view('HJGL.admin.article.articleList', ['admin' => $admin, 'data' => $data, 'articles' => $articles, 'ascription_sign' => $ascription_sign, 'con_arr' => $con_arr]);
        }else{
            return('分类id未获取到');
        }
    }

    /*
     * 为文章分类添加文章
     *
     * By Yuyang
     *
     * 2019/01/03
     */
    public function addArticle(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        return view('HJGL.admin.article.add', ['admin' => $admin, 'data' => $data]);
    }

    /*
     * 为文章分类添加文章-post
     *
     * By Yuyang
     *
     * 2019/01/03
     */
    public function addArticlePost(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return ApiResponse::makeResponse(false, '文章分类id缺失', ApiResponse::MISSING_PARAM);
        }
        $article = new Article();
        $data['oper_type'] = $admin->role;
        $data['oper_name'] = $admin->name;
        $data['oper_id'] = $admin->id;
        $article = ArticleManager::setInfo($article, $data);
        $article->save();

        $info = array();
        $info['type_id'] = $data['id'];
        $info['article_id'] = $article->id;
        $ascription = ArticleAscriptionManager::setInfo($info);
        $count = ArticleAscriptionManager::getByTypeIds($data['id'],false)->count();
        $ascription->seq = $count + 1 ;
        $ascription->save();

        return ApiResponse::makeResponse(true, $article, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 文章排序
     *
     * By Yuyang
     *
     * 2019/01/15
     */
    public function sort(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (!array_key_exists('id', $data)) {
            return('id缺失');
        }
        $ascription_sign = array();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $articleType = ArticleTypeManager::getById($data['id']);
            if(empty($articleType)){
                return('目录不存在');
            }
            $ascriptions = ArticleAscriptionManager::getByTypeIds($data['id'],true);
            if(isset($data['ids'])){
                $ids = $data['ids'];
            }else{
                $ids = array();
                foreach($ascriptions as $v){
                    $ids[] = $v->article_id;
                }
            }
            $articles = ArticleManager::getInfoByorId($ids,array(),false);
            $articles_show = array();
            foreach($articles as $v){
                $articles_show[$v->id] = $v;
            }
            foreach($ascriptions as $v){
                $ascription_sign[$v->article_id] = $v->type_id;
                if(isset($articles_show[$v->article_id])){
                    $v->article_title = $articles_show[$v->article_id]->title;
                    $v->article_oper_name = $articles_show[$v->article_id]->oper_name;
                    $v->article_created_at = $articles_show[$v->article_id]->created_at;
                    $v->article_status = $articles_show[$v->article_id]->status;
                }else{
                    $v->title = '未获取';
                    $v->article_oper_name = '未获取';
                    $v->article_created_at = '未获取';
                    $v->article_status = '未获取';
                }
            }
            return view('HJGL.admin.article.sort', ['admin' => $admin, 'data' => $data, 'ascriptions' => $ascriptions, 'ascription_sign' => $ascription_sign, 'ids' => $ids]);
        }else{
            return('分类id未获取到');
        }
    }

    /*
     * 上移文章
     *
     * By Yuyang
     *
     * 2019-01-15
     */
    public static function upArticle(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('type_id', $data) || Utils::isObjNull($data['type_id'])) {
            return ApiResponse::makeResponse(false,  '目录id未获取', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('article_id', $data) || Utils::isObjNull($data['article_id'])) {
            return ApiResponse::makeResponse(false,  '文章id未获取', ApiResponse::MISSING_PARAM);
        }
        $tag = ArticleAscriptionManager::getOneByCon($data['article_id'],$data['type_id']);
        $seq=1;
        if (array_key_exists('seq', $data) && !Utils::isObjNull($data['seq'])) {
            $seq=$data['seq'];
        }
        $seq_up = $tag->seq - $seq;
        if($seq_up<=0){
            $seq=$seq-(1-$seq_up);
            $seq_up=1;
        }
        $con_arr = array(
            'type_id' => $tag->type_id,
            'seq'=>$tag->seq,
            'seq_up'=>$seq_up
        );
        $tag_others = ArticleAscriptionManager::getBySeq($con_arr, 'up');
        $i = 0;
        foreach ($tag_others as $tag_other) {
            $tag_other->seq = $tag_other->seq + 1;
            $tag_other->save();
            $i++;
        }
        if ($i > 0) {
            $tag->seq = $seq_up;
        }
        $tag->save();
        return ApiResponse::makeResponse(true,  '移动成功', ApiResponse::SUCCESS_CODE);
    }

    /*
     * 下移文章
     *
     * By Yuyang
     *
     * 2019-01-16
     */
    public static function downArticle(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('type_id', $data) || Utils::isObjNull($data['type_id'])) {
            return ApiResponse::makeResponse(false,  '目录id未获取', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('article_id', $data) || Utils::isObjNull($data['article_id'])) {
            return ApiResponse::makeResponse(false,  '文章id未获取', ApiResponse::MISSING_PARAM);
        }
        $tag = ArticleAscriptionManager::getOneByCon($data['article_id'],$data['type_id']);
        $seq=1;
        if (array_key_exists('seq', $data) && !Utils::isObjNull($data['seq'])) {
            $seq=$data['seq'];
        }
        $seq_down = $tag->seq + $seq;
        $con_arr = array(
            'type_id' => $tag->type_id,
            'seq'=>$tag->seq,
            'seq_down'=>$seq_down
        );
        $tag_others = ArticleAscriptionManager::getBySeq($con_arr, 'down');
        $i = 0;
        foreach ($tag_others as $tag_other) {
            $tag_other->seq = $tag_other->seq - 1;
            $tag_other->save();
            $i++;
        }
        if ($i > 0) {
            $tag->seq = $tag->seq+$i;
        }
        $tag->save();
        return ApiResponse::makeResponse(true,  '移动成功', ApiResponse::SUCCESS_CODE);
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
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $article = ArticleManager::getById($data['id']);
        }
        return view('HJGL.admin.article.edit', ['admin' => $admin, 'article' => $article , 'data' => $data]);
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
            $article->revise_type = $admin->role;
            $article->revise_id = $admin->id;
            $article->revise_name = $admin->name;
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
            $article->oper_name = $admin->name;
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
     * 删除文章分类下的文章
     *
     * By Yuyang
     *
     * 2019-01-03
     */
    public function delArticle(Request $request){
        $data = $request->all();
        if (!array_key_exists('article_id', $data) || $data['article_id'] == '' || !array_key_exists('type_id', $data) || $data['type_id'] == '') {
            return ApiResponse::makeResponse(false, 'id缺失', ApiResponse::MISSING_PARAM);
        }

        $type = ArticleTypeManager::getById($data['type_id']);
        if (!$type) {
            return ApiResponse::makeResponse(false, '不存在的文章分类', ApiResponse::PARAM_ERROR);
        }
        $article = ArticleManager::getById($data['article_id']);
        if (!$article) {
            return ApiResponse::makeResponse(false, '不存在的文章', ApiResponse::PARAM_ERROR);
        }
        $con_arr = array(
            'type_id' => $data['type_id'],
            'article_id' => $data['article_id'],
        );
        $ascription = ArticleAscriptionManager::getListByCon($con_arr,true);
        if (!$ascription) {
            return ApiResponse::makeResponse(false, '不存在的文章所属关系', ApiResponse::PARAM_ERROR);
        }
        foreach($ascription as $v){
            $v->delete();
        }
        $article->delete();
        return ApiResponse::makeResponse(true, '删除成功', ApiResponse::SUCCESS_CODE);
    }

    /*
     * 文章详情
     *
     * By Yuyang
     *
     * 2019-01-15
     */
    public function info(Request $request){
        $data = $request->all();
        if (!array_key_exists('id', $data) || Utils::isObjNull($data['id'])) {
            return('文章ID缺失');
        }
        $article_info = ArticleManager::getById($data['id']);
        if(empty($article_info)){
            return('不存在该文章');
        }
        return view('HJGL.admin.article.info', [ 'data' => $article_info]);
    }

    /*
     * 设置文章状态
     *
     * By Yuyang
     *
     * 2019/01/15
     */
    public function setStatus(Request $request)
    {
        $data = $request->all();
        if(!array_key_exists('id',$data) || Utils::isObjNull($data['id'])){
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查文章id$id']);
        }
        $id = $data['id'];
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\HJGL\Admin\IndexController@error', ['msg' => '合规校验失败，请检查文章id$id']);
        }
        $tool = ArticleManager::getById($id);
        $tool->status = $data['status'];
        $tool->save();
        return ApiResponse::makeResponse(true, '操作成功', ApiResponse::SUCCESS_CODE);
    }

    /*
     * 移动文章列表
     *
     * By Yuyang
     *
     * 2019/01/16
     */
    public static function moveArticleList(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        if (array_key_exists('article_id', $data) && !Utils::isObjNull($data['article_id'])) {
            $article_id=$data['article_id'];
        }else{
            return('文章id未获取');
        }
        if (array_key_exists('old_type_id', $data) && !Utils::isObjNull($data['old_type_id'])) {
            $old_type_id=$data['old_type_id'];
        }else{
            return('所属目录id未获取');
        }
        $con_arr = array(
        );
        $tags = ArticleTypeManager::getListByCon($con_arr, false);
        $datas=array();
        foreach ($tags as $tag) {
            $data=(object)array();
            $data->name =$tag->name;
            $data->click="moveMulu(".$article_id.",".$tag->id.",".$old_type_id.")";
            if($tag->parent_id == ""){
                $data->pId =0;
            } else {
                $data->pId = $tag->parent_id;
            }
            $data->id = $tag->id;
            array_push($datas, $data);
        }
        $datas=json_encode($datas);
        return view('HJGL.admin.article.moveArticleList', ['admin' => $admin, 'datas' =>$datas]);
    }

    /*
     * 移动文章
     *
     * By Yuyang
     *
     * 2019/01/16
     */
    public static function moveArticleSave(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');

        if(!array_key_exists('article_id', $data) || Utils::isObjNull($data['article_id'])){
            return ApiResponse::makeResponse(false, '文章id缺失', ApiResponse::PARAM_ERROR);
        }
        if(!array_key_exists('move_id', $data) || Utils::isObjNull($data['move_id'])){
            return ApiResponse::makeResponse(false, '目标目录id缺失', ApiResponse::PARAM_ERROR);
        }
        if(!array_key_exists('old_type_id', $data) || Utils::isObjNull($data['old_type_id'])){
            return ApiResponse::makeResponse(false, '原始目录id缺失', ApiResponse::PARAM_ERROR);
        }

        $article = ArticleManager::getById($data['article_id']);
        if(empty($article)){
            return ApiResponse::makeResponse(false, '所移动文章不存在', ApiResponse::PARAM_ERROR);
        }
        $old_type = ArticleTypeManager::getById($data['old_type_id']);
        if(empty($old_type)){
            return ApiResponse::makeResponse(false, '原始目录不存在', ApiResponse::PARAM_ERROR);
        }
        $articleAscription = ArticleAscriptionManager::getOneByCon($article->id,$old_type->id);
        if(empty($articleAscription)){
            return ApiResponse::makeResponse(false, '原始所属关系不存在', ApiResponse::PARAM_ERROR);
        }
        $type = ArticleTypeManager::getById($data['move_id']);
        if(empty($type)){
            return ApiResponse::makeResponse(false, '目标目录不存在', ApiResponse::PARAM_ERROR);
        }
        if($old_type->id == $type->id){
            return ApiResponse::makeResponse(false, '您选择了原目录', ApiResponse::PARAM_ERROR);
        }
        $con_arr_move = array(
            'type_id' =>  $articleAscription->type_id,
            'seq' => $articleAscription->seq,
            'seq_down' => '999'
        );
        $type_others = ArticleAscriptionManager::getBySeq($con_arr_move, 'down');
        foreach($type_others as $type_other){
            $type_other->seq=$type_other->seq-1;
            $type_other->save();
        }
        $articleAscription->type_id=$data['move_id'];
        $con_arr=array(
            'type_id'=>$data['move_id'],
        );
        $num=ArticleAscriptionManager::getListByCon($con_arr,false)->count();
        $articleAscription->seq=$num+1;
        $articleAscription->save();

        return ApiResponse::makeResponse(true,  '移动成功', ApiResponse::SUCCESS_CODE);
    }


}
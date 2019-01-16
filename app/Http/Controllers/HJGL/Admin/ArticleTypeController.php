<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: articletypeistrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\HJGL\Admin;

use App\Components\HJGL\ArticleManager;
use App\Components\HJGL\ArticleTypeManager;
use App\Components\HJGL\ArticleAscriptionManager;
use App\Components\HJGL\HandleRecordManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\HJGL\Article;
use App\Models\HJGL\ArticleType;
use Illuminate\Http\Request;


class ArticleTypeController
{
    /*
     * 首页--文章分类
     *
     * By Yuyang
     *
     * 2019/01/03
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
        return view('HJGL.admin.ArticleType.index', ['admin' => $admin, 'datas' => $infos]);
    }

    /*
     * 新建或编辑文章分类以及显示该分类下的文章
     *
     * By Yuyang
     *
     * 2019/01/03
     */
    public function edit(Request $request)
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
            return view('HJGL.admin.articleType.edit', ['admin' => $admin, 'data' => $data, 'articles' => $articles, 'ascription_sign' => $ascription_sign, 'con_arr' => $con_arr]);
        }else{
            return('分类id未获取到');
        }
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
                    $v->article_create_time = $articles_show[$v->article_id]->create_time;
                    $v->article_status = $articles_show[$v->article_id]->status;
                }else{
                    $v->title = '未获取';
                    $v->article_oper_name = '未获取';
                    $v->article_create_time = '未获取';
                    $v->article_status = '未获取';
                }
            }
            return view('HJGL.admin.articleType.sort', ['admin' => $admin, 'data' => $data, 'ascriptions' => $ascriptions, 'ascription_sign' => $ascription_sign, 'ids' => $ids]);
        }else{
            return('分类id未获取到');
        }
    }

    /*
     * 新建或编辑文章分类-post
     *
     * By Yuyang
     *
     * 2019/01/03
     */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $articleType = new ArticleType();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $articleType = ArticleTypeManager::getById($data['id']);
            $articleType->name=$data['name'];
            $articleType->save();
        }else{
            if (!array_key_exists('parent_id', $data) || Utils::isObjNull($data['parent_id'])){
                return ApiResponse::makeResponse(false, '父id缺失', ApiResponse::MISSING_PARAM);
            }
            $con_arr=array(
                'parent_id'=>$data['parent_id'],
            );
            $count = ArticleTypeManager::getListByCon($con_arr,true)->count();
            $articleType->name=$data['name'];
            $articleType->parent_id=$data['parent_id'];
            $articleType->seq = $count + 1;
            $articleType->save();
        }
        return ApiResponse::makeResponse(true, '修改成功', ApiResponse::SUCCESS_CODE);
    }

    /*
     * 添加文章父分类
     *
     * By Yuyang
     *
     * 2019-01-03
     */

    public static function addTypeFather(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $type = new ArticleType();
        $con_arr=array(
            'parent_id'=>"0",
        );
        $count = ArticleTypeManager::getListByCon($con_arr,true)->count();
        $type->name = $data['name'];
        $type->seq = $count + 1;
        $type->save();
        $re_arr=array(
            't_table'=>'article_type',
            't_id'=>$type->id,
            'type'=>'create',
            'role'=>$admin['role'],
            'role_id'=>$admin['id'],
        );
        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true, '添加成功', ApiResponse::SUCCESS_CODE);
    }

    /*
     * 上移目录
     *
     * By Yuyang
     *
     * 2019-01-03
     */
    public static function upType(Request $request)
    {
        $data = $request->all();
        $tag_id = $data['id'];
        $tag = ArticleTypeManager::getById($tag_id);
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
            'parent_id' => $tag->parent_id,
            'seq'=>$tag->seq,
            'seq_up'=>$seq_up
        );
        $tag_others = ArticleTypeManager::getBySeq($con_arr, 'up');
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
     * 下移目录
     *
     * By Yuyang
     *
     * 2019-01-03
     */
    public static function downType(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $tag_id = $data['id'];
        $tag = ArticleTypeManager::getById($tag_id);
        $seq=1;
        if (array_key_exists('seq', $data) && !Utils::isObjNull($data['seq'])) {
            $seq=$data['seq'];
        }
        $seq_down = $tag->seq + $seq;
        $con_arr = array(
            'parent_id' => $tag->parent_id,
            'seq'=>$tag->seq,
            'seq_down'=>$seq_down
        );
        $tag_others = ArticleTypeManager::getBySeq($con_arr, 'down');
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
        $re_arr=array(
            't_table'=>'article_type',
            't_id'=>$tag_id,
            'type'=>'update',
            'role'=>$admin['role'],
            'role_id'=>$admin['id'],
            'action'=>'down',
        );
        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true,  '移动成功', ApiResponse::SUCCESS_CODE);
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
     * 为文章分类添加文章
     *
     * By Yuyang
     *
     * 2019/01/03
     */
    public function addArticle(Request $request){
    	$data = $request->all();
        $admin = $request->session()->get('admin');
        return view('HJGL.admin.articleType.add', ['admin' => $admin, 'data' => $data]);
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

        $article = new Article();
        $data['oper_type'] = $admin->name;
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
     * 为文章分类挂文章
     *
     * By Yuyang
     *
     * 2019/01/03
     */
    public function chooseArticle(Request $request)
    {
        exit('暂时停用');
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //相关搜素条件
        $search_word = null;
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('title', $data) && !Utils::isObjNull($data['title'])) {
            $title = $data['title'];
        }else{
            $title = '';
        }
        if (array_key_exists('author', $data) && !Utils::isObjNull($data['author'])) {
            $author = $data['author'];
        }else{
            $author = '';
        }
        $con_arr = array(
            'search_word' => $search_word,
            'title' => $title,
            'author' => $author,
        );
        $max_fid = ArticleTypeManager::getByorfatherAllId($data['id']);
        $type_ids = ArticleTypeManager::getByfatherAllId($max_fid,array());
        $type_ids = array_unique($type_ids);
        array_push($type_ids,$max_fid);
        $articles_only = ArticleAscriptionManager::getByTypeorCon($type_ids,1);
        $ids = array();
        foreach($articles_only as $v){
        	$ids[] = $v->article_id;
        }
        $articles = ArticleManager::getListByCon($con_arr, true);
        return view('HJGL.admin.articleType.chooseArticle', ['admin' => $admin, 'datas' => $articles, 'type_id' => $data['id'], 'ids' => $ids, 'con_arr' => $con_arr]);
    }

    /*
     * 为文章分类挂文章-保存
     *
     * By Yuyang
     *
     * 2019/01/03
     */
    public function chooseArticleSave(Request $request){
        exit('暂时停用');
    	$data = $request->all();
        if (!array_key_exists('article_id', $data) || Utils::isObjNull($data['article_id'])) {
            return ApiResponse::makeResponse(false, '文章id缺失', ApiResponse::MISSING_PARAM);
        }
        if (!array_key_exists('type_id', $data) || Utils::isObjNull($data['type_id'])) {
            return ApiResponse::makeResponse(false, '文章分类id缺失', ApiResponse::MISSING_PARAM);
        }
        $admin = $request->session()->get('admin');
        $ascription = ArticleAscriptionManager::setInfo($data);
        $ascription->save();
        $re_arr=array(
            't_table'=>'article_ascription',
            't_id'=>$ascription->id,
            'type'=>'create',
            'role'=>$admin['role'],
            'role_id'=>$admin['id'],
        );
        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true, $ascription, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 删除文章分类
     *
     * By Yuyang
     *
     * 2019-01-03
     */
    public function del(Request $request)
    {
        $data = $request->all();
        if (!array_key_exists('id', $data) || $data['id'] == '') {
            return ApiResponse::makeResponse(false, 'id缺失', ApiResponse::MISSING_PARAM);
        }
        $article = ArticleTypeManager::getById($data['id']);
        if (!$article) {
            return ApiResponse::makeResponse(false, '不存在的文章分类', ApiResponse::PARAM_ERROR);
        }
        $articleascription = ArticleAscriptionManager::getByTypeId($data['id']);
        $type = ArticleTypeManager::getByFId($data['id']);
        if($articleascription != null) {
            return ApiResponse::makeResponse(false, '该文章分类下挂有文章', ApiResponse::PARAM_ERROR);
        }elseif($type != null){
            return ApiResponse::makeResponse(false, '该文章分类下挂有下属分类', ApiResponse::PARAM_ERROR);
        }
        $con_arr = array(
            'parent_id' => $type->parent_id,
            'seq' => $type->seq,
            'seq_down' => '999',
        );
        $moves = ArticleTypeManager::getBySeq($con_arr,'down');
        $i = 0;
        foreach($moves as $move){
            $move->seq = $move->seq - 1;
            $move->save();
            $i++;
        }
        if($i > 0){
            $article->delete();
            return ApiResponse::makeResponse(true, '删除成功', ApiResponse::SUCCESS_CODE);
        }else{
            return ApiResponse::makeResponse(false, '删除失败', ApiResponse::PARAM_ERROR);
        }
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
        $admin = $request->session()->get('admin');
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
     * 移动文章分类列表
     *
     * By Yuyang
     *
     * 2019/01/03
     */
    public static function moveTypeList(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $from_id=$data['id'];
        $con_arr = array(
        );
        $tags = ArticleTypeManager::getListByCon($con_arr, false);
        if (array_key_exists('parent_id', $data) && !Utils::isObjNull($data['parent_id'])) {
            $open_id=$data['parent_id'];
        }else{
            $open_id = 0;
        }
        $datas=array();
        foreach ($tags as $tag) {
            $data=(object)array();
            $data->name =$tag->name;
            $data->click="moveMulu(".$from_id.",".$tag->id.",".$open_id.")";

            if($tag->parent_id == ""){
                $data->pId =0;
            } else {
                $data->pId = $tag->parent_id;
            }
            $data->id = $tag->id;
            array_push($datas, $data);
        }
        $datas=json_encode($datas);
        return view('HJGL.admin.articleType.moveTypeList', ['admin' => $admin, 'datas' =>$datas, 'from_id' => $from_id]);
    }

    /*
     * 移动文章分类
     *
     * By Yuyang
     *
     * 2019/01/03
     */
    public static function moveTypeSave(Request $request){
        $data = $request->all();
        $admin = $request->session()->get('admin');
        $type_id = $data['id'];
        $parent_id = $data['move_id'];
        $type = ArticleTypeManager::getById($type_id);
        if ($type_id == $parent_id) {
            return ApiResponse::makeResponse(false, '不能选择自己', ApiResponse::PARAM_ERROR);
        }
        $con_arr = array(
            'parent_id' =>  $type->parent_id,
            'seq' => $type->seq,
            'seq_down' => '999'
        );
        $type_others = ArticleTypeManager::getBySeq($con_arr, 'down');
        foreach($type_others as $type_other){
            $type_other->seq=$type_other->seq-1;
            $type_other->save();
        }
        $type->parent_id=$data['move_id'];
        $con_arr=array(
            'parent_id'=>$data['move_id'],
        );
        $num=ArticleTypeManager::getListByCon($con_arr,false)->count();
        $type->seq=$num+1;
        $type->save();
        $re_arr=array(
            't_table'=>'article_type',
            't_id'=>$data['id'],
            'type'=>'update',
            'role'=>$admin['role'],
            'role_id'=>$admin['id'],
            'action'=>'move',
        );
        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true,  '移动成功', ApiResponse::SUCCESS_CODE);
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
        return view('HJGL.admin.articleType.moveArticleList', ['admin' => $admin, 'datas' =>$datas]);
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
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
//use App\Components\HJGL\HandleRecordManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\HJGL\Article;
use App\Models\HJGL\ArticleType;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


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
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'search_word' => $search_word,
        );
        $articles = array();
        $ascriptions = array();
        $articleType = new ArticleType();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
        	$articleType = ArticleTypeManager::getById($data['id']);
        	if(empty($articleType)){
                return view('HJGL.admin.index.blank');
            }
//            if($articleType->parent_id == 0){
                $ids = ArticleTypeManager::getByfatherAllId($data['id'],array());
                $ids = array_unique($ids);
                array_push($ids,$data['id']);
                $ascription = ArticleAscriptionManager::getByTypeorCon($ids,'1');
//            }else{
//                $max_parent_id = ArticleTypeManager::getByorfatherAllId($data['id']);
//                $ids = ArticleTypeManager::getByfatherAllId($max_parent_id,array());
//                $ids = array_unique($ids);
//                array_push($ids,$data['id']);
//                $ascription = ArticleAscriptionManager::getByTypeorCon($ids,'1');
//            }
        	foreach($ascription as $v){
        		$ascriptions[$v->article_id] = $v->type_id;
        	}
			$articles = ArticleManager::getInfoByorId($ascription);
        }
        return view('HJGL.admin.articleType.edit', ['admin' => $admin, 'data' => $articleType, 'articles' => $articles, 'ascriptions' => $ascriptions, 'con_arr' => $con_arr]);
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
            if (array_key_exists('parent_id', $data) && !Utils::isObjNull($data['parent_id'])) {
                $articleType->parent_id=$data['parent_id'];
            }
            $articleType->name=$data['name'];
            $articleType->save();
//            $re_arr=array(
//                't_table'=>'article_type',
//                't_id'=>$data['id'],
//                'type'=>'update',
//                'role'=>'admin',
//                'role_id'=>$admin['id'],
//                'action'=>'update',
//            );
//            HandleRecordManager::record($re_arr);
        }else{
            $articleType->name=$data['name'];
            $articleType->parent_id=$data['parent_id'];
            $articleType->save();
//            $re_arr=array(
//                't_table'=>'article_type',
//                't_id'=>$articleType->id,
//                'type'=>'create',
//                'role'=>'admin',
//                'role_id'=>$admin['id'],
//            );
//            HandleRecordManager::record($re_arr);
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
        $type = new ArticleType();
        $con_arr=array(
            'parent_id'=>"0",
        );
        $count = ArticleTypeManager::getListByCon($con_arr,true)->count();
        $type->name = $data['name'];
        $type->seq = $count + 1;
        $type->save();
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
        $admin = $request->session()->get('admin');
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
//        $re_arr=array(
//            't_table'=>'article_type',
//            't_id'=>$tag_id,
//            'type'=>'update',
//            'role'=>'admin',
//            'role_id'=>$admin['id'],
//            'action'=>'up',
//        );
//        HandleRecordManager::record($re_arr);
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
//        $re_arr=array(
//            't_table'=>'article_type',
//            't_id'=>$tag_id,
//            'type'=>'update',
//            'role'=>'admin',
//            'role_id'=>$admin['id'],
//            'action'=>'down',
//        );
//        HandleRecordManager::record($re_arr);
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
//        $articleascription =ArticleAscriptionManager:: getByTypeCon($data['id']);
//        $articles = ArticleManager::getInfoByorId($articleascription);
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
    	$data = $request->all();
        $admin = $request->session()->get('admin');
        $ascription = ArticleAscriptionManager::setInfo($data);
        $ascription->save();
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
        $admin = $request->session()->get('admin');
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

//        $re_arr=array(
//            't_table'=>'article_type',
//            't_id'=>$data['id'],
//            'type'=>'delete',
//            'role'=>'admin',
//            'role_id'=>$admin['id'],
//        );
//        HandleRecordManager::record($re_arr);
        $article->delete();
        return ApiResponse::makeResponse(true, '删除成功', ApiResponse::SUCCESS_CODE);
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
//        	$re_arr=array(
//	            't_table'=>'article_ascription',
//	            't_id'=>$data['type_id'],
//	            'type'=>'delete',
//	            'role'=>'admin',
//	            'role_id'=>$admin['id'],
//	        );
//	        HandleRecordManager::record($re_arr);
	        $v->delete();
        }
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
//        dd($tags);
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
            'seq_down' => '100'
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

//        $re_arr=array(
//            't_table'=>'article_type',
//            't_id'=>$data['id'],
//            'type'=>'update',
//            'role'=>'admin',
//            'role_id'=>$admin['id'],
//            'action'=>'move',
//        );
//        HandleRecordManager::record($re_arr);
        return ApiResponse::makeResponse(true,  '移动成功', ApiResponse::SUCCESS_CODE);
    }


}
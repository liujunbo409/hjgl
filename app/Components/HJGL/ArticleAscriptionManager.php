<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components\HJGL;

use App\Components\Utils;
use App\Models\HJGL\ArticleAscription;
use Qiniu\Auth;

class ArticleAscriptionManager
{
    /*
     * 根据id获取文章所属文章分类
     *
     * By yuyang
     *
     * 2019-01-03
     */
    public static function getById($id)
    {
        $info =new ArticleAscription();
        $info = ArticleAscription::where('id', '=', $id)->first();
        return $info;
    }

    /*
     * 根据article_id获取首个文章所属文章分类
     *
     * By yuyang
     *
     * 2019-01-03
     */
    public static function getByArticleId($article_id)
    {
        $info=new ArticleAscription();
        $info = ArticleAscription::where('article_id', '=', $article_id)->first();
        return $info;
    }

    /*
     * 根据type_id获取首个文章所属信息
     *
     * By yuyang
     *
     * 2019-01-03
     */
    public static function getByTypeId($type_id)
    {
        $info=new ArticleAscription();
        $info = ArticleAscription::where('type_id', '=', $type_id)->first();
        return $info;
    }

    /*
     * 通过article_id和type_id获取所属关系
     *
     * By Yuyang
     *
     * 2019-01-03
     */
    public static function getOneByCon($article_id,$type_id){
        $info = ArticleAscription::where('type_id','=',$type_id)->where('article_id','=',$article_id)->first();
        return $info;
    }

    /*
     * 根据type_id获取多个文章所属信息
     *
     * By yuyang
     *
     * 2019-01-03
     */
    public static function getByTypeIds($type_id,$is_paginate)
    {
        $info=new ArticleAscription();
        $info = $info::where('type_id', '=', $type_id);
        $info = $info->orderBy('seq','asc');
        if ($is_paginate) {
            $info = $info->paginate(Utils::PAGE_SIZE);
        } else {
            $info = $info->get();
        }
        return $info;
    }

    /*
     * 根据多个type_id获取全部文章所属信息
     *
     * By yuyang
     *
     * 2019-01-03
     */
    public static function getByTypeorCon($info,$level='0')
    {
        $articles=new ArticleAscription();
        $type_ids = array();
        if ($level = 0) {
            foreach($info as $v){
                $type_ids[] = $v['id'];
            }
        }else if($level = 1){
            $type_ids = $info;
        }
        $articles = ArticleAscription::whereIn('type_id',$type_ids)->get();
        return $articles;
    }

    /*
     * 根据搜索条件获取全部文章所属信息
     *
     * By yuyang
     *
     * 2019-01-03
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new ArticleAscription();
        //相关条件
        if (array_key_exists('type_id', $con_arr) && !Utils::isObjNull($con_arr['type_id'])) {
            $infos = $infos->where('type_id', '=', $con_arr['type_id']);
        }
        if (array_key_exists('article_id', $con_arr) && !Utils::isObjNull($con_arr['article_id'])) {
            $infos = $infos->where('article_id', '=', $con_arr['article_id']);
        }
        $infos = $infos->orderBy('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $infos = $infos->paginate(Utils::PAGE_SIZE);
        } else {
            $infos = $infos->get();
        }
        return $infos;
    }

    /*
     * 设置文章所属信息，用于增加和编辑
     *
     * By Yuyang
     * 
     * 2019-01-03
     */
    public static function setInfo($data)
    {   
        $info = new ArticleAscription();
        if (array_key_exists('type_id', $data)) {
            $info->type_id = array_get($data, 'type_id');
        }
        if (array_key_exists('article_id', $data)) {
            $info->article_id = array_get($data, 'article_id');
        }
        return $info;
    }



    /*
     * 根据排序范围查找文章分类
     *
     * By Yuyang
     *
     * 2018-12-20
     */
    public static function getBySeq($con_arr, $type)
    {
        $type_info = new ArticleAscription();
        //相关条件
        if (array_key_exists('type_id', $con_arr) && !Utils::isObjNull($con_arr['type_id'])) {
            $type_info = $type_info->where('type_id', '=', $con_arr['type_id']);
        }
        if ($type == 'down') {
            $type_info = $type_info->where('seq', '>', $con_arr['seq']);
            $type_info = $type_info->where('seq', '<=', $con_arr['seq_down']);
        } else {
            $type_info = $type_info->where('seq', '<', $con_arr['seq']);
            $type_info = $type_info->where('seq', '>=', $con_arr['seq_up']);
        }
        $type_info = $type_info->get();
        return $type_info;
    }


}
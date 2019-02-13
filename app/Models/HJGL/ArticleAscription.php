<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/2/26
 * Time: 13:39
 */
namespace App\Models\HJGL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleAscription extends Model
{
	use SoftDeletes;    //使用软删除
	protected $connection = 'hjgldb';   //慢病管理数据库名
	protected $table = 't_article_ascription';
	public $timestamps = true;
	protected $dates = ['deleted_at'];  //软删除
}
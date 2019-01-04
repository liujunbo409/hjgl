<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/2/26
 * Time: 13:35
 */
namespace App\Models\HJGL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
//	use SoftDeletes;    //使用软删除
	protected $connection = 'hjgldb';   //环境监测管理数据库名
	protected $table = 't_admin';
	public $timestamps = true;
//	protected $dates = ['deleted_at'];  //软删除
}
<?php
/**
 * Created by PhpStorm.
 * User: Yuyang
 * Date: 2019/01/04
 */
namespace App\Models\HJGL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HandleRecord extends Model
{
	use SoftDeletes;    //使用软删除
	protected $connection = 'hjgldb';   //环境管理数据库名
	protected $table = 't_handle_record';
	public $timestamps = true;
	protected $dates = ['deleted_at'];  //软删除
}
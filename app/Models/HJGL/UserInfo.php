<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/19 0019
 * Time: 上午 10:26
 */
namespace App\Models\HJGL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model{
    use SoftDeletes;    //使用软删除
    protected $connection = 'hjgldb';   //环境监测管理数据库名
    protected $table = 't_user_info';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/18 0018
 * Time: 上午 9:42
 */
namespace App\Models\HJGL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNopay extends Model
{
    use SoftDeletes;    //使用软删除
    protected $connection = 'hjgldb';   //环境监测管理数据库名
    protected $table = 't_user_nopay';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}
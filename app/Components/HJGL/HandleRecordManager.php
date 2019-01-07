<?php

/**
 * Created by PhpStorm.
 * User: Yuyang
 * Date: 2019-01-03
 */

namespace App\Components\HJGL;

use App\Components\Utils;
use App\Models\HJGL\HandleRecord;

class HandleRecordManager
{

    /*
     * 记录操作动作
     *
     * By Junbo
     *
     * 2018-11-29
     */
    public static function record($re_arr)
    {
        $record = new HandleRecord();
        if (array_key_exists('t_table', $re_arr) && !Utils::isObjNull($re_arr['t_table'])) {
            $record->t_table = $re_arr['t_table'];
        }else{
        	$record->t_table = 'undefined';
        }
        if (array_key_exists('t_id', $re_arr) && !Utils::isObjNull($re_arr['t_id'])) {
            $record->t_id = $re_arr['t_id'];
        }else{
        	$record->t_id = '0';
        }
        if (array_key_exists('type', $re_arr) && !Utils::isObjNull($re_arr['type'])) {
            $record->type = $re_arr['type'];
        }else{
        	$record->type = 'undefined';
        }
        if (array_key_exists('role', $re_arr) && !Utils::isObjNull($re_arr['role'])) {
            $record->role = $re_arr['role'];
        }else{
        	$record->role = 'undefined';
        }
        if (array_key_exists('role_id', $re_arr) && !Utils::isObjNull($re_arr['role_id'])) {
            $record->role_id = $re_arr['role_id'];
        }else{
        	$record->role_id = '0';
        }
        if (array_key_exists('action', $re_arr) && !Utils::isObjNull($re_arr['action'])) {
            $record->action = $re_arr['action'];
        }else{
        	$record->action = 'undefined';
        }
        $record->save();
    }
}
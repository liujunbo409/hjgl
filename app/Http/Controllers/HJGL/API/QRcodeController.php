<?php
namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class QRcodeController extends Controller{
    public function index($tool_id){
        dd($tool_id);
        return('到达测试');
    }
}
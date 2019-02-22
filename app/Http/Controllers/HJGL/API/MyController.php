<?php

namespace App\Http\Controllers\HJGL\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\HJGL\VertifyManager;
use App\Http\Controllers\ApiResponse;


class MyController extends Controller{

    public function index(Request $request){
        $user_info = $request->session()->get('wechat_user');

        return view('HJGL.user.my.index');
    }

    public function info(){
        return view('HJGL.user.my.info');
    }

    public function phone(){
        return view('HJGL.user.my.phone');
    }

}
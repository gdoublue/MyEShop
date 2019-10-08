<?php

namespace app\admin\controller;

use think\Controller;
use Session;
class Adminbase extends Controller
{
    public function initialize(){
       $user_name = Session::get("user_name");
             if(!$user_name) {
                $this->error('请先登录管理员！','login/login');
             }
    }

}
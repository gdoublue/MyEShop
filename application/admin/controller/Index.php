<?php

namespace app\admin\controller;

use think\Controller;
use Session;
//定义后台首页类
class Index extends Controller
{
    //显示后台的首页
    public function index()
    {
        $user_name = Session::get("user_name");
        if($user_name) {
            return $this->fetch();
        }
        else {
            header("location: http://www.jayhui.cn/admin/login/login");
            exit;
        }
    }
}
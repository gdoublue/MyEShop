<?php

namespace app\admin\controller;

use think\Controller;

//定义后台首页类
class Index extends Controller
{
    //显示后台的首页
    public function index()
    {
        return $this->fetch();
    }
}
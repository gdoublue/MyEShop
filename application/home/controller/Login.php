<?php

namespace app\home\controller;

use think\Controller;
use Session,Request,Db,Config;

class Login extends Controller
{
    //退出登陆
    public function logout()
    {
        Session::delete("mid");
        Session::delete("mname");

        return json(["code"=>1]);
    }

    //用户登陆
    public function login()
    {
        //接收用户信息
        if (Request::isPost()) {
            //获取前端数据
            $data = Request::param();
            //获取用户名和密码
            $username = $data["username"];
            $password = $data["password"];

            //先去数据查找如用户名是否存在
            $userData = Db::name("member")->where("username",$username)->find();
            if ($userData) {
                if (md5(Config::get("cus.secure_salt").$password) == $userData["password"]) {
                    //将用户id和用户名称写入session中
                    Session::set("mid",$userData["id"]);
                    Session::set("mname",$userData["username"]);
                    return json(["code"=>2,"message"=>"登陆成功"]);
                } else {
                    return json(["code"=>0,"message"=>"密码不正确"]);
                }
            } else {
                return json(["code"=>0,"message"=>"用户名不存在"]);
            }
        }
        //显示用户登陆的页面
        return $this->fetch();
    }

}
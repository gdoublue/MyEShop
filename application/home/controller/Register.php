<?php

namespace  app\home\controller;

use think\Controller;
use Request,Db,Config,Session;
use app\home\validate\RegisterValidate;

class Register extends Controller
{
    //用户注册
    public function reg()
    {
        //接受用户注册的信息
        if (Request::isPost()){
            //接受数据
            $data = Request::param();
            //实例化验证器
            $regvalidate = new RegisterValidate;
            //验证数据
            if ($regvalidate->check($data)) {
                $username = $data["username"];
                $password = $data["password"];
                //构建添加的数据
                $data = [
                    "username" => $username,
                    "password"  =>  md5(Config::get("cus.secure_salt").$password),
                ];
                //将用户数据写入数据库
                $userId =  Db::name("member")->insertGetId($data);
                if ($userId) {
                    //将用户的id 和用户名写入session中
                    Session::set("mid",$userId);
                    Session::set("mname",$username);
                    //注册成功跳转至首页
                    $this->success("注册成功","/");
                } else {
                    $this->error("注册失败");
                }
            } else {
                $this->error($regvalidate->getError());
            }

        }
        //显示用户注册页面
        return $this->fetch();
    }

    //判断用户名是否存在
    public function memberIsHas()
    {
        //获取用户名
        $username = Request::param("username");
        $userData = Db::name("member")->where("username",$username)->find();
        if ($userData) {
            return json(["code"=>1]);
        } else {
            return json(["code"=>0]);
        }
    }
}
<?php

namespace app\admin\logic;

use Db;
use Config;
use Session;

//定义登录的业务逻辑处理类
class LoginLogic
{
    public  function login($username, $password)
    {
        //获取用户数据
        $user = Db::name("admin")->field("id,user_name,password")->where("user_name", $username)->find();
        //首先判断用户名是否存在
        if ($user) {
            //获取加密字符串
            $salt = Config::get("cus.secure_salt");
            if (md5($password.$salt) == $user['password']) {
                //登录成功将用户的id 和用户名存入session中(用于后面是否登录判断)
                Session::set("id", $user['id']);
                Session::set("user_name", $user["user_name"]);
                return ["status" => true, "msg" => "登录成功"];
            } else {
                return ["status" => false, "msg" => "密码不正确"];
            }
        } else {
            return ["status" => false, "msg" => "用户名不存在"];
        }

    }
}
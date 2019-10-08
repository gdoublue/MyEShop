<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;
use Request,Session;
use app\admin\validate\LoginValidate;
use app\admin\logic\LoginLogic;

//登录类 实现登录的逻辑
class Login extends Controller
{
    //实现登录的逻辑
    public function login()
    {
        if (Request::isPost()) {
            //获取数据
            $data = Request::param();
            //实例化验证器
            $validate = new LoginValidate;
            //验证数据
            if ($validate->check($data)){
                //获取用户名和密码
                $username = $data['username'];
                $password = $data['password'];

                //实例化登录业务逻辑类
                $loginLogic = new LoginLogic;
                return json($loginLogic->login($username, $password));


            } else {
                return json(["status" => false, "msg" => $validate->getError()]);
            }
        }
        return $this->fetch();
    }
    public function logout()
    {
        Session::delete("id");
        Session::delete("user_name");
    }
    //实现验证码
    public function verify()
    {
        //验证码的配置选项
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    50,
            // 验证码位数
            'length'      =>    2,
            // 关闭验证码杂点
            'useNoise'    =>    true,
        ];
        //实例化验证码类
        $captcha = new Captcha($config);
        //输出验证码
        return $captcha->entry();
    }

}
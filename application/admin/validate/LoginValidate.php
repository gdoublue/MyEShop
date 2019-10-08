<?php

namespace app\admin\validate;

use think\Validate;
use think\captcha\Captcha;

class LoginValidate extends Validate
{
    //定义验证规则
    protected $rule = [
        "vertify"  => "checkCode",
        "username" => "require",
        "password" => "require",
    ];

    //验证提示信息
    protected $message = [
        "username.require" => "用户名不能为空",
        "password.require" => "您的密码不能为空",
    ];

    //定义验证码的验证规则
    protected function checkCode($value, $rule, $data = [])
    {
        //实例化验证码类
        $captcha = new Captcha;
        return $captcha->check($value) ? true:"验证码错误";

    }
}




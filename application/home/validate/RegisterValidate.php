<?php

namespace app\home\validate;

use think\Validate;

class RegisterValidate extends Validate
{
    //定义验证规则
    protected $rule = [
        "username" => "require|length:3,15",
        "password" => "require",
    ];

    protected $message = [
        "username.require" => "用户名不能为空",
        "username.length" => "用户名的长度必须在3-15个字符之间",
        "password.require" => "密码不能为空",

    ];


    //自定义验证方法
}
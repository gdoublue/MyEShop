<?php

namespace  app\home\controller;

use think\Controller;
use Request,Db,Config,Session;
use app\home\validate\RegisterValidate;
use app\home\logic\Ucpaas;


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
                if(Session::has('phone')){
                    $phonenumber=Session::pull("phone");
                }
                else{
                    $this->error("请重新获取验证码","/");
                }
                //构建添加的数据
                $data = [
                    "username" => $username,
                    "password"  =>  md5(Config::get("cus.secure_salt").$password),
                   "phonenumber"=> $phonenumber,
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

    //发送手机验证码
    public function smsyzm()
    {
           $phone = Request::param("phone");


            $options['accountsid']='d5bcb68d6fba35e0232927d20ab78660';
            $options['token']='9ff778bb56fc2eafc193d49aba3343fb';
            $ucpaas = new Ucpaas($options);
            $appid = "243e663b623e44e698583601b05c6638";	//应用的ID，可在开发者控制台内的短信产品下查看
            $templateid = "503193";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
            $yzmcode=mt_rand(1001,9899);
            $param = "$yzmcode,300"; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
            $mobile = $phone;
            $uid = "007";
            Session::set("phone",$phone);
            Session::set("yzmcode",$yzmcode);
           $res= $ucpaas->SendSms($appid,$templateid,$param,$mobile,$uid);
           if($res){
               return json(["smsready"=>1,"num"=>$phone]);
           }else
           {
               return json(["smsready"=>0,"num"=>"***"]);
           }

    }
    //校验验证码
    public function  smsverify()
    {
       $smscode = Request::param("smscode");
       $getsmscode=Session::get("yzmcode");
      if( $smscode == $getsmscode){
          return json(["code"=>1]);
      } else {
          return json(["code"=>0]);
      }
    }

}
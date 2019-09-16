<?php

namespace app\home\controller;

use think\Controller;
use Db;
use Request,Session;

class Index extends Controller
{
    //显示前台首页
    public function index()
    {
        //获取分类数据
        $list = \getCategory();
        //获取导航的数据
        $navigation = \getNavigation();
        //获取文章数据
        $article = Db::name("article")->field("title,url")->select();
        //获取热卖商品
        $hotGoods = Db::name("goods")->where("is_hot", 1)->field("goods_id,goods_name,price,introduction,picture")->select();
        foreach ($hotGoods as &$value) {
            $picId = $value["picture"];
            $picture = Db::name("picture")->field("pic_addr")->where("id",$picId)->find();
            $value["pic_addr"] = "uploads/".$picture["pic_addr"];
        }


        $this->assign("hotGoods", $hotGoods);
        $this->assign("article", $article);
        $this->assign("navigation", $navigation);
        $this->assign("list", $list);
        return $this->fetch();
    }




    //获取前台首页的轮播图
    public function platformadvlist()
    {
        //获取广告位
        $apId = Request::param("ap_id");
        //获取广告数据
        $advList = Db::name("adv")->where("ap_id", $apId)->select();
        return json($advList);
    }
    //获取用户的登录信息
    public function getlogininfo()
    {
        //从session中获取用户id
        $mid = Session::get("mid");
        if ($mid) {  //只要为真 用户已经登陆
            //构建返回的数据
            $data = [
                "user_info" => ["nick_name"=>Session::get("mname")],
            ];

            return json($data);
        } else
        return false;
    }


}
<?php

namespace app\home\controller;

use think\Controller;
use Db,Request;

class Goods extends Controller
{
    public function goodsinfo()
    {
        //获取商品的分类数据
        $list = \getCategory();
        //获取导航的数据
        $navigation = \getNavigation();

        //获取商品的id
        $goodsId = Request::param("goodsid");
        $goodsInfo = Db::name("goods")->field("goods_id,goods_name,price,introduction,picture,category_id,img_id_array,stock")->where("goods_id",$goodsId)->find();
        //获取分类id
        $categoryId = $goodsInfo["category_id"];
        $categoryName = Db::name("category")->field("category_name")->where("category_id",$categoryId)->find();
        $goodsInfo["category_name"] = $categoryName["category_name"];
        //获取商品的主图
        $picId = $goodsInfo["picture"];
        $picture = Db::name("picture")->field("pic_addr")->where("id",$picId)->find();
        $goodsInfo["pic_addr"] = "uploads/".$picture["pic_addr"];

        //获取商品的缩略图
        $imgArray = explode(",",$goodsInfo["img_id_array"]);
        array_shift($imgArray);
        $picAddr = Db::name("picture")->field("pic_addr")->whereIn("id",$imgArray)->select();
        foreach ($picAddr as &$addr) {
            $addr["pic_addr"] = "uploads/".$addr["pic_addr"];
        }

        $this->assign("goodsInfo",$goodsInfo);
        $this->assign("picAddr",$picAddr);
        $this->assign("list",$list);
        $this->assign("navigation",$navigation);
        return $this->fetch();
    }
}
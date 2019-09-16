<?php

namespace app\home\controller;

use think\Controller;
use Db,Request,Session;

class Cart extends Controller
{
    //购物车列表
    public function cartlist()
    {
        //判断用户是否已经登录
        $mid = Session::get("mid");
        if ($mid) {
            $list = \getCategory();
            //获取导航的数据
            $navigation = \getNavigation();

            //查询购物车 获取用户的商品数据
            $cartData = Db::name("cart")->where("member_id",$mid)->select();
            //定义二维数组存储商品的信息
            $goods = [];
            $num=0;
            $asum=0;
            foreach ($cartData as $cart) {
                //获取商品的id
                $goods_id = $cart["goods_id"];
                $goodsData = Db::name("goods")->field("goods_id,goods_name,price,picture")->where("goods_id",$goods_id)->find();
                //根据图片的id
                $picid = $goodsData["picture"];
                $picData = Db::name("picture")->field("pic_addr")->where("id",$picid)->find();
                //图片的地址
                $pic_addr = "/uploads/".$picData["pic_addr"];
                //总金额
                $sum = $cart["goods_number"]*$goodsData["price"];
                $arr["goods_id"] = $goods_id;  //商品id
                $arr["goods_price"] = $goodsData["price"];  //商品价格
                $arr["pic_addr"] = $pic_addr;  //图片地址
                $arr["sum"] = $sum;   // 总金额
                $arr["name"] = $goodsData["goods_name"];  //商品名称
                $arr["count"] = $cart["goods_number"];
                $arr["cart_id"] = $cart["id"];
                $num += $cart["goods_number"];
                $asum+=500;

                $goods[] = $arr;

            }
              $carts["amount"]=$num;
            $carts["asum"]=$asum;

        } else {
            //没有登录跳转到登录页面
//            echo "<script>alert('没有登录');location.href = 'http://www.jayhui.cn/home/login/login'</script>";
            header("location: http://www.jayhui.cn/home/login/login");
            exit;
        }

        //模板赋值
        $this->assign("carts", $carts);
        $this->assign("goodsData", $goods);
        $this->assign("list",$list);
        $this->assign("navigation",$navigation);
        return $this->fetch();
    }
    //判断是否登录
    public function isLogin()
    {
        //判断session中是否存在用户的id
        $mid = Session::get("mid");
        if ($mid) {
            return json(["code"=>1]);
        } else {
            return json(["code"=>0]);
        }
    }

    //加入购物车
    public function addCart()
    {
        //获取商品数据
        $cart_detail = Request::param();
        //获取用户id
        $mid = Session::get("mid");
        //获取商品的id
        $goods_id = $cart_detail["cart_detail"]["goods_id"];
        //先判断购物车商品是否已经存在
        $goodsData = Db::name("cart")->where("goods_id",$goods_id)->find();

        //获取商品的数量
        $count = $cart_detail["cart_detail"]["count"];
        if ($goodsData) {
            //在原先的基础上进行累加
            $rows = Db::name("cart")->where("goods_id",$goods_id)->setInc("goods_number",$count);
            if (!$rows) {
                return -1;
            }
        } else {
            //构建添加的数据
            $data = [
                "goods_id" => $goods_id,
                "member_id" => $mid,
                "goods_number" => $count
            ];
            //商品添加至购物车表
            $rows = Db::name("cart")->insert($data);
            if (!$row) {
                return -1;
            }
        }

    }

    //获取当前用户的商品数量
    public function getNum()
    {
        //获取用户的id
        $mid = Session::get("mid");
        $goodsData = Db::name("cart")->where("member_id",$mid)->select();
        //商品的总数量
        $num = 0;
        foreach ($goodsData as $value) {
            $num += $value["goods_number"];
        }

        return $num;
    }

    //删除购物车的商品
    public function delcartgoods()
    {
        //获取购物车的id
        $cart_id = Request::param("cart_id");

        //根据购物车中的id删除商品
        $row = Db::name("cart")->where("id",$cart_id)->delete();
        if ($row) {
            return json(["code"=>1]);
        } else {
            return json(["code"=>0]);
        }

    }

    //增加购物车中数量
    public function changeNum()
    {
        //购物车的id
        $cart_id = Request::param("cart_id");
        //商品的数量
        $num = Request::param("num");

        //修改购物车id 对应的商品的数量
        $row =  Db::name("cart")->where("id",$cart_id)->data(["goods_number"=>$num])->update();

        if ($row) {
            return json(["code"=>1]);
        } else {
            return json(["code"=>0]);
        }
    }

    //清空购物车
    public function emptycart()
    {
        //获取用户的id
        $mid =  Session::get("mid");
        //删除用户id 对应的商品
        $row =  Db::name("cart")->where("member_id",$mid)->delete();

        if ($row) {
            return json(["code"=>1]);
        } else {
            return json(["code"=>0]);
        }
    }

}